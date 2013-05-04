<?php

/**
* CONTRIBOOK Blog Lib
*
* @author Frank Karlitschek
* @copyright 2013 Frank Karlitschek frank@karlitschek.de
*
* @modifications Added default image fallback. Improve code readability
* @author Sayak Banerjee
* @copyright 2010 Sayak Banerjee <sayakb@kde.org>
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
 * @param string count
 */
class CONTRIBOOK_BLOG {

	/**
	* Show the list of blog posts
	*
	* @param string $count
	*/
	public static function show($count) {
		$content=array();

		$request = CONTRIBOOK_DB::query('select user,title,url,timestamp,content from blog order by timestamp desc limit ' . addslashes($count));
		$num = CONTRIBOOK_DB::numrows($request);
		for ($i = 0; $i < $num; $i++) {

			$blog=CONTRIBOOK_DB::fetch_assoc($request);
			$user = CONTRIBOOK_USER::getuser($blog['user']);
			$blog['name']=$user['name'];
			$blog['picture']=$user['picture_50'];
			$content[]=$blog;

		}
		CONTRIBOOK::showtemplate('blogs',$content);
	}


	/**
	 * Show the list of blog posts from a user
	 *
	 * @param string $userid
	 * @param string $count
	 */
	public static function showuser($userid,$count) {
		$content=array();
	
		// fetch the from the DB
		$request = CONTRIBOOK_DB::query('select title,url,timestamp,content from blog where user="'.addslashes($userid).'" order by timestamp desc limit ' . addslashes($count));
		$num = CONTRIBOOK_DB::numrows($request);
		for ($i = 0; $i < $num; $i++) {
			$blog=CONTRIBOOK_DB::fetch_assoc($request);
			$content[]=$blog;
	
		}
		
		// render the template
		CONTRIBOOK::showtemplate('userblogs',$content);

	}


	/**
 	 * Import the blog posts from all users
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
			if(isset($data['rssurl'])) {
				CONTRIBOOK_BLOG::import($userid,$data['rssurl'], 10);
			}
		}
	}



	/**
	 * Import the blog posts from a user
	 *
	 * @param string $userid
	 * @param string $blogurl
	 * @param int $count
	 */
	private static function import($userid, $blogurl, $count) {

		// fetch the RSS
		$feed = new SimplePie();
		$feed->set_feed_url($blogurl);
		$feed->init();

		// remove old stuff
		$request=CONTRIBOOK_DB::query('delete from blog where user="' . addslashes($userid) . '"');
		CONTRIBOOK_DB::free_result($request);
	
		// store the new items in the DB
		$items = $feed->get_items(0, $count);
		foreach ($items as $item) {
			$request = CONTRIBOOK_DB::query('insert into blog (user,title,url,content,timestamp) ' .
				 'values("' . addslashes($userid) . '","' .
					 addslashes($item->get_title()) . '","' .
					 addslashes($item->get_permalink()) . '","' .
					 addslashes($item->get_description()) . '","' .
					 addslashes(strtotime($item->get_date())).'")');
			CONTRIBOOK_DB::free_result($request);
		}
		unset($feed);
	}

}

