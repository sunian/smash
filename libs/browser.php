<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sun
 * Date: 10/14/13
 * Time: 12:57 PM
 * To change this template use File | Settings | File Templates.
 */
if (stripos($_SERVER['HTTP_USER_AGENT'], "MSIE", 0) === false) {
//    echo "you're safe </br>";
} elseif (isset($_COOKIE["msie"]) && $_COOKIE["msie"] == "bypass") {
//    echo "you have been warned! </br>";
} else {
    header("Location: ie.php");
}
function clean($elem)
{
    if(!is_array($elem) && !is_object($elem))
        $elem = htmlentities($elem,ENT_QUOTES,"UTF-8");
    else
        foreach ($elem as $key => $value) {
            $elem[$key] = clean($value);
            echo $key, "=>", $value, "\n";
        }
    return $elem;
}

$urlParams = clean($_GET);

function startsWith($haystack, $needle)
{
    return $needle === "" || strpos($haystack, $needle) === 0;
}
function endsWith($haystack, $needle)
{
    return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
}