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
require_once('libs/Video.php');
require_once('libs/VideoListUnit.php');
//require_once('libs/Techniques.php');

//if (strlen($json_input) > 0) {
// $video = new Video($json_input);
// $error = $video->createIdentity();
// if ($error) echo $error;
// exit();
//}
if (!$urlParams["t"]) {
    header("Location: http://plato.cs.virginia.edu/~jcs5sb/smash/videos.php");
    exit();
}
$vidUnit = new VideoListUnit($urlParams["t"]);
$vid = $vidUnit->video;
$urlID = $vid->getIDFromURL();
?>

<html>
<head>
    <title>Video</title>
    <?php include('libs/headers.php');
    ?>
    <script type="text/javascript">
        var newTechnique;
        var selectTechnique;
        function init() {
            newTechnique = $("#newTechnique");
            selectTechnique = createTechniqueSelector();
            selectTechnique.id = "selectTechnique";
            newTechnique.appendChild(selectTechnique);
        }
    </script>
</head>
<body>

<?php

include('libs/navheader.php');

echo "<h1>$vid->title</h1>";

?>

<div class='body'>
    <table>
        <tr>
            <td style="width:425px; height:350px">
                <iframe name='video'
                        width="425" height="350"
                        src="http://www.youtube.com/embed/<?php echo $urlID; ?>?enablejsapi=1&playsinline=1&autoplay=1"
                        seamless>
                </iframe>
            </td>
            <td style="width:425px; height:350px">
                <h2>Information</h2><br>
                <?php $vidUnit->render(); ?>
            </td>
        </tr>
    </table>
    <br>

    <table id='newTechnique'>
        <tr>
            <td style="width:850px">
                Add Techniques here?
            </td>
        </tr>
    </table>

</div>
<?php
echo "<div id='div_urlParam' style='display: none;'>", $urlParams["t"], "</div>";
include('libs/techniques.php');
?>
</body>
</html>