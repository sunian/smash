<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sun
 * Date: 10/14/13
 * Time: 12:57 PM
 * To change this template use File | Settings | File Templates.
 */
if(empty($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] !== "on")//force https
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
require_once('libs/User.php');

function clean($elem)
{
    if (is_array($elem))
        foreach ($elem as $key => $value) {
            $elem[$key] = clean($value);
        }
    elseif (is_object($elem))
        foreach ($elem as $key => $value) {
            $elem->{$key} = clean($value);
        }
    else
        $elem = htmlentities($elem, ENT_QUOTES, "UTF-8");
    return $elem;
}

$urlParams = clean($_GET);
$cookies = clean($_COOKIE);

if (stripos($_SERVER['HTTP_USER_AGENT'], "MSIE", 0) === false) {
//    echo "you're safe </br>";
} elseif (isset($cookies["msie"]) && $cookies["msie"] == "bypass") {
//    echo "you have been warned! </br>";
} else {
    header("Location: ie.php");
}

$authenticatedUser = null;

if (isset($cookies["user_name"]) && isset($cookies["user_token"])) {
    $authenticatedUser = User::nu($cookies["user_name"]);
    $authenticatedUser = $authenticatedUser->authenticateWithToken($cookies["user_token"]);
}

function startsWith($haystack, $needle)
{
    return $needle === "" || strpos($haystack, $needle) === 0;
}

function endsWith($haystack, $needle)
{
    return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
}