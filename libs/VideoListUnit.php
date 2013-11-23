<?php
/**
 * Created by PhpStorm.
 * User: Arjun
 * Date: 11/18/13
 * Time: 10:23 PM
 */
require_once('DbUtil.php');
require_once('Videos.php');

class VideoListUnit {
    var $video;

    function __construct($video_id) {
        $this->video = new Video();
        $this->video->video_id = $video_id;
        $this->video->populateFieldsFromID();
    }

    public function getThumbnail() {
        return "<img src=\"http://img.youtube.com/vi/" . $this->video->getIDFromURL() . "/1.jpg\">";
    }

    public function getVideoInformation() {
        $outputString = "<div>Players: " . $this->players[0];
        for($i=1; $i<count($this->players); $i++) {
            $outputString = $outputString . ", " . $this->players[$i];
        }
        $outputString = $outputString . "</div>";

        return $outputString;
    }
}