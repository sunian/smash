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
require_once('libs/Technique.php');
if (strlen($json_input) > 0) {
    $technique = new Technique($json_input);
    $error = $technique->createTechnique();
    if ($error) echo $error;
    exit();
}
?>

<html>
<head>
    <title>Techniques</title>
    <?php include('libs/headers.php'); ?>
    <script type="text/javascript">
        var newName;
        $(function () {
            newName = $("#newName");
            newName.keyup(function () {
                Helper.displayBtnAdd(newName.val().length > 0);
            });

            Helper.setupTables("Techs");

            newName.focus();
        });

        function createTechs() {
            var newObj = {};
            newObj.name = newName.val();
            newObj.abbrev = $("#newAbbrev").val();
            if (newObj.name.length == 0) {
                alert("Please enter a name to create a new technique.");
                newName.focus();
                return;
            }
            if (newObj.abbrev.length == 0) newObj.abbrev = undefined;
//            console.log(JSON.stringify(newChar));
            Helper.uploadObj(newObj);
        }
    </script>
</head>
<body>
<?php

include('libs/navheader.php');

$table = new DataTable("Techs", array(
    new TableColumn("Name", "newName", "input", "New name"),
    new TableColumn("Abbreviation", "newAbbrev", "input", "New abbrev")
));
$table->setData("SELECT t.name, t.abbreviation, t.technique_id
                    FROM technique AS t
                    ORDER BY t.name, t.abbreviation", null);
$table->renderData = function ($row) {
    echo "<tr>";
    echo "<td><a href='techniques.php?t=", $row["technique_id"], "'>", $row["name"], "</a></td>";
    echo "<td>", $row["abbreviation"], "</td>";
    echo "</tr>";
};
$table->render();

?>
</body>
</html>