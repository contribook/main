<?php

/**
* CONTRIBOOK News Lib
*
* @author Frank Karlitschek
* @copyright 2013 Frank Karlitschek frank@karlitschek.de
*
* This library is free software; you can redistribute it and/or
* modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
* License as published by the Free Software Foundation; either
* version 3 of the License, or any later version.
*
* This library is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU AFFERO GENERAL PUBLIC LICENSE for more details.
*
* You should have received a copy of the GNU Lesser General Public
* License along with this library.  If not, see <http://www.gnu.org/licenses/>.
*
*/


/**
 * Class to import and show a RSS news feed
 *
 */
class CONTRIBOOK_NEWS {


  /**
   * Import a RSS news feed
   *
   */
  public static function import(){
    
    global $CONTRIBOOK_news_url;
    
    $feed = new SimplePie();
    $feed->set_feed_url($CONTRIBOOK_news_url);
    $feed->init();
    
    // remove old stuff
    $stmt=CONTRIBOOK_DB::prepare('delete from news');
    $stmt->execute();
    
    // save the new entries in the DB
    $items=$feed->get_items(0, 10);
    foreach ($items as $item){
        $stmt=CONTRIBOOK_DB::prepare('insert into news (title,url,timestamp) values(:title,:url,:timestamp)');
        $title=$item->get_title();
        $url=$item->get_permalink();
        $timestamp=strtotime($item->get_date());
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':url', $url, PDO::PARAM_STR);
        $stmt->bindParam(':timestamp', $timestamp, PDO::PARAM_STR);
        $stmt->execute();
    }
    unset($feed);
  }
    
    
 /**
    * Show a import RSS news feed
    *
    * @param int $start
    * @param int $count
    */
  public static function show($start,$count){
  
    // read it from the DB
    $stmt=CONTRIBOOK_DB::prepare('select title,url,timestamp from news order by timestamp desc limit :start,:count');
    $stmt->bindParam(':start', $start, PDO::PARAM_INT);
    $stmt->bindParam(':count', $count, PDO::PARAM_INT);
    $stmt->execute();

    $num=$stmt->rowCount();
    
    $content=array();
    for($i = 0; $i < $num; $i++) {
      $content[]=$stmt->fetch(PDO::FETCH_ASSOC);
    }

        
    // render the template
    CONTRIBOOK::showtemplate('news',$content);
  }
    
    
  
}
