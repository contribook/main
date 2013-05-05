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
	 * @param string $count
	 */
	public static function show($userid,$count) {
		$content=array();
	
		// fetch the from the DB
		$request = CONTRIBOOK_DB::query('select message,url,timestamp from github where user="'.addslashes($userid).'" order by timestamp desc limit ' . addslashes($count));
		$num = CONTRIBOOK_DB::numrows($request);
		for ($i = 0; $i < $num; $i++) {
			$item=CONTRIBOOK_DB::fetch_assoc($request);
			$content[]=$item;
	
		}
		
		// render the template
		CONTRIBOOK::showtemplate('github',$content);

	}


	/**
 	 * Import the github items from all users
	 *
 	 * @param string $userid
	 * @param string $count
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
		$request=CONTRIBOOK_DB::query('delete from github where user="' . addslashes($userid) . '"');
		CONTRIBOOK_DB::free_result($request);
	
		// store the new items in the DB
		$items = $feed->get_items(0, $count);
		if(count($items)>0) {
			foreach ($items as $item) {
				$request = CONTRIBOOK_DB::query('insert into github (user,message,url,timestamp) ' .
					 'values("' . addslashes($userid) . '","' .
						 addslashes($item->get_title()) . '","' .
						 addslashes($item->get_permalink()) . '","' .
						 addslashes(strtotime($item->get_date())).'")');
				CONTRIBOOK_DB::free_result($request);
			}
		}
		unset($feed);
	}

}

