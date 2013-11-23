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
<body text="white">

<?php include('libs/navheader.php');

    $vid = new Video();
    $vid->set(array("video_id"=>$urlParams["t"]));
    echo $vid->populateFieldsFromID();
    $urlID = $vid->getIDFromURL();
    $players = $vid->players;
    for($i=0; $i<count($vid->players); $i++) {
    $output = $output . ", " . $vid->players[$i]->tag;
    }
    echo $output;
    $characters = $vid->characters;
    for($i=0; $i<count($vid->characters); $i++) {
    $output2 = $output2 . ", " . $vid->characters[$i]->tag;
    }
    echo $output2;
    $techniques = $vid->techniques;
    echo "<h1>", $vid->title , "</h1>";


?>
<div class='body'>
<table>
    <tr>
        <td style="background-color:black;width:425px;height=350">
            <iframe name='video'
                    width="425" height="350"
                    src="http://www.youtube.com/embed/<?php echo $urlID;?>?enablejsapi=1&playsinline=1&autoplay=1"
                    seamless>
            </iframe>
        </td>
        <td style="background-color:black;width:425px;height=350">
            <h2>Information</h2><br>
            Characters<br>
            Players<br>
            Techniques<br>
        </td>
    </tr>

</table>
</div>
</body>
</html>