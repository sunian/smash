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
require_once('libs/Videos.php');
if (strlen($json_input) > 0) {
    $video = new Video($json_input);
    $error = $video->createIdentity();
    if ($error) echo $error;
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
        $(function () {
            newTitle = $("#newTitle");
            newURL = $("#newURL");
            newTitle.keyup( function () {
                Helper.displayBtnAdd(newTitle.val().length > 0 && newURL.val().length > 0);
            });
            newURL.keyup( function () {
                Helper.displayBtnAdd(newTitle.val().length > 0 && newURL.val().length > 0);
            });

            Helper.setupTables("Videos");

            newTitle.focus();
        });

        function createVideos() {
            var newObj = {};
            newObj.title = newTitle.val();
            newObj.url = newURL.val();
            if (newObj.title.length == 0) {
                alert("Please enter a video title.");
                newTitle.focus();
                return;
            }
//            console.log(JSON.stringify(newChar));
            Helper.uploadObj(newObj);
        }
    </script>
</head>
<body>
<?php include('libs/navheader.php');

$table = new DataTable("Videos", array(
    new TableColumn("Title", "newTitle", "input", "Title"),
    new TableColumn("Video URL", "newURL", "input", "Video URL"),
    new TableColumn("Date Added", "newDate", "none", "")
));
$table->setData("SELECT title, url, date_added, video_id
FROM video
ORDER BY date_added DESC", null);
$table->renderData = function ($row) {
    echo "<tr>";
    echo "<td><a href='videos.php?t=", $row["video_id"], "'>", $row["title"], "</a></td>";
    echo "<td> <a href=\"", $row["url"], "\">", $row["url"], "</a> </td>";
    echo "<td>", $row["date_added"], "</td>";
    echo "</tr>";
};
$table->render();

$searchbox = SearchBox::construct("filter", array(
    new QueryField("title", "Title"))
);
$searchbox->render();
echo print_r(new SearchBox(json_encode($searchbox)));
?>
</body>
</html>