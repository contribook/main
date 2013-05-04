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

    // remove old stuff
    $request=CONTRIBOOK_DB::query('delete from ocs where category="'.addslashes($category).'"');
    CONTRIBOOK_DB::free_result($request);


    // store it in the database
    $tmp=$data->data->content;
    for($i = 0; $i < count($tmp); $i++) {
      $request=CONTRIBOOK_DB::query('insert into ocs (category,name,type,user,url,preview,timestamp,description) values("'.$category.'","'.addslashes($tmp[$i]->name).'", "'.addslashes($tmp[$i]->typeid).'","'.addslashes($tmp[$i]->personid).'","'.addslashes($tmp[$i]->detailpage).'", "'.addslashes($tmp[$i]->smallpreviewpic1).'","'.addslashes(strtotime($tmp[$i]->changed)).'","'.addslashes($tmp[$i]->description).'"  ) ');
      CONTRIBOOK_DB::free_result($request);
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

    $sql='select name,type,user,url,preview,timestamp,description from ocs where category='.addslashes($category).' order by timestamp desc limit '.addslashes($count);
    $request=CONTRIBOOK_DB::query($sql);
    $num=CONTRIBOOK_DB::numrows($request);
      
    $content=array();
    for($i = 0; $i < $num; $i++) {
      $data=CONTRIBOOK_DB::fetch_assoc($request);
      $content[]=$data;
    }
    CONTRIBOOK_DB::free_result($request);
  
    // render the template
    CONTRIBOOK::showtemplate('ocs',$content);
    
  }


}
