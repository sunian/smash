<?php
/**
 * Created by PhpStorm.
 * User: Arjun
 * Date: 11/22/13
 * Time: 3:15 PM
 */
require_once('libs/browser.php');
?>
<html>
<head>
    <h1>Test</h1>
    <?php
    include('libs/headers.php');
    $urlParams = $_CLEAN('GET');
    if(strlen($urlParams["t"])<1) {
        header("Location: http://plato.cs.virginia.edu/~jcs5sb/smash/versions.php");
        exit;
    }
    else {
        echo "<div id=\"div_urlParam\" style=\"display: none;>" , $urlParams["t"] , "</div>";
    }
    ?>
</head>
<body>
<?php include('libs/navheader.php');?>
</body>
</html>

