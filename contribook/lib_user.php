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
		$stmt=CONTRIBOOK_DB::prepare('select userid from users where status=:status1 or status=:status2');
		$s1=1; $stmt->bindParam(':status1', $s1, PDO::PARAM_STR);
		$s2=2; $stmt->bindParam(':status2', $s2, PDO::PARAM_STR);
		$stmt->execute();
		$num=$stmt->rowCount();
		
		$users=array();
		for($i = 0; $i < $num; $i++) {
			$user=$stmt->fetch(PDO::FETCH_ASSOC);
			$users[]=$user['userid'];

		}
		return($users);
	}


	/**
	 * get the data of one user
	 *
	 * @param string $userid
	 * @return full data array of a user
	 */
	static function getuser($userid){
		
		$stmt=CONTRIBOOK_DB::prepare('select * from users where userid=:userid');
		$stmt->bindParam(':userid', $userid, PDO::PARAM_STR);
		$stmt->execute();
		$num=$stmt->rowCount();

		if($num<>1) return(array());
		$user=$stmt->fetch(PDO::FETCH_ASSOC);
		return($user);
	}

	/**
	 * check if a user exists
	 *
	 * @param string $userid
	 * @return bool
	 */
	static function exist($userid){

		$stmt=CONTRIBOOK_DB::prepare('select * from users where userid=:userid');
                $stmt->bindParam(':userid', $userid, PDO::PARAM_STR);
                $stmt->execute();
		$num=$stmt->rowCount();

		if($num==1) {
			return(true);
		}else{
			return(false);
		}
	}




}
