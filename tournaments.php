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
                <td id="_newDate"></td>
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
                            <td id="newDate"></td>
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