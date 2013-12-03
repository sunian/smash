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
    if (strcmp($input_type, "q") == 0) {//user performed search
        Video::constructDataTableFrom(new SearchBox($json_input))->render();
    } else {
        $video = new Video($json_input);
        $error = $video->createVideo();
        if ($error) echo $error;
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

        function init() {
            newTitle = $("#newTitle");
            newURL = $("#newURL");
            newTitle.keyup( function () {
                Helper.displayBtnAdd(newTitle.val().length > 0 && newURL.val().length > 0);
            });
            newURL.keyup( function () {
                Helper.displayBtnAdd(newTitle.val().length > 0 && newURL.val().length > 0);
            });

            $(window).scroll(function () {
                if ($(document).height() <= $(window).scrollTop() + $(window).height()) {
                    alert("End Of The Page");
                }
            });

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


$searchbox = SearchBox::nu("Filter Videos", Video::getQueryFields());
$searchbox->render();

$table = Video::constructDataTableFrom($searchbox);
$table->render();

include('libs/players.php');
include('libs/characters.php');
include('libs/versions.php');
include('libs/tournaments.php');
include('libs/techniques.php');
?>
</body>
</html>