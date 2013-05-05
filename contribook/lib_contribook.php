<?php

/**
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
* You should have received a copy of the GNU Affero General Public
* License along with this library.  If not, see <http://www.gnu.org/licenses/>.
*
*/

// include all the required libraries, 3rd party libraries and configuration files
require('3rdparty/simplepie/autoloader.php');
require('config.php');
require('lib_db.php');
require('lib_user.php');
require('lib_profile.php');
require('lib_blog.php');
require('lib_microblog.php');
require('lib_forum.php');
require('lib_news.php');
require('lib_ocs.php');
require('lib_github.php');

/**
 * The main class of contribook.
 */
class CONTRIBOOK {

	/**
	 * Show a template
	 *
	 * This output the html code of a template together with the content that is defined in $data
	 * @param template $template
	 * @param data array $ data
	 */
	public static function showtemplate($template,$data) {

		//include template
		$_=$data;
		$result=include(CONTRIBOOK_TEMPLATES_PATH.$template.'.php');
	
		// show an error if template not found
		if(!$result){
			echo('template "'.CONTRIBOOK_TEMPLATES_PATH.$template.'.php'.'" not found.');
		}
		
		
	}



}