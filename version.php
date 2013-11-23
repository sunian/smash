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

        $(function () {
            newName = $("#newName");
            newWeight = $("#newWeight");
            newHeight = $("#newHeight");
            newFallRank = $("#newFallRank");
            newAirRank = $("#newAirRank");

            newName.keyup( function () {
                Helper.displayBtnAdd(newName.val().length > 0 && newWeight.val().length > 0);
            });

            Helper.setupTables("Characters");

            newName.focus();
        });

        function createCharacters() {
            var newObj = {};
            newObj.name = newName.val();

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
        new TableColumn("Name", "newName", "dropDown", "New Name"),
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
        echo "<td>". $row["weight"] . "</td>";
        echo "<td>" . $row["height"] . "</td>";
        echo "<td>" . $row["falling_speed_rank"] . "</td>";
        echo "<td>". $row["air_speed_rank"] . "</td>";
        echo "</tr>";
    };
    $table->render();
}
?>
</body>
</html>

