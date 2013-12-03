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
    if (!$urlParams["t"]) {
        header("Location: https://plato.cs.virginia.edu/~jcs5sb/smash/versions.php");
        exit;
    }
    ?>
    <title>
        <?php
        if (strcmp($urlParams["t"], "newVersion") == 0) {
            echo "New Version";
        }
        else {
            $conn = DbUtil::connect();
            $stmt = $conn->prepare("SELECT name FROM pretty_version WHERE version_id = :version_id");
            $params = array("version_id"=>$urlParams["t"]);
            $stmt->execute($params);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            echo $row["name"];
        }
        ?>
    </title>
    <script type="text/javascript">
        var versionId;

        var newName;
        var newWeight;
        var newHeight;
        var newFallRank;
        var newAirRank;

        var titleSelector;

        function init() {
            versionId = $("#video_id_div").text();
            if(versionId=="newVersion") {
                newName = $("#new_version_title");
                newWeight = $("#new_version_abbrev");
                newHeight = $("#new_version_release_date");
                newFallRank = $("new_version_number");
                newName.autocomplete({
                   source: getVersionTitles()
                });
                newName.keyup(function() {
                   newWeight.val(getAbbreviationForTitle(newName.val()));
                });
                $("#submit").click(function() {

                });
            }
            else {
                newWeight = $("#newWeight");
                newHeight = $("#newHeight");
                newFallRank = $("#newFallRank");
                newAirRank = $("#newAirRank");

                Helper.setupDataTable("Characters");
                Helper.displayBtnAdd(true);

                newName = $("#newName");
                newName.focus();
            }
        }

        function createCharacters() {
            var newObj = {};
            newObj.identity_id = newName.val();
            newObj.weight = newWeight.val();
            newObj.height = newHeight.val();
            newObj.falling_speed_rank = newFallRank.val();
            newObj.air_speed_rank = newAirRank.val();
            newObj.version_id = versionId;

            Helper.uploadObj(newObj);
        }
    </script>
</head>
<body>
<?php
include('libs/navheader.php');

if (strcmp($urlParams["t"], "newVersion") == 0) {
    echo "<h1>New Version Form</h1>";
    echo "<div id='version_form' class='body'>
        <div id='title_fields'><div style=\"display: inline-block\">Title: </div>
        <div style=\"display: inline-block\"><input id='new_version_title'></div></div>
        <div id='abbrev_fields'><div style=\"display: inline-block\">Abbreviation: </div>
        <div style=\"display: inline-block\"><input id='new_version_abbrev'></div></div>
        <div id='title_fields'><div style=\"display: inline-block\">Version Number: </div>
        <div style=\"display: inline-block\"><input id='new_version_number'></div></div>
        <div id='title_fields'><div style=\"display: inline-block\">Release Date: </div>
        <div style=\"display: inline-block\"><input id='new_version_release_date' class='date'></div></div>
        </div>";
    echo "<button id='submit'>Create Version</button>";
}
else {
    $version = new Version();
    $version->set(array("version_id" => $urlParams["t"]));
    $version->populateFieldsFromID();
    echo "<h1>", $version->pretty_name, "</h1>";

    $table = new DataTable("Characters", array(
        new TableColumn("Name", "newName", "select", "createCharacterSelector"),
        new TableColumn("Weight", "newWeight", "input", "New Weight"),
        new TableColumn("Height", "newHeight", "input", "New Height"),
        new TableColumn("Falling Speed Rank", "newFallRank", "input", "Fall Speed Rank"),
        new TableColumn("Air Speed Rank", "newAirRank", "input", "Air Speed Rank")
    ));
    $table->setData("SELECT ci.name, c.weight, c.height, c.falling_speed_rank, c.air_speed_rank
        FROM `character` AS c INNER JOIN character_identity AS ci on c.identity_id = ci.identity_id
        WHERE c.version_id = :version",
        array("version" => $version->version_id));
    $table->renderData = function ($row) {
        echo "<tr>";
        echo "<td>" . $row["name"] . "</td>";
        echo "<td type='#'>" . $row["weight"] . "</td>";
        echo "<td type='#'>" . $row["height"] . "</td>";
        echo "<td type='#'>" . $row["falling_speed_rank"] . "</td>";
        echo "<td type='#'>" . $row["air_speed_rank"] . "</td>";
        echo "</tr>";
    };
    $table->render();
}
include('libs/characters.php');
include('libs/versions.php');
echo "<div id=\"video_id_div\" style=\"display: none;\">" . $urlParams["t"] . "</div>";
?>
</body>
</html>

