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

// insert json input into technique_usage
//INSERT INTO `cs4750jcs5sb`.`technique_usage` (`technique_id`, `video_player_id`) VALUES ('1', '4'), ('1', '5');
if (strlen($json_input) > 0) {
    echo $json_input;
}

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
                Add Technique...
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
    <br>
    <a href='javascript:void(0);' class='btnPlus' id='submit'> </a>
</div>
</div>
<?php
//echo "<div id='div_urlParam' style='display: none;'>", $urlParams["t"], "</div>";
include('libs/techniques.php');
echo "<div id=\"div_players\" style=\"display: none;\">";
    $conn = DbUtil::connect();
    $stmt = $conn->prepare("select p.player_id as id, COALESCE(p.tag, p.name) as name from player AS p INNER JOIN video_player as v
         ON v.player_id = p.player_id WHERE video_id = :video_id order by name");
    $params = array("video_id"=>$urlParams["t"]);
    $stmt->execute($params);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    echo json_encode(clean($stmt->fetchAll()));
    $stmt->closeCursor();
    echo "</div><script type=\"text/javascript\">
    function createPlayerSelector(includeBlank) {
        var select_player = document.createElement(\"select\");
        var players = JSON.parse($(\"#div_players\").text());
        if (includeBlank) select_player.options[0] = new Option(\"Player\", -1);
        for (var i in players) {
            select_player.options[select_player.options.length] = new Option(players[i].name, players[i].id);
        }
        return select_player;
    }
    var player_id;
    var video_id;
    var vp;
    player_id = $(\"#newPlayer\").val()";
echo "video_id = " . $urlParams["t"] . ";" ;
echo "alert(player_id);
    alert(video_id);";
echo"</script>";
echo"<div id=\"div_vp\" style=\"display: none;\">";
            $conn = DbUtil::connect();
            $stmt = $conn->prepare("select video_player_id from video_player
                where video_player->video_id = :video_id and video_player->player_id = :player_id");
            $params = array("video_id"=>$urlParams["t"]);
            $stmt->execute($params);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            echo json_encode(clean($stmt->fetchAll()));
            $stmt->closeCursor();
echo "hello";
?>
    </div><script type="text/javascript">
    alert("hey");
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

//            $vp = JSON.parse($("#div_vp").text());
              alert('blah');
            $("#submit").click(function() {
            var newObj = {};
//            newObj.technique_id = $("#newTechnique").val();
//            newObj.video_player_id = vp;
            Helper.uploadObj(newObj);
        }
        }
            </script>

</body>
</html>