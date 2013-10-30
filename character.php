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
require_once('libs/Character.php');
if (strlen($json_input) > 0) {
    $character = new Character($json_input);
    $error = $character->createIdentity();
    if ($error) echo $error;
    exit();
}
?>

<html>
<head>
    <title>Characters</title>
    <?php include('libs/headers.php'); ?>
    <script type="text/javascript">
        var select_universe;
        var newName;
        var btnAdd;
        $(function () {
            newName = $("#newName");
            btnAdd = $("a.btnPlus");
            select_universe = createUniverseSelector();
            select_universe.id = "_select_universe";
            select_universe.disabled = true;
            $("#_newChar")[0].appendChild(select_universe);
            select_universe = createUniverseSelector();
            select_universe.id = "select_universe";
            $("#newChar")[0].appendChild(select_universe);
            alignCellWidths($.makeArray($("table#tableChars tr th")),
                $.makeArray($("div#fixedHeader table tr th")));
            alignCellWidths($.makeArray($("table#tableChars tfoot tr td")),
                $.makeArray($("div#fixedFooter table.content tr td")));
            $("div#fixedHeader table tr th").each(function (i, elem) {
                $(elem).attr("dir", "1").css("cursor", "pointer");
                $(elem).click(function () {
                    var dir = $(elem).attr("dir") * 1;
                    sortTable($("table#tableChars tbody.sortable"), i, dir);
                    $(elem).attr("dir", "" + (dir * -1));
                })
            });
            newName.keyup( function () {
                btnAdd.css("display", newName.val().length > 0 ? "inline-block" : "none")
            });
            newName.focus();
            $("div#scrollContainer").css("maxHeight", "10%").animate({
                maxHeight: "85%"
            }, 666, function() {
                // Animation complete.
                $("div#fixedFooter table.content, div#fixedHeader table")
                    .css("width", $("table#tableChars").css("width"));

            });
        });

        function createChars() {
            var newChar = {};
            newChar.name = newName.val();
            newChar.nick = $("#newNick").val();
            if (newChar.name.length == 0) {
                alert("Please enter a name to create a new character.");
                newName.focus();
                return;
            }
            if (newChar.nick.length == 0) newChar.nick = undefined;
            newChar.universe = $(select_universe).val();
//            console.log(JSON.stringify(newChar));
            $.ajax({
                type: "POST",
                data: JSON.stringify(newChar),
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

$table = new DataTable("Chars", array(
    new TableColumn("Name", "newName", "input", "New name"),
    new TableColumn("Nickname", "newNick", "input", "New nickname"),
    new TableColumn("Universe", "newChar", "select", "")
));
$table->setData("SELECT i.name, i.nickname, u.name as universe
                    FROM character_identity AS i INNER JOIN universe AS u on i.universe_id = u.universe_id
                    ORDER BY u.universe_id, i.nickname, i.name", null);
$table->renderData = function ($row) {
    echo "<tr>";
    echo "<td>", $row["name"], "</td>";
    echo "<td>", $row["nickname"], "</td>";
    echo "<td>", $row["universe"], "</td>";
    echo "</tr>";
};
$table->render();

include('libs/universes.php');
?>

</body>
</html>