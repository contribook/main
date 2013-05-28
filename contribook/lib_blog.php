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
 */
class CONTRIBOOK_BLOG {

	/**
	* Show the list of blog posts
	*
	* @param int $start
	* @param int $count
	*/
	public static function show($start,$count) {
		$content=array();

		$request = CONTRIBOOK_DB::query('select user,message,url,timestamp,content from activity where type="blog" order by timestamp desc limit '.addslashes($start).',' . addslashes($count));
		$num = CONTRIBOOK_DB::numrows($request);
		for ($i = 0; $i < $num; $i++) {

			$blog=CONTRIBOOK_DB::fetch_assoc($request);
			$user = CONTRIBOOK_USER::getuser($blog['user']);
			if(isset($user['name']) and $user['name']<>'') $blog['name']=$user['name'];
			if(isset($user['picture_50']) and $user['picture_50']<>'') $blog['picture']=$user['picture_50']; else $blog['picture']='';
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
		$request = CONTRIBOOK_DB::query('select message,url,timestamp,content from activity where type="blog" and user="'.addslashes($userid).'" order by timestamp desc limit ' . addslashes($count));
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
			if(isset($data['rssurl']) and $data['rssurl']<>'') {
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
		$request=CONTRIBOOK_DB::query('delete from activity where type="blog" and user="' . addslashes($userid) . '"');
		CONTRIBOOK_DB::free_result($request);
	
		// store the new items in the DB
		$items = $feed->get_items(0, $count);
		foreach ($items as $item) {
			$request = CONTRIBOOK_DB::query('insert into activity (type,user,message,url,content,timestamp) ' .
				 'values("blog", "' . addslashes($userid) . '","' .
					 addslashes($item->get_title()) . '","' .
					 addslashes($item->get_permalink()) . '","' .
					 addslashes($item->get_description()) . '","' .
					 addslashes(strtotime($item->get_date())).'")');
			CONTRIBOOK_DB::free_result($request);
		}
		unset($feed);
	}

	/**
	* Show an RSS feed of the blog posts.
	*
	* @param string $title
	* @param string $description
	* @param string $link
	* @param string $count
	*/
	public static function showrss($title,$description,$link,$count) {
		$content=array();
	
		$request = CONTRIBOOK_DB::query('select user,message,url,timestamp,content from activity where type="blog" order by timestamp desc limit ' . addslashes($count));
		$num = CONTRIBOOK_DB::numrows($request);
		for ($i = 0; $i < $num; $i++) {
			$blog=CONTRIBOOK_DB::fetch_assoc($request);
			$c=array();
			$c['TITLE']=$blog['message'];
			$c['DESCRIPTION']=$blog['content'];
			$c['LINK']=$blog['url'];
			$c['DATE']=date('r',$blog['timestamp']);
			$content[]=$c;
		}
		
		$rss=CONTRIBOOK_BLOG::generaterss($link, $title, $link, $description, $link, $content);
		echo($rss);
	}
	
	
	
	/**
	* Generate an RSS feed
	*
	* @param string $host
	* @param string $title
	* @param string $link
	* @param string $description
	* @param string $url
	* @param string $content
	*/
	public static function generaterss($host,$title,$link,$description,$url,$content) {
  
		$writer = xmlwriter_open_memory();
		xmlwriter_set_indent( $writer, 4 );
		xmlwriter_start_document( $writer , '1.0', 'utf-8');
  
		xmlwriter_start_element( $writer, 'rss' );
		xmlwriter_write_attribute( $writer,'version','2.0');
		xmlwriter_write_attribute( $writer,'xml:base',$host);
		xmlwriter_write_attribute( $writer,'xmlns:atom','http://www.w3.org/2005/Atom');
		xmlwriter_start_element( $writer, 'channel');
  
		xmlwriter_write_element($writer,'title',$title);
		xmlwriter_write_element($writer,'language','en-us');
		xmlwriter_write_element($writer,'link',$link);
		xmlwriter_write_element($writer,'description',$description);
		xmlwriter_write_element($writer,'pubDate',date('r'));
		xmlwriter_write_element($writer,'lastBuildDate',date('r'));
  
		xmlwriter_start_element( $writer, 'atom:link' );
		xmlwriter_write_attribute( $writer,'href',$url);
		xmlwriter_write_attribute( $writer,'rel','self');
		xmlwriter_write_attribute( $writer,'type','application/rss+xml');
		xmlwriter_end_element( $writer );
  
		// items
		for($i=0;$i<count($content);$i++) {
			xmlwriter_start_element( $writer, 'item');
			if (isset($content[$i]['TITLE'])){
				xmlwriter_write_element($writer,'title',$content[$i]['TITLE']);
			}
  
			if (isset($content[$i]['LINK']))     xmlwriter_write_element($writer,'link',$content[$i]['LINK']);
			if (isset($content[$i]['LINK']))     xmlwriter_write_element($writer,'guid',$content[$i]['LINK']);
			if (isset($content[$i]['LINK']))     xmlwriter_write_element($writer,'comments',$content[$i]['LINK']);
			if (isset($content[$i]['DATE']))     xmlwriter_write_element($writer,'pubDate',$content[$i]['DATE']);
			if (isset($content[$i]['CATEGORY'])) xmlwriter_write_element($writer,'category',$content[$i]['CATEGORY']);
  
  
			if (isset($content[$i]['DESCRIPTION'])) {
				xmlwriter_start_element($writer,'description');
				xmlwriter_start_cdata($writer);
				xmlwriter_text($writer,$content[$i]['DESCRIPTION']);
				xmlwriter_end_cdata($writer);
				xmlwriter_end_element($writer);
			}
			xmlwriter_end_element( $writer );
		}
  
		xmlwriter_end_element( $writer );
		xmlwriter_end_element( $writer );
  
		xmlwriter_end_document( $writer );
		$entry=xmlwriter_output_memory( $writer );
		unset($writer);
		return($entry);
	}




}

