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
require_once('libs/VideoListUnit.php');
//require_once('libs/Techniques.php');

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
        var newTechnique;
        var selectTechnique;
        $(function () {
            newTechnique = $("#newTechnique");
            selectTechnique = createTechniqueSelector();
            selectTechnique.id = "selectTechnique";
            newTechnique.appendChild(selectTechnique);
        });
    </script>
</head>
<body text="white">

<?php include('libs/navheader.php');

    $vid = new Video();
    $vid->set(array("video_id"=>$urlParams["t"]));
    echo $vid->populateFieldsFromID();
    $urlID = $vid->getIDFromURL();
    $vidUnit = new VideoListUnit($vid->video_id);
    $outputString = $vidUnit->getVideoInformation();

    echo "<h1>", $vid->title , "</h1>";

include('libs/techniques.php');
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
            <?php $outputString; ?>
        </td>
    </tr>
</table>
    <br>

    <div id='newTechnique'>
        <table>
            <tr>
                <td style="background-color:black;width:850px">
                    Add Techniques here?
                </td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>