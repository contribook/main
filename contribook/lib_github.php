<?php

/**
* CONTRIBOOK Github Lib
*
* @author Frank Karlitschek
* @copyright 2013 Frank Karlitschek frank@karlitschek.de
*
*
* This library is free software; you can redistribute it and/or
* modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
* License as published by the Free Software Foundation; either
* version 3 of the License, or any later version.
*
* This library is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	See the
* GNU AFFERO GENERAL PUBLIC LICENSE for more details.
*
* You should have received a copy of the GNU Lesser General Public
* License along with this library.	If not, see <http://www.gnu.org/licenses/>.
*
*/

/**
 * Show a list of forum posts
 *
 */
class CONTRIBOOK_GITHUB {


	/**
	 * Show the list of github messages from a user
	 *
	 * @param string $userid
	 * @param int $start
	 * @param int $count
	 */
	public static function show($userid,$start,$count) {
		$content=array();
	
		// fetch the from the DB
		$stmt = CONTRIBOOK_DB::prepare('select message,url,timestamp from activity where type="github" and user=:userid order by timestamp desc limit :start,:count');
		$stmt->bindParam(':userid', $userid, PDO::PARAM_STR);
		$stmt->bindParam(':start', $start, PDO::PARAM_INT);
		$stmt->bindParam(':count', $count, PDO::PARAM_INT);
		$stmt->execute();
		$num = $stmt->rowCount();
		
		for ($i = 0; $i < $num; $i++) {
			$item=$stmt->fetch(PDO::FETCH_ASSOC);
			$content[]=$item;
	
		}
		
		// render the template
		CONTRIBOOK::showtemplate('github',$content);

	}

	/**
	 * Show the list of github messages from all user
	 *
	 * @param int $count
	 * @param int $start
	 */
	public static function showall($start,$count) {
		$content=array();
	
		// fetch the from the DB
		$stmt = CONTRIBOOK_DB::prepare('select message,url,timestamp from activity where type="github" order by timestamp desc limit :start,:count');
		$stmt->bindParam(':start', $start, PDO::PARAM_INT);
		$stmt->bindParam(':count', $count, PDO::PARAM_INT);
		$stmt->execute();
		$num = $stmt->rowCount();
		for ($i = 0; $i < $num; $i++) {
			$item=$stmt->fetch(PDO::FETCH_ASSOC);
			$content[]=$item;
		}
		
		// render the template
		CONTRIBOOK::showtemplate('github',$content);

	}


	/**
 	 * Import the github items from all users
	 *
 	 * @param string $userid
	 * @param int $count
	*/
	public static function importall() {

		// fetch a list of all users
		$users = CONTRIBOOK_USER::getusers();
	
		// fetch the RSS feeds
		foreach($users as $userid) {
			$data = CONTRIBOOK_USER::getuser($userid);
			if(isset($data['github']) and $data['github']<>'') {
				CONTRIBOOK_GITHUB::import($userid,$data['github'], 10);
			}
		}
	}



	/**
	 * Import the github messages from a user
	 *
	 * @param string $userid
	 * @param string $githubaccount
	 * @param int $count
	 */
	private static function import($userid, $githubaccount, $count) {

		$url='https://github.com/'.$githubaccount.'.atom';

		// fetch the RSS
		$feed = new SimplePie();
		$feed->set_feed_url($url);
		$feed->init();

		// remove old stuff
		$stmt=CONTRIBOOK_DB::prepare('delete from activity where type="github" and user=:userid');
		$stmt->bindParam(':userid', $userid, PDO::PARAM_STR);
		$stmt->execute();
		$num = $stmt->rowCount();
			
		// store the new items in the DB
		$items = $feed->get_items(0, $count);
		if(count($items)>0) {
			foreach ($items as $item) {
				$stmt = CONTRIBOOK_DB::prepare('insert into activity (type,user,message,url,timestamp) values("github", :userid, :message, :url, :timestamp)');
				$stmt->bindParam(':userid', $userid, PDO::PARAM_STR);
				$message=$item->get_title();
				$url=$item->get_permalink();
				$timestamp=strtotime($item->get_date());
				$stmt->bindParam(':message', $message, PDO::PARAM_STR);
				$stmt->bindParam(':url', $url, PDO::PARAM_STR);
				$stmt->bindParam(':timestamp', $timestamp, PDO::PARAM_STR);
				$stmt->execute();
		
			}
		}
		unset($feed);
	}

}

