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
        var btnAdd;
        $(function () {
            newName = $("#newName");
            btnAdd = $("a.btnPlus");
            alignCellWidths($.makeArray($("table#tableTechs tr th")),
                $.makeArray($("div#fixedHeader table tr th")));
            alignCellWidths($.makeArray($("table#tableTechs tfoot tr td")),
                $.makeArray($("div#fixedFooter table.content tr td")));
            $("div#fixedHeader table tr th").each(function (i, elem) {
                $(elem).attr("dir", "1").css("cursor", "pointer");
                $(elem).click(function () {
                    var dir = $(elem).attr("dir") * 1;
                    sortTable($("table#tableTechs tbody.sortable"), i, dir);
                    $(elem).attr("dir", "" + (dir * -1));
                })
            });
            newName.keyup(function () {
                btnAdd.css("display", newName.val().length > 0 ? "inline-block" : "none")
            });
            newName.focus();
            $("div#scrollContainer").css("maxHeight", "10%").animate({
                maxHeight: "85%"
            }, 666, function () {
                // Animation complete.
//                $("div#fixedFooter table.content, div#fixedHeader table")
//                    .css("width", $("table#tableTechs").css("width"));
            });
        });

        function createTechs() {
            var newTech = {};
            newTech.name = newName.val();
            newTech.abbrev = $("#newAbbrev").val();
            if (newTech.name.length == 0) {
                alert("Please enter a name to create a new technique.");
                newName.focus();
                return;
            }
            if (newTech.abbrev.length == 0) newTech.abbrev = undefined;
//            console.log(JSON.stringify(newChar));
            $.ajax({
                type: "POST",
                data: JSON.stringify(newTech),
//                dataType: "json",
                success: function (data, textStatus, jqXHR) {
                    console.log(data);
                    if (data.length > 0) {
                        alert(data);
                    } else {
                        location.reload();
                    }
                }

            });
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