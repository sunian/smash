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
require_once('libs/Video.php');
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

try {
    $conn = DbUtil::connect();
    $stmt = $conn->prepare("SELECT video_id FROM video ORDER BY date_added DESC LIMIT 0, 10");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    echo "<div class='body'>";
    echo "<table id='most_recent_vids'>";
    while($row = clean($stmt->fetch())) {
        echo "vid=", $row["video_id"], "\n";
        $listUnit = Video::nu($row["video_id"]);
        echo "now populate\n";
        $listUnit->populateFieldsFromID();
        echo "populated\n";
        echo "<tr id='" , $row["video_id"] , "'>
                <td><a href='video.php?t=", $row["video_id"], "'>", $listUnit->renderThumbnail() , "</a></td>
                <td>" , $listUnit->render() , "</td>
              </tr>";
    }
    echo "</table>";
    echo "</div>";
}
catch(PDOException $e) {
    echo $e->getMessage();
}

?>
</body>
</html>