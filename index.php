<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sun
 * Date: 10/12/13
 * Time: 3:28 PM
 * To change this template use File | Settings | File Templates.
 */
require_once('libs/browser.php');
require_once('libs/DbUtil.php');
require_once('libs/VideoListUnit.php');

?>

<html>
<head>
    <title>SMASH!</title>
    <?php include('libs/headers.php'); ?>
    <script type="text/javascript">
        $(function () {
//            var select_universe = createUniverseSelector();
//            select_universe.id = "select_universe";
//            document.body.appendChild(select_universe);
        });
    </script>
</head>
<body>
<?php include('libs/navheader.php');
$conn = DbUtil::connect();
$stmt = $conn->prepare("SELECT video_id FROM video ORDER BY date_added DESC");
$stmt->setFetchMode(PDO::FETCH_ASSOC);
$x = 0;
while($row = $stmt->fetch()) {
    $listUnit = new VideoListUnit($row["video_id"]);
    $listUnit->echoDisplayString();
}
?>
</body>
</html>