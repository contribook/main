<?php

/**
* CONTRIBOOK OCS Lib
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
* A OCS client class. Use it to fetch content.
*
*/
class CONTRIBOOK_OCS {


  /**
  * Import all the OCS content categories from a remote server.
  *
  */
  public static function importall(){
    global $CONTRIBOOK_ocs_categories;
   
    foreach($CONTRIBOOK_ocs_categories as $key=>$value) {
      CONTRIBOOK_OCS::import($key,$value);
    }
  }


  /**
  * Import a specific OCS category
  *
  * @param int $category
  * @param string $ocscategories
  */
  private static function import($category,$ocscategories){
    global $CONTRIBOOK_ocs_server;
    $url=$CONTRIBOOK_ocs_server.'content/data?categories='.$ocscategories['ids'].'&sortmode=new&page=0&pagesize=10';

    // load and parse the data
    $xml=file_get_contents($url);
    $data=simplexml_load_string($xml);

    if(isset($data->data->content)){
     // remove old stuff
      $stmt=CONTRIBOOK_DB::prepare('delete from ocs where category=:category');
      $stmt->bindParam(':category', $category, PDO::PARAM_STR);
      $stmt->execute();

     // store it in the database
      $tmp=$data->data->content;
      for($i = 0; $i < count($tmp); $i++) {
        $stmt=CONTRIBOOK_DB::prepare('insert into ocs (category,name,type,user,url,preview,timestamp,description) values(:category,:name,:type,:user,:url,:preview,:timestamp,:description)');
        $timestamp=strtotime($tmp[$i]->changed);
        $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        $stmt->bindParam(':name', $tmp[$i]->name, PDO::PARAM_STR);
        $stmt->bindParam(':type', $tmp[$i]->typeid, PDO::PARAM_STR);
        $stmt->bindParam(':user', $tmp[$i]->personid, PDO::PARAM_STR);
        $stmt->bindParam(':url', $tmp[$i]->detailpage, PDO::PARAM_STR);
        $stmt->bindParam(':preview', $tmp[$i]->smallpreviewpic1, PDO::PARAM_STR);
        $stmt->bindParam(':timestamp', $timestamp, PDO::PARAM_STR);
        $stmt->bindParam(':description', $tmp[$i]->description, PDO::PARAM_STR);
        $stmt->execute();
        
      }
    }

  }


  /**
  * Show a list of the imported OCS content
  *
  * @param int $category
  * @param int $count
  */
  static function show($category,$count){
    global $CONTRIBOOK_ocs_server;

    $sql='select name,type,user,url,preview,timestamp,description from ocs where category=:category order by timestamp desc limit :count';
    $stmt=CONTRIBOOK_DB::prepare($sql);
    $stmt->bindParam(':category', $category, PDO::PARAM_STR);
    $stmt->bindParam(':count', $count, PDO::PARAM_INT);
    $stmt->execute();

    $num=$stmt->rowCount();
      
    $content=array();
    for($i = 0; $i < $num; $i++) {
      $data=$stmt->fetch(PDO::FETCH_ASSOC);
      $content[]=$data;
    }
  
    // render the template
    CONTRIBOOK::showtemplate('ocs',$content);
    
  }


}
