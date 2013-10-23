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
        var newName;
        var btnAdd;
        $(function () {
            newName = $("#newName");
            btnAdd = $("a.btnPlus");
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
            $("#newDate").datepicker();
            newName.keyup( function () {
                btnAdd.css("display", newName.val().length > 0 ? "inline-block" : "none")
            });
            newName.focus();
            $("div#scrollContainer").css("maxHeight", "10%").animate({
                maxHeight: "85%"
            }, 666, function() {
                // Animation complete.
            });
        });

        function createChar() {
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
<?php include('libs/navheader.php'); ?>
<div class="body">
    <div id="fixedHeader" class="fixedHeader">
        <table class="solid">
            <tr>
                <th class="clickable">Name</th>
                <th class="clickable">Venue</th>
                <th class="clickable">Date</th>
            </tr>
        </table>
    </div>
    <div id="scrollContainer" class="scrollable">
        <table id="tableChars">
            <tr>
                <th>Name</th>
                <th>Venue</th>
                <th>Date</th>
            </tr>
            <tfoot>
            <tr>
                <td><input id="_newName" placeholder="New name" disabled="disabled"></td>
                <td><input id="_newVenue" placeholder="New Venue" disabled="disabled"></td>
                <td id="_newDate"><input id="_newDate" placeholder="New Date" disabled="disabled"></td>
            </tr>
            </tfoot>
            <tbody class="sortable">
            <?php
            $conn = DbUtil::connect();
            $stmt = $conn->prepare("SELECT name, venue, date
                    FROM tournament
                    ORDER BY tournament_id, date, name");
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_BOTH);
            while ($row = $stmt->fetch()) {
                echo "<tr>";
                echo "<td>", $row["name"], "</td>";
                echo "<td>", $row["venue"], "</td>";
                echo "<td>", $row["date"], "</td>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
    <div id="fixedFooter" class="fixedFooter">
        <table class="layout">
            <tr class="layout">
                <td style="vertical-align: bottom;" class="layout">
                    <table class="content solid">
                        <tfoot>
                        <tr>
                            <td><input id="newName" placeholder="New name"></td>
                            <td><input id="newVenue" placeholder="New venue"></td>
                            <td><input id="newDate" placeholder="New date"></td>
                        </tr>
                        </tfoot>
                    </table>
                </td>
                <td class="layout" style="padding-left: 20px;">
                    <a href="javascript:void(0);" style="display: none;" class="btnPlus" onclick="createChar();"></a>
                    <!--                <input type="button" value="Create New&#x00A;Character" onclick="createChar();">-->
                </td>
            </tr>
        </table>

    </div>
</div>

<?php include('libs/regions.php'); ?>
</body>
</html>