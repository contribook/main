<?php

/**
* CONTRIBOOK DB Lib
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
* The contribook database access class
*/
class CONTRIBOOK_DB {


    /**
     * executes a query on the database
     *
     * @param string $cmd
     * @return result-set
     */
    public static function query($cmd) {
        global $CONTRIBOOK_Connection;

        if(!isset($CONTRIBOOK_Connection)) {
            $CONTRIBOOK_Connection = @new mysqli(CONTRIBOOK_DB_HOST, CONTRIBOOK_DB_LOGIN, CONTRIBOOK_DB_PASSWD, CONTRIBOOK_DB_NAME, CONTRIBOOK_DB_PORT, CONTRIBOOK_DB_SOCKET);
            if (mysqli_connect_errno()) {
                @ob_end_clean();
                echo('Can not connect to the database. Please check your configuration.');
                exit();
            }
        }
        $result = @$CONTRIBOOK_Connection->query($cmd);
        if (!$result) {
            $entry='DB Error: "'.$CONTRIBOOK_Connection->error.'"<br />';
            $entry.='Offending command was: '.$cmd.'<br />';
            echo($entry);
        }
        return $result;
    }


    /**
     * closing a db connection
     *
     * @return bool
     */
    public static function close() {
        global $CONTRIBOOK_Connection;
        if(isset($CONTRIBOOK_Connection)) {
            return $CONTRIBOOK_Connection->close();
        } else {
            return(false);
        }
    }


    /**
     * Returning number of rows in a result
     *
     * @param resultset $result
     * @return int
     */
    public static function numrows($result) {
        if(!isset($result) or ($result == false)) return 0;
        $num= mysqli_num_rows($result);
        return($num);
    }


    /**
    * Returning primarykey if last statement was an insert.
    *
    * @return primarykey
    */
    static function insertid() {
        global $CONTRIBOOK_Connection;
        return(mysqli_insert_id($CONTRIBOOK_Connection));
    }


    /**
    * Returning number of affected rows
    *
    * @return int
    */
    static function affected_rows() {
        global $CONTRIBOOK_Connection;
        if(!isset($CONTRIBOOK_Connection) or ($CONTRIBOOK_Connection==false)) return 0;
        $num= mysqli_affected_rows($CONTRIBOOK_Connection_Connection);
        return($num);
    }


    /**
    * get data-array from resultset
     *
     * @param resultset $result
     * @return data
     */
    public static function fetch_assoc($result) {
        return mysqli_fetch_assoc($result);
    }


    /**
     * Freeing resultset (performance)
     *
     * @param unknown_type $result
     * @return bool
     */
    public static function free_result($result) {
        return @mysqli_free_result($result);
    }



}