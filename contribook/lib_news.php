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
    $request=CONTRIBOOK_DB::query('delete from news');
    CONTRIBOOK_DB::free_result($request);

    // save the new entries in the DB
    $items=$feed->get_items(0, 10);
    foreach ($items as $item){
      $request=CONTRIBOOK_DB::query('insert into news (title,url,timestamp) values("'.addslashes($item->get_title()).'","'.addslashes($item->get_permalink()).'","'.addslashes(strtotime($item->get_date())).'") ');
      CONTRIBOOK_DB::free_result($request);
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
    $request=CONTRIBOOK_DB::query('select title,url,timestamp from news order by timestamp desc limit '.addslashes($start).','.$count);
    $num=CONTRIBOOK_DB::numrows($request);
    
    $content=array();
    for($i = 0; $i < $num; $i++) {
      $content[]=CONTRIBOOK_DB::fetch_assoc($request);
    }
    CONTRIBOOK_DB::free_result($request);
        
    // render the template
    CONTRIBOOK::showtemplate('news',$content);
  }
    
    
  
}
