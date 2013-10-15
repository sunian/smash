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
    private static $connection = null;

    public static function connect()
    {
        $conn = DbUtil::$connection;
        if (!is_object($conn)) {
            try {
                $conn = new PDO("mysql:host=" . DbUtil::$host . ";dbname=" . DbUtil::$database,
                    DbUtil::$user, DbUtil::$pass,
                    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
                );
            } catch (PDOException $e){
                echo "db connection failed";
                exit();
            }
//            $conn = new mysqli(DbUtil::$host, DbUtil::$user, DbUtil::$pass, DbUtil::$database);
            DbUtil::$connection = $conn;
        }
        return $conn;
    }

    public static function disconnect()
    {
//        $conn = DbUtil::$connection;
        DbUtil::$connection = null;
//        if (is_object($conn)) {
//            $conn->close();
//        }
    }

}
