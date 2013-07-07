<?php

/**
* CONTRIBOOK Microblogging Lib
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
* Class to import and show a Twitter posts of users
*
*/
class CONTRIBOOK_MICROBLOG {


  /**
   * Import all Twitter post from all users
   *
   */
  public static function importall() {

    if(CONTRIBOOK_TWITTER_OAUTH_ACCESS_TOKEN<>''){
      $users = CONTRIBOOK_USER::getusers();
      foreach($users as $userid) {
        $data = CONTRIBOOK_USER::getuser($userid);
        if(isset($data['twitter']) and $data['twitter']<>'') {
          CONTRIBOOK_MICROBLOG::import($userid,$data['twitter']);
        }
      }
    }
  }


 /**
 * Import all Twitter post from a user
 *
 * @param string $userid
 * @param string $twitterid
 */
  private static function import($userid,$twitterid){

    // The Twitter URLs
    $url = "https://api.twitter.com/1.1/statuses/user_timeline.json";
    $homepage = 'https://twitter.com/';

    // Build the OAuth header
    $oauth = array(
                'screen_name' => $twitterid,
                'count' => 200,
                'oauth_consumer_key' => CONTRIBOOK_TWITTER_CONSUMER_KEY,
                'oauth_nonce' => time(),
                'oauth_signature_method' => 'HMAC-SHA1',
                'oauth_token' => CONTRIBOOK_TWITTER_OAUTH_ACCESS_TOKEN,
                'oauth_timestamp' => time(),
                'oauth_version' => '1.0');

    $base_info = CONTRIBOOK_MICROBLOG::buildBaseString($url, 'GET', $oauth);
    $composite_key = rawurlencode(CONTRIBOOK_TWITTER_CONSUMER_SECRET) . '&' . rawurlencode(CONTRIBOOK_TWITTER_OAUTH_ACCESS_TOKEN_SECRET);
    $oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
    $oauth['oauth_signature'] = $oauth_signature;

    // Make Requests
    $header = array(CONTRIBOOK_MICROBLOG::buildAuthorizationHeader($oauth), 'Expect:');
    $options = array( CURLOPT_HTTPHEADER => $header,
                  //CURLOPT_POSTFIELDS => $postfields,
                  CURLOPT_HEADER => false,
                  CURLOPT_URL => $url . '?screen_name='.$twitterid.'&count=200',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_SSL_VERIFYPEER => false);

    $feed = curl_init();
    curl_setopt_array($feed, $options);
    $json = curl_exec($feed);
    curl_close($feed);
    $twitter_data = json_decode($json);

    // deleting the old stuff
    $stmt=CONTRIBOOK_DB::prepare('delete from activity where type="microblog" and user=:userid');
    $stmt->bindParam(':userid', $userid, PDO::PARAM_STR);
    $stmt->execute();
    
    if(count($twitter_data)>0) {
      foreach($twitter_data as $tweet) {
        if(isset($tweet->text)) {
          if(CONTRIBOOK_TWITTERFILTER=='' or (stripos($tweet->text,CONTRIBOOK_TWITTERFILTER)<>false)) {
            $stmt=CONTRIBOOK_DB::prepare('insert into activity (user,type,message,url,timestamp) values(:userid,"microblog",:message,:url,:timestamp)');
            $timestamp=strtotime($tweet->created_at);
            $stmt->bindParam(':userid', $userid, PDO::PARAM_STR);
            $stmt->bindParam(':message', $tweet->text, PDO::PARAM_STR);
            $url=$homepage.$twitterid;
            $stmt->bindParam(':url', $url, PDO::PARAM_STR);
            $stmt->bindParam(':timestamp', $timestamp, PDO::PARAM_STR);
            $stmt->execute();
          }
        }else{
          echo('can not parse Twitter API response for '.$userid);
          print_r($tweet);
        }
      }
    }
  }


 /**
 * Build a base String
 *
 * @param string $baseURI
 * @param string $method
 * @param string $params
 */
  private static function buildBaseString($baseURI, $method, $params) {
    $r = array();
    ksort($params);
    foreach($params as $key=>$value){
      $r[] = "$key=" . rawurlencode($value);
    }
    return $method."&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));
  }

 /**
 * Build an OAuth Header
 *
 * @param string $oauth
 */
  private static function buildAuthorizationHeader($oauth) {
    $r = 'Authorization: OAuth ';
    $values = array();
    foreach($oauth as $key=>$value) $values[] = "$key=\"" . rawurlencode($value) . "\"";
    $r .= implode(', ', $values);
    return $r;
  }


  /**
  * Show a list of Twitter posts from all users
  *
  * @param string $start
  * @param string $count
  */
  public static function show($start,$count) {
    $content=array();

    
    $stmt = CONTRIBOOK_DB::prepare('select user,message,url,timestamp from activity where type="microblog" order by timestamp desc limit :start,:count');
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
    
    CONTRIBOOK::showtemplate('microblogs',$content);
  }


  /**
  * Show a list of Twitter posts of a user
  *
  * @param string $user
  * @param string $start
  * @param string $count
  */

  public static function showuser($user,$start,$count) {
    $content=array();

    $stmt = CONTRIBOOK_DB::prepare('select user,message,url,timestamp from activity where type="microblog" and user=:user order by timestamp desc limit :start,:count');
    $stmt->bindParam(':user', $user, PDO::PARAM_STR);
    $stmt->bindParam(':start', $start, PDO::PARAM_INT);
    $stmt->bindParam(':count', $count, PDO::PARAM_INT);
    $stmt->execute();

    $num = $stmt->rowCount();
    for ($i = 0; $i < $num; $i++) {
      $blog=$stmt->fetch(PDO::FETCH_ASSOC);
      $content[]=$blog;
    }
    CONTRIBOOK::showtemplate('microblog',$content);
  }



}
