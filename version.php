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
require_once('libs/DataTable.php');
if (strlen($json_input) > 0) {
    $character = new Character($json_input);
    $error = $character->createCharacter();
    if ($error) echo $error;
    exit();
}
?>
<html>
<head>
    <?php
    include('libs/headers.php');
    if(!$urlParams["t"]) {
        header("Location: http://plato.cs.virginia.edu/~jcs5sb/smash/versions.php");
        exit;
    }
    ?>
    <script type="text/javascript">
        var newName;
        var newWeight;
        var newHeight;
        var newFallRank;
        var newAirRank;

        var selectCharacter;

        $(function () {
            newWeight = $("#newWeight");
            newHeight = $("#newHeight");
            newFallRank = $("#newFallRank");
            newAirRank = $("#newAirRank");

            Helper.setupDataTable("Characters");
            Helper.displayBtnAdd(true);

            newName = $("#newName");
            newName.focus();
        });

        function createCharacters() {
            var newObj = {};
            newObj.identity_id = newName.val();
            newObj.weight = newWeight.val();
            newObj.height = newHeight.val();
            newObj.falling_speed_rank = newFallRank.val();
            newObj.air_speed_rank = newAirRank.val();
            newObj.version_id = $("#video_id_div").text();

            Helper.uploadObj(newObj);
        }
    </script>
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

    $table = new DataTable("Characters", array(
        new TableColumn("Name", "newName", "select", "createCharacterSelector"),
        new TableColumn("Weight", "newWeight", "input", "New Weight"),
        new TableColumn("Height", "newHeight", "input", "New Height"),
        new TableColumn("Falling Speed Rank", "newFallRank", "input", "Fall Speed Rank"),
        new TableColumn("Air Speed Rank", "newAirRank", "input", "Air Speed Rank")
    ));
    $table->setData("SELECT name, weight, height, falling_speed_rank, air_speed_rank
        FROM `character` NATURAL JOIN character_identity WHERE version_id = " . $version->version_id, null);
    $table->renderData = function ($row) {
        $leDate = $row["release_date"];
        echo "<tr>";
        echo "<td>" . $row["name"] . "</td>";
        echo "<td type='#'>". $row["weight"] . "</td>";
        echo "<td type='#'>" . $row["height"] . "</td>";
        echo "<td type='#'>" . $row["falling_speed_rank"] . "</td>";
        echo "<td type='#'>". $row["air_speed_rank"] . "</td>";
        echo "</tr>";
    };
    $table->render();

    include('libs/characters.php');
    echo "<div id=\"video_id_div\" style=\"display: none;\">" . $version->version_id . "</div>";
}
?>
</body>
</html>

