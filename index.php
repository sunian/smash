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
require_once('libs/DataTable.php');
?>

<html>
<head>
    <title>SMASH!</title>
    <?php include('libs/headers.php'); ?>
    <script type="text/javascript">
        function init() {

        }
    </script>
</head>
<body>

<?php include('libs/navheader.php');

try {
    $conn = DbUtil::connect();
    $stmt = $conn->prepare("SELECT t.name, t.date, t.venue, r.name AS rName FROM tournament AS t INNER JOIN region as r on t.region_id
        = r.region_id ORDER BY t.date DESC LIMIT 0,3");
    echo "<div class='sideBlock'><table><tr><th>Recent Tournaments</th></tr><tr><td class='sideBlock'>";
    $stmt->execute();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<b>" , $row["name"] , "</b><br>&nbsp&nbsp&nbsp&nbspDate: " , DataTable::prettyDate($row["date"]) , "<br>&nbsp&nbsp&nbsp&nbspVenue: "
        , $row["venue"] , "<br>&nbsp&nbsp&nbsp&nbspRegion: " , $row["rName"], "&nbsp&nbsp&nbsp&nbsp<br>";
    }
    echo "</td></tr></table></div>";
}
catch(PDOException $e) {
    echo $e->getMessage();
}


try {
    $conn = DbUtil::connect();
    $stmt = $conn->prepare("SELECT video_id FROM video ORDER BY date_added DESC LIMIT 0, 10");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    echo "<div class='body'>";
    echo "<table id='most_recent_vids'>";
    while($row = clean($stmt->fetch())) {
        $listUnit = Video::nu($row["video_id"]);
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