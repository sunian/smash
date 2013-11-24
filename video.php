<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Student
 * Date: 10/18/13
 * Time: 2:53 AM
 * To change this template use File | Settings | File Templates.
 */
require_once('libs/browser.php');
require_once('libs/DbUtil.php');
require_once('libs/Videos.php');
//require_once('libs/Techniques.php');

//if (strlen($json_input) > 0) {
   // $video = new Video($json_input);
   // $error = $video->createIdentity();
   // if ($error) echo $error;
   // exit();
//}
?>

<html>
<head>
    <title>Video</title>
    <?php include('libs/headers.php');
    if(!$urlParams["t"]) {
        header("Location: http://plato.cs.virginia.edu/~jcs5sb/smash/videos.php");
        exit;
    }
    else {
        echo "<div id=\"div_urlParam\" style=\"display: none;\">" , $urlParams["t"] , "</div>";
    }
    ?>
    <script type="text/javascript">
        var newTechnique;
        var selectTechnique;
        $(function () {
            newTechnique = $("#newTechnique");
            selectTechnique = createTechniqueSelector();
            selectTechnique.id = "selectTechnique";
//            selectTechnique.disabled = true;
            $("#_newTechnique")[0].appendChild(selectTechnique);
//            selectTechnique = createTechniqueSelector();
//            selectTechnique.id = "selectTechnique";
//            newTechnique[0].appendChild(selectTechnique);
        });
    </script>
</head>
<body text="white">

<?php include('libs/navheader.php');

    $vid = new Video();
    $vid->set(array("video_id"=>$urlParams["t"]));
    echo $vid->populateFieldsFromID();
    $urlID = $vid->getIDFromURL();

    for($i=0; $i<count($vid->players); $i++) {
        $output = $output . ", " . $vid->players[$i]->tag;
    }
    echo $output;

    for($i=0; $i<count($vid->characters); $i++) {
        $output2 = $output2 . ", " . $vid->characters[$i]->name;
    }
    echo $output2;

    echo "<h1>", $vid->title , "</h1>";

//    $table = new DataTable("Techniques", array(
//    new TableColumn("Technique", "newTechnique", "select", "New Technique"),
//    ));
//
//    $table->renderData = function ($row) {
//    echo "<tr>";
//    echo "<td>" . $row["techniques"] . "</td>";
//    echo "</tr>";
//    };
//    $table->render();

include('libs/techniques.php');
?>

<div class='body'>
<table>
    <tr>
        <td style="background-color:black;width:425px;height=350">
            <iframe name='video'
                    width="425" height="350"
                    src="http://www.youtube.com/embed/<?php echo $urlID;?>?enablejsapi=1&playsinline=1&autoplay=1"
                    seamless>
            </iframe>
        </td>
        <td style="background-color:black;width:425px;height=350">
            <h2>Information</h2><br>
        </td>
    </tr>
</table>
</div>
</body>
</html>