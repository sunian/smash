<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sun
 * Date: 10/12/13
 * Time: 3:28 PM
 * To change this template use File | Settings | File Templates.
 */
require_once('DbUtil.php');

$stmt = $conn->stmt_init();
if ($stmt->prepare("SELECT version_id,
                concat(abbreviation, coalesce(concat(' ', version_number),'')) AS name
                FROM version
                ORDER BY name")
) {
    $stmt->execute();
    $stmt->bind_result($version_id, $name);
    while ($stmt->fetch()) {
        echo "<p><input type='checkbox' value='", $version_id, "'>", $name, "</input></p>";
    }
}
$stmt->close();
?>
<!--<script type="text/javascript">-->
<!--</script>-->
