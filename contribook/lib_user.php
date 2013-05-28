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
		
		// fetch users from the DB status: 0-> not approved, 1-> approved, 2->admin
		$request=CONTRIBOOK_DB::query('select userid from users where status="1" or status="2"');
		$num=CONTRIBOOK_DB::numrows($request);
		
		$users=array();
		for($i = 0; $i < $num; $i++) {
			$user=CONTRIBOOK_DB::fetch_assoc($request);
			$users[]=$user['userid'];

		}
		CONTRIBOOK_DB::free_result($request);
		return($users);
	}


	/**
	 * get the data of one user
	 *
	 * @param string $userid
	 * @return full data array of a user
	 */
	static function getuser($userid){
		
		$request=CONTRIBOOK_DB::query('select * from users where userid="'.addslashes($userid).'" ');
		$num=CONTRIBOOK_DB::numrows($request);

		if($num<>1) return(array());
		$user=CONTRIBOOK_DB::fetch_assoc($request);

		CONTRIBOOK_DB::free_result($request);
		return($user);
	}

        /**
         * check if a user exists
         *
         * @param string $userid
         * @return bool
         */
        static function exist($userid){

                $request=CONTRIBOOK_DB::query('select * from users where userid="'.addslashes($userid).'" ');
                $num=CONTRIBOOK_DB::numrows($request);

                if($num==1) {
			return(true);
		}else{
			return(false);
		}
        }




}
