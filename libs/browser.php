<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sun
 * Date: 10/14/13
 * Time: 12:57 PM
 * To change this template use File | Settings | File Templates.
 */
if (stripos($_SERVER['HTTP_USER_AGENT'], "MSIE", 0) === false) {
    echo "you're safe </br>";
} else {
    echo "you're using IE </br>";
}
