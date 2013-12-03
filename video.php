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

if (strlen($json_input) > 0) {
    try {
        $input = json_decode($json_input, true);
        $conn = DbUtil::connect();
        $stmt = $conn->prepare("SELECT video_player_id FROM video_player WHERE video_id = :video_id AND player_id = :player_id");
        $params = array("player_id"=>$input["player_id"], "video_id"=>$input["video_id"]);
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $vp_id = $row["video_player_id"];

        $stmt = $conn->prepare("INSERT INTO technique_usage(video_player_id, technique_id) VALUES (:vp_id, :tech_id)");
        $params = array("vp_id"=>$vp_id, "tech_id"=>$input["technique_id"]);
        $stmt->execute($params);
    }
    catch(PDOException $e) {
        echo $e->getMessage();
    }
    exit();
}

if (!$urlParams["t"]) {
    header("Location: https://plato.cs.virginia.edu/~jcs5sb/smash/videos.php");
    exit();
}
?>

<html>
<head>
    <title>Video</title>
    <?php include('libs/headers.php');
    ?>

    <script type="text/javascript">
        function init() {
            var newTechnique = $("#newTechnique");
            var selectTechnique = createTechniqueSelector();
            selectTechnique.id = "selectTechnique";
            newTechnique.append(selectTechnique);

            var newPlayer = $("#newPlayer");
            var selectPlayer = createPlayerSelector();
            selectPlayer.id = "selectPlayer";
            newPlayer.append(selectPlayer);

            $("#submit").click(function() {
                var newObj = {};
                newObj.technique_id = newTechnique.val();
                newObj.player_id = newPlayer.val();
                newObj.video_id = $("#video_id_div").text();
                Helper.uploadObj(newObj);
            });
        }
    </script>
</head>
<body>

<?php

include('libs/navheader.php');

$video = Video::nu($urlParams["t"]);
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
echo "</div>
    <script type=\"text/javascript\">
    function createPlayerSelector(includeBlank) {
        var select_player = document.createElement(\"select\");
        var players = JSON.parse($(\"#div_players\").text());
        if (includeBlank) select_player.options[0] = new Option(\"Player\", -1);
        for (var i in players) {
            select_player.options[select_player.options.length] = new Option(players[i].name, players[i].id);
        }
        return select_player;
    }</script>";
echo "<div id=\"video_id_div\" style=\"display: none;\">" . $urlParams["t"] . "</div>";
?>
</body>
</html>