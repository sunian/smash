<?php
/**
 * Created by PhpStorm.
 * User: Arjun
 * Date: 11/22/13
 * Time: 3:15 PM
 */
require_once('libs/browser.php');
require_once('libs/Version.php');
require_once('libs/Character.php');
?>
<html>
<head>
    <?php
    include('libs/headers.php');
    if(!$urlParams["t"]) {
        header("Location: http://plato.cs.virginia.edu/~jcs5sb/smash/versions.php");
        exit;
    }
//    else {
//        echo "<div id=\"div_urlParam\" style=\"display: none;\">" , $urlParams["t"] , "</div>";
//    }
    ?>
</head>
<body>
<?php
include('libs/navheader.php');

if(strcmp($urlParams["t"], "newVersion")==0) {

}
else {
    $version = new Version();
    $version->set(array("version_id"=>$urlParams["t"]));
    $version->populateFieldsFromID();
    echo "<h1>" , $version->pretty_name , "</h1>";
}
?>
</body>
</html>

