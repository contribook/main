<?php

/**
* CONTRIBOOK Profile Lib
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
* class to show the user profile
*
*/
class CONTRIBOOK_PROFILE {


	/**
	* show the profile page of a user
	*
	* @param string $userid
	*/
	static function show($userid){
		$data=CONTRIBOOK_USER::getuser($userid);
		CONTRIBOOK::showtemplate('profile',$data);
	}

}
