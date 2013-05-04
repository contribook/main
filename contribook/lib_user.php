<?php

/**
* CONTRIBOOK User Lib
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

// include the contributors files. This will be replaced by a database backend in the future.
require('contributors.php');


/**
 * class to manage user/contributors
 *
 */
class CONTRIBOOK_USER {


	/**
	 * get a complete list of all users
	 *
	 * @return users as array
	 */
	static function getusers(){
		global $CONTRIBOOK_contributors;
		$users=array();
		foreach($CONTRIBOOK_contributors as $key=>$value){
			$users[]=$key;
		}
		return($users);
	}


	/**
	 * executes a query on the database
	 *
	 * @param string $userid
	 * @return full data array of a user
	 */
	static function getuser($userid){
		global $CONTRIBOOK_contributors;
		return($CONTRIBOOK_contributors[$userid]);
	}




}