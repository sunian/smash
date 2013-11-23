<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Student
 * Date: 10/18/13
 * Time: 2:53 AM
 * To change this template use File | Settings | File Templates.
 */
require_once('libs/browser.php');
require_once('libs/DbUtil.php');
require_once('libs/Videos.php');
//if (strlen($json_input) > 0) {
   // $video = new Video($json_input);
   // $error = $video->createIdentity();
   // if ($error) echo $error;
   // exit();
//}
?>

<html>
<head>
    <title>Video</title>
    <?php include('libs/headers.php');
    if(!$urlParams["t"]) {
        header("Location: http://plato.cs.virginia.edu/~jcs5sb/smash/videos.php");
        exit;
    }
    else {
        echo "<div id=\"div_urlParam\" style=\"display: none;\">" , $urlParams["t"] , "</div>";
    }
    ?>
    <script type="text/javascript">

    </script>
</head>
<div class='body'>
<body text="white">

<?php include('libs/navheader.php');
    if(strcmp($urlParams["t"], "0")==0) {

    }

    else {
    $vid = new Video();
    $vid->set(array("video_id"=>$urlParams["t"]));
    echo $vid->populateFieldsFromID();
    echo "<h1>, $vid->title , </h1>";
    }
?>
<script>
function setURL() {
    var iframe = document.getElementById("myframe");
    var str1 = "http://www.youtube.com/embed/";
    var str2 = vid->getIDFromURL();
    var str3 = "?enable?enablejsapi=1&playsinline=1&autoplay=1";
    var str = str1.concat(str2,str3);
    iframe.src = str;
}
</script>

<iframe id="myframe"
        width="420" height="345"
        src="http://www.youtube.com/embed/aEd5doQuG9c?enablejsapi=1&playsinline=1&autoplay=1"
        seamless
        >
</iframe>

</div>
</body>
</html>