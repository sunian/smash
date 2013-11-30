<?php
/**
 * Created by PhpStorm.
 * User: Arjun
 * Date: 11/18/13
 * Time: 10:23 PM
 */
require_once('DbUtil.php');
require_once('Video.php');
require_once('Player.php');

class VideoListUnit {
    public $video;

    function __construct($video_id = false) {
        if($video_id) {
            $this->video = Video::nu($video_id);
            $this->video->populateFieldsFromID();
        }
    }

    public function getThumbnail() {
        return "<img src=\"http://img.youtube.com/vi/" . $this->video->getIDFromURL() . "/mqdefault.jpg\">";
    }

    public function render() {
        echo "<div>";
        if(count($this->video->playerPlaysChar)>0) {
            foreach ($this->video->playerPlaysChar as $i => $video_player) {
                if ($i > 0) echo  ", ";
                echo $video_player->player->tag , " (" , $video_player->character->name , ")";
            }
        }
        echo "<br>Version: ";
        if($this->video->versions) {
            foreach ($this->video->versions as $i => $version) {
                if ($i > 0) echo  ", ";
                echo $version->pretty_name;
            }
        }
        else {
            echo "Unknown";
        }
        echo "</div>\n";
    }
}