<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sun
 * Date: 10/14/13
 * Time: 2:19 PM
 * To change this template use File | Settings | File Templates.
 */
require_once('libs/browser.php');
require_once('libs/DataTable.php');
//require_once('libs/Technique.php');
if (strlen($json_input) > 0) {
//    $technique = new Technique($json_input);
//    $error = $technique->createTechnique();
//    if ($error) echo $error;
    exit();
}
?>

<html>
<head>
    <title>Versions</title>
    <?php include('libs/headers.php'); ?>
    <script type="text/javascript">
        var newName, newDate;
        $(function () {
            newName = $("#newName");
            newDate = $("#newDate");

            Helper.setupTables("Versions");
            Helper.displayBtnAdd(true);

            newName.focus();
        });

        function createVersions() {
            document.location.href = "version.php?t=newVersion";
//            var newObj = {};
//            newObj.name = newName.val();
//            newObj.release_date = newDate.val();
//            if (newObj.name.length == 0) {
//                alert("Please enter a name to create a new version of Smash.");
//                newName.focus();
//                return;
//            }
////            console.log(JSON.stringify(newChar));
//            Helper.uploadObj(newObj);
        }
    </script>
</head>
<body>
<?php

include('libs/navheader.php');

$table = new DataTable("Versions", array(
    new TableColumn("Name", "newName", "none", ""),
    new TableColumn("Release Date", "newDate", "none", "")
));
$table->setData("SELECT version_id, release_date,
                concat(title, coalesce(concat(' ', version_number),'')) AS name
                FROM version
                ORDER BY name", null);
$table->renderData = function ($row) {
    $leDate = $row["release_date"];
    echo "<tr>";
    echo "<td><a href='version.php?t=", $row["version_id"], "'>", $row["name"], "</a></td>";
    echo "<td raw='$leDate'>", DataTable::prettyDate($leDate), "</td>";
    echo "</tr>";
};
$table->render();

?>
</body>
</html>