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
    if (strcmp($urlParams["t"], "newVersion") != 0) {
        $character = new Character($json_input);
        $error = $character->createCharacter();
        if ($error) echo $error;
        exit();
    }
    else {
        $version = new Version($json_input);
        $error = $version->createIdentity();
        if($error) {
            echo $error;
            exit();
        }
        $conn = DbUtil::connect();
        $stmt = $conn->prepare("SELECT version_id FROM version WHERE title = :title" . ($version->abbreviation?" AND
            abbreviation = :abbrev":"") . ($version->release_date?" AND release_date = :date":"") . ($version->version_number?" AND
            version_number = :version_number":""));
        $params = array("title"=>$version->title);
        if($version->release_date) $params["date"] = $version->release_date;
        if($version->version_number) $params["version_number"] = $version->version_number;
        if($version->abbreviation) $params["abbrev"] = $version->abbreviation;
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $id = $row["version_id"];
        echo "YAYhttps://plato.cs.virginia.edu/~jcs5sb/smash/version.php?t=" , $id;
        exit();
    }
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
                newFallRank = $("#new_version_number");
                newName.autocomplete({
                    source: getVersionTitles(),
                    select: function( event, ui ) {
                        newWeight.val(getAbbreviationForTitle(ui.item.value));
                    }
                });
                $("#submit").click(function() {
                    var newObj = {};
                    if(!newName.val()) {
                        alert("You must enter a version title!");
                        newName.focus();
                        return;
                    }
                    newObj.title = newName.val();

                    if(newHeight.val()) {
                        newObj.date = Date.parse(newHeight.val());
                        if(!newObj.date) {
                            alert("Incorrect format for release date!");
                            newHeight.focus();
                            return;
                        }
                        newObj.date = newObj.date.toString("yyyy-MM-dd");
                    }

                    if(newWeight.val()) {
                        newObj.abbreviation = newWeight.val();
                    }
                    if(newFallRank.val()) {
                        newObj.version_number = newFallRank.val();
                    }
                    Helper.postJSON(newObj, "n",
                        function (data, textStatus, jqXHR) {
                            if(data.charAt(0)=='Y' && data.charAt(1)=='A' && data.charAt(2)=='Y') {
                                window.location.href = data.substring(3);
                            }
                            else {
                                alert(data);
                            }
                        });
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
    echo "<a class='btnPlus' href='javascript:void' id='submit'></a>";
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

