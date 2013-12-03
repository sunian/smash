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
//require_once('libs/Technique.php');

//if (strlen($json_input) > 0) {
//$video = Video::nu($urlParams["t"]);
//$technique = new Technique($json_input);
// $error = $technique->createTechnique();
// if ($error){
//     echo $error;
//     exit();
// }
//$conn = DbUtil::connect();
//$tech_id=$technique->technique_id;
//$v_id=$video->video_id;
//$vp_id1 = "select max(video_player_id) from video player where video_id=$v_id";
//$vp_id2 = "select min(video_player_id) from video player where video_id=$v_id";
//$stmt = $conn->prepare("INSERT INTO technique_usage VALUES($tech_id,$vp_id1),($tech_id,$vp_id2)");
//$params = array("technique_id"=>$technique->technique_id);
//if($technique->) $params["date"] = $version->release_date;
//if($version->version_number) $params["version_number"] = $version->version_number;
//if($version->abbreviation) $params["abbrev"] = $version->abbreviation;
//$stmt->execute($params);
//$row = $stmt->fetch(PDO::FETCH_ASSOC);
//exit();
//INSERT INTO `cs4750jcs5sb`.`technique_usage` (`technique_id`, `video_player_id`) VALUES ('1', '4'), ('1', '5');
//}

if (!$urlParams["t"]) {
    header("Location: https://plato.cs.virginia.edu/~jcs5sb/smash/videos.php");
    exit();
}
$video = Video::nu($urlParams["t"]);
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
            newTechnique.append(selectTechnique);

            newPlayer = $("#newPlayer");
            selectPlayer = createPlayerSelector();
            selectPlayer.id = "selectPlayer";
            newPlayer.append(selectPlayer);

            Helper.displayBtnAdd(true);
        }
        function addNewTechnique() {
            var newObj = {};
            newObj.technique_id = $("#newTechnique").val();
            newObj.player_id = $("#newPlayer").val();
            newObj.video_id = $video->video_id;
            Helper.uploadObj(newObj);
        }
    </script>
</head>
<body>

<?php

include('libs/navheader.php');

echo "<h1>$video->title</h1>";

?>

<div class='body'>
    <table>
        <tr>
            <td style="width:425px; height:350px">
                <iframe name='video'
                        width="425" height="350"
                        src="<?php echo $video->getEmbedURL(); ?>"
                        seamless>
                </iframe>
            </td>
            <td style="width:425px; height:350px">
                <h2>Information</h2><br>
                <?php $video->render(true); ?>
            </td>
        </tr>
    </table>
    <br>
<div class='body'>
    <table id='newTechnique'>
        <tr>
            <td style="width:350px">
                Add Technique
            </td>
        </tr>
    </table>
    <br>
    <table id='newPlayer'>
        <tr>
            <td style = "width:350px">
                To Player
            </td>
        </tr>
    </table>
    <a href='javascript:void(0);' class='btnPlus' onclick='addNewTechnique()'> </a>
</div>
</div>
<?php
//echo "<div id='div_urlParam' style='display: none;'>", $urlParams["t"], "</div>";
include('libs/techniques.php');
include('libs/players.php');
?>
</body>
</html>