<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sun
 * Date: 10/12/13
 * Time: 3:28 PM
 * To change this template use File | Settings | File Templates.
 */
require_once('DbUtil.php');

$conn = DbUtil::connect();
$stmt = $conn->prepare("SELECT version_id as id,
                concat(abbreviation, coalesce(concat(' ', version_number),'')) AS name
                FROM version
                ORDER BY name");
$stmt->execute();
while ($row = $stmt->fetch()) {
    echo "<label><input type='checkbox' value='", $row["id"], "'/>", $row["name"], "</label></br>";
}
$stmt->closeCursor();
if ($_GET['enclose'] != null) {
//    echo "enclose";
} else {
//    echo "don't enclose";
}
?>
<!--<script type="text/javascript">-->
<!--</script>-->
