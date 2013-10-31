<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sun
 * Date: 10/15/13
 * Time: 9:26 PM
 * To change this template use File | Settings | File Templates.
 */
//ini_set('display_errors',"1");
$navPages = array(
    'videos.php' => 'Videos',
    'players.php' => 'Players',
    'characters.php' => 'Characters',
    'tournaments.php' => 'Tournaments',
    'techniques.php' => 'Tech'
);
if ($activePage == null) {
    $activePage = basename($_SERVER['PHP_SELF']);
}
?>
<div id="pageHeader" style="
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(19,23,50,.9)), color-stop(100%,rgba(5,6,13,0.5)));
    background: -webkit-linear-gradient(top,  rgba(19,23,50,.9) 0%,rgba(5,6,13,0.5) 100%);
    text-align: left;
         ">
    <a href="index.php"><img src="images/title_logo.png" style="margin: 0 8px;
        max-height: 11%;
        vertical-align: text-bottom;
        "></a>
    <nav>
        <?php
        foreach ($navPages as $navPage => $pageTitle) {
            echo "<a class='naviLink clickable",
                $navPage == $activePage ? " active" : "",
                "' href='", $navPage, "'>", $pageTitle, "</a>";
        }
        ?>
    </nav>

</div>