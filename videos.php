<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sun
 * Date: 10/18/13
 * Time: 2:53 AM
 * To change this template use File | Settings | File Templates.
 */
require_once('libs/browser.php');
require_once('libs/DataTable.php');
require_once('libs/SearchBox.php');
require_once('libs/Video.php');
if (strlen($json_input) > 0) {
    if (strcmp($input_type, "q") == 0) { //user performed search
        $searchbox = new SearchBox($json_input);
        $searchbox->makeUseful();
        list($params, $sqlQueryOriginal) = Video::constructQuery($searchbox);
        $sqlQuery = $sqlQueryOriginal . " LIMIT 0,10";
        echo $sqlQuery, "\n";
        print_r($params);
        if ($sqlQuery) exit();
        $conn = DbUtil::connect();
        $stmt = $conn->prepare($sqlQuery);
        $stmt->execute($params);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        echo "<div class='body'>";
        echo "<table id='all_videos'>";
        $rowCount = 0;
        while ($row = clean($stmt->fetch())) {
            $rowCount++;
            $listUnit = Video::nu($row["video_id"]);
            echo "<tr id='", $row["video_id"], "'>
                <td><a href='video.php?t=", $row["video_id"], "'>", $listUnit->renderThumbnail(), "</a></td>
                <td>", $listUnit->render(), "</td>
              </tr>";
        }
        echo "</table>";
        if ($rowCount == 10) echo "<br><div class='spin' id='spinner'></div>";
        echo "</div>";
    } elseif (strcmp($input_type, "z") == 0) {
        $json_input = json_decode($json_input, true);
        $nextMax = $json_input["nextMax"];
        $query = $json_input["query"];
        $params = $json_input["params"];
        $params = json_decode($params, true);
        try {
            $conn = DbUtil::connect();
            $stmt = $conn->prepare($query . " LIMIT " . ($nextMax - 1) * 10 . ", " . $nextMax * 10);
            $stmt->execute($params);
            while ($row = clean($stmt->fetch(PDO::FETCH_ASSOC))) {
                $listUnit = Video::nu($row["video_id"]);
                echo "<tr id='", $row["video_id"], "'>
                <td><a href='video.php?t=", $row["video_id"], "'>", $listUnit->renderThumbnail(), "</a></td>
                <td>", $listUnit->render(), "</td>
              </tr>";
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    } else {
        $searchbox = new SearchBox($json_input);
        $searchbox->makeUseful();
        Video::insertVideo($searchbox);
    }
    exit();
}
?>

<html>
<head>
    <title>Videos</title>
    <?php include('libs/headers.php'); ?>
    <script type="text/javascript">
        var newTitle;
        var newURL;
        var spinner;
        var displayCount = 1;
        var display;

        function init() {
            newTitle = $("#newTitle");
            newURL = $("#newURL");
            spinner = $("#spinner");
            display = $("#all_videos");
            newTitle.keyup(function () {
                Helper.displayBtnAdd(newTitle.val().length > 0 && newURL.val().length > 0);
            });
            newURL.keyup(function () {
                Helper.displayBtnAdd(newTitle.val().length > 0 && newURL.val().length > 0);
            });

            $(window).scroll(function () {
                if (document.body.scrollTop + document.body.clientHeight >= $(document.body).height()) {
                    var newObj = {};
                    displayCount++;
                    newObj.nextMax = displayCount;
                    newObj.query = $("#query").text();
                    newObj.params = $("#params").text();
                    Helper.postJSON(newObj, "z",
                        function (data, textStatus, jqXHR) {
                            if (data) {
                                if (data.charAt(1) == 't' && data.charAt(2) == 'r') {
                                    display.append(data);
                                }
                                else {
                                    alert(data);
                                }
                            }
                            else {
                                $(window).unbind("scroll");
                                spinner.remove();
                            }
                        });
                }
            });
            if (!spinner) {
                $(window).unbind("scroll");
            }

            Helper.setupDataTable("Videos");
            setupSearchBox();
            newTitle.focus();
        }

        function createVideos() {
            var newObj = {};
            newObj.title = newTitle.val();
            newObj.url = newURL.val();
            if (newObj.title.length == 0) {
                alert("Please enter a video title.");
                newTitle.focus();
                return;
            }
            newObj.tournament = $("#newTourny").val();
            if (newObj.tournament < 0) newObj.tournament = undefined;
            Helper.uploadObj(newObj);
        }
    </script>
</head>
<body>
<?php include('libs/navheader.php');


$searchbox = SearchBox::nu("Filter/Add Videos", Video::getQueryFields());
$searchbox->render();

list($params, $sqlQueryOriginal) = Video::constructQuery($searchbox);
$sqlQuery = $sqlQueryOriginal . " LIMIT 0,10";
$conn = DbUtil::connect();
$stmt = $conn->prepare($sqlQuery);
$stmt->execute($params);
$stmt->setFetchMode(PDO::FETCH_ASSOC);
echo "<div class='body'>";
echo "<table id='all_videos'>";
$rowCount = 0;
while ($row = clean($stmt->fetch())) {
    $rowCount++;
    $listUnit = Video::nu($row["video_id"]);
    echo "<tr id='", $row["video_id"], "'>
                <td><a href='video.php?t=", $row["video_id"], "'>", $listUnit->renderThumbnail(), "</a></td>
                <td>", $listUnit->render(), "</td>
              </tr>";
}
echo "</table>";
if ($rowCount == 10) echo "<br><div class='spin' id='spinner'></div>";
echo "</div>";

echo "<div id='query' style='display: none;'>", $sqlQueryOriginal, "</div>";
echo "<div id='params' style='display: none;'>", json_encode($params), "</div>";
include('libs/players.php');
include('libs/characters.php');
include('libs/versions.php');
include('libs/tournaments.php');
include('libs/techniques.php');
?>
</body>
</html>