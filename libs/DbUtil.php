<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sun
 * Date: 10/12/13
 * Time: 1:50 PM
 * To change this template use File | Settings | File Templates.
 */

class DbUtil
{
    public static $host = "stardock.cs.virginia.edu";
    public static $user = "cs4750jcs5sb";
    public static $pass = "starkid";
    public static $database = "cs4750jcs5sb";
    public static $connection = null;

    public static function connect()
    {
        $conn = DbUtil::$connection;
        if (!is_resource($conn)) {
            echo "new ";
            $conn = new mysqli(DbUtil::$host, DbUtil::$user, DbUtil::$pass, DbUtil::$database);
            if ($conn->connect_errno) {
                echo "db connection failed";
                $conn->close();
                exit();
            }
            DbUtil::$connection = $conn;
        } else {
            echo "old ";
        }
        return $conn;
    }

}
