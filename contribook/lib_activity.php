<?php

/**
* CONTRIBOOK Activity Lib
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
* Class to show all activity posts
*
*/
class CONTRIBOOK_ACTIVITY {


  /**
  * Show a list of Activity from all users
  *
  * @param string $start
  * @param string $count
  */
  public static function show($start,$count) {
    $content=array();

    $stmt = CONTRIBOOK_DB::prepare('select user,message,url,timestamp from activity order by timestamp desc limit :start, :count');
    $stmt->bindParam(':start', $start, PDO::PARAM_INT);
    $stmt->bindParam(':count', $count, PDO::PARAM_INT);
    $stmt->execute();
    $num = $stmt->rowCount();
    
    for ($i = 0; $i < $num; $i++) {
      $blog=$stmt->fetch(PDO::FETCH_ASSOC);
      $user = CONTRIBOOK_USER::getuser($blog['user']);
      if(isset($user['name']) and $user['name']<>'') $blog['name']=$user['name'];
      if(isset($user['picture_50']) and $user['picture_50']<>'') $blog['picture_50']=$user['picture_50']; else $blog['picture_50']='';
      $content[]=$blog;
    }
    
    CONTRIBOOK::showtemplate('activities',$content);
  }


  /**
  * Show a list of Activity of a user
  *
  * @param string $user
  * @param string $start
  * @param string $count
  */

  public static function showuser($user,$start,$count) {
    $content=array();

    $stmt = CONTRIBOOK_DB::prepare('select user,message,url,timestamp from activity where user=:user order by timestamp desc limit :start,:count');
    $stmt->bindParam(':user', $user, PDO::PARAM_STR);
    $stmt->bindParam(':start', $start, PDO::PARAM_INT);
    $stmt->bindParam(':count', $count, PDO::PARAM_INT);
    $stmt->execute();
    $num = $stmt->rowCount();
    
    for ($i = 0; $i < $num; $i++) {
      $blog=$stmt->fetch(PDO::FETCH_ASSOC);
      $content[]=$blog;
    }
    CONTRIBOOK::showtemplate('activity',$content);
  }



}
