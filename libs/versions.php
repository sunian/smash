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
$stmt = $conn->stmt_init();
if ($stmt->prepare("SELECT version_id,
                concat(abbreviation, coalesce(concat(' ', version_number),'')) AS name
                FROM version
                ORDER BY name")
) {
    $stmt->execute();
    $stmt->bind_result($version_id, $name);
    while ($stmt->fetch()) {
        echo "<label><input type='checkbox' value='", $version_id, "'/>", $name, "</label></br>";
    }
}
$stmt->close();
if ($_GET['enclose'] != null){
    echo "enclose";
} else {
    echo "don't enclose";
}
?>
<!--<script type="text/javascript">-->
<!--</script>-->
