<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sun
 * Date: 10/18/13
 * Time: 2:53 AM
 * To change this template use File | Settings | File Templates.
 */
require_once('libs/browser.php');
require_once('libs/Character.php');
if (strlen($json_input) > 0) {
    exit();
}
?>

<html>
<head>
    <title>Tournaments</title>
    <?php include('libs/headers.php'); ?>
    <script type="text/javascript">
        var select_universe;
        $(function () {
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
            $("#newName").focus();
        });

        function createChar() {
            var newChar = {};
            newChar.name = $("#newName").val();
            newChar.nick = $("#newNick").val();
            if (newChar.name.length == 0) {
                alert("Please enter a name to create a new character.");
                $("#newName").focus();
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
<?php include('libs/navheader.php'); ?>

<?php include('libs/regions.php'); ?>
</body>
</html>