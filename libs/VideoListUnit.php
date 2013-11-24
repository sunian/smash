<?php
/**
 * Created by PhpStorm.
 * User: Arjun
 * Date: 11/18/13
 * Time: 10:23 PM
 */
require_once('DbUtil.php');
require_once('Videos.php');
require_once('Player.php');

class VideoListUnit {
    var $video;

    function __construct($video_id = false) {
        if($video_id) {
            $this->video = new Video();
            $this->video->video_id = $video_id;
            $this->video->populateFieldsFromID();
        }
    }

    public function getThumbnail() {
        return "<img src=\"http://img.youtube.com/vi/" . $this->video->getIDFromURL() . "/mqdefault.jpg\">";
    }

    public function getVideoInformation() {
        $outputString = "<div>";
        if(count($this->video->playerPlaysChar)>0) {
            $outputString = $outputString . $this->video->playerPlaysChar[0]->character->name . "(" .
                $this->video->playerPlaysChar[0]->player->tag . ")";
            for($i=1; $i<count($this->video->playerPlaysChar); $i++) {
                $outputString = $outputString . ", " . $this->video->playerPlaysChar[$i]->character->name . "(" .
                    $this->video->playerPlaysChar[$i]->player->tag . ")";
            }
        }
        $outputString = $outputString . "<br>Version: ";
        if($this->video->versions) {
             $outputString = $outputString . $this->video->versions[0]->pretty_name;
            for($i=1; $i<count($this->video->versions); $i++) {
                $outputString = $outputString . ", " + $this->video->versions[$i]->pretty_name;
            }
        }
        else {
            $outputString = $outputString . "Unknown";
        }
        $outputString = $outputString . "</div>\n";

        return $outputString;
    }
}