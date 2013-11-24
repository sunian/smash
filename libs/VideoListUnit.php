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
require_once('Character.php');

class VideoListUnit {
    var $video;

    function __construct($video_id) {
        $this->video = new Video();
        $this->video->video_id = $video_id;
        $this->video->populateFieldsFromID();
    }

    public function getThumbnail() {
        return "<img src=\"http://img.youtube.com/vi/" . $this->video->getIDFromURL() . "/mqdefault.jpg\">";
    }

    public function getVideoInformation() {
        $outputString = "<div>Players: " . $this->video->players[0]->tag;
        for($i=1; $i<count($this->video->players); $i++) {
            $outputString = $outputString . ", " . $this->video->players[$i]->tag;
        }
        $outputString = $outputString . "<br>Characters: " . $this->video->characters[0]->name;
        for($i=1; $i<count($this->video->characters); $i++) {
            $outputString = $outputString . ", " . $this->video->characters[$i]->name;
        }
        $outputString = $outputString . "<br>" . $this->video->playerPlaysChar[0]->character->name . "(" .
            $this->video->playerPlaysChar[0]->player->tag . ")";
        for($i=1; $i<count($this->video->playerPlaysChar); $i++) {
            $outputString = $outputString . ", " . $this->video->playerPlaysChar[$i]->character->name . "("
                $this->video->playerPlaysChar[$i]->player->tag . ")";
        }
        $outputString = $outputString . "</div>";

        return $outputString;
    }
}