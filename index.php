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
if ($stmt->prepare("show columns from `character` like 'universe'")){
    $stmt->execute();
    $stmt->bind_result($col1, $col2, $col3, $col4, $col5, $col6);
    while ($stmt->fetch()){
        echo $col2, "</br>";
    }
}
?>