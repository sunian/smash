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
<body>

<embed
    width="420" height="340"
    src="http://www.youtube.com/watch?v=aEd5doQuG9c"
    type="application/x-shockwave-flash">
</embed>

<?php include('libs/navheader.php');

try {
    $conn = DbUtil::connect();
    $stmt = $conn->prepare("SELECT url FROM video");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
//    echo "<div class='body'>";
//    echo "<table id='most_recent_vids'>";
//    while($row = $stmt->fetch()) {
//        $listUnit = new VideoListUnit($row["video_id"]);
//        echo "<tr id='" , $row["video_id"] , "'>
//                <td>" , $listUnit->getThumbnail() , "</td>
//                <td>" , $listUnit->getVideoInformation() , "</td>
//              </tr>";
//    }
//    echo "</table>";
//    echo "</div>";
}
catch(PDOException $e) {
    echo $e->getMessage();
}

?>
</body>
</html>