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
if ($stmt->prepare("select name from universe")){
    $stmt->execute();
    $stmt->bind_result($col1);
    while ($stmt->fetch()){
        $csv = str_getcsv(substr($col1, 5, -1), ',', "'");
        print_r($csv);
    }
}
?>