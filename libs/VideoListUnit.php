<?php
/**
 * Created by PhpStorm.
 * User: Arjun
 * Date: 11/18/13
 * Time: 10:23 PM
 */
require_once('DbUtil.php');

class VideoListUnit {
    var $video_id;
    var $title;
    var $url;
    var $date_added;
    var $players;
    var $characters;

    function __construct($video_id) {
        $conn = DbUtil::connect();
        $this->video_id = $video_id;
        $queryString = "SELECT title, url, date_added FROM video WHERE video_id = :video_id";
        $params = array("video_id"=>$this->video_id);
        $stmt = $conn->prepare($queryString);
        $stmt->execute($params);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetch();
        $this->title = $result["title"];
        $this->url = $result["url"];
        $this->date_added = $result["date_added"];
    }

    public function getDisplayString() {
        $query = substr($this->url, strpos($this->url, "?"));
        $query = substr($query, strpos($query, "v=")+2);
        if(strpos($query, "&")>-1) $query = substr($query, 0, strpos($query, "&"));
        return "<div class='video_list_unit' id='". $this->video_id . "'>
            <img src=\"http://img.youtube.com/vi/" . $query . "/1.jpg\"> </div>";
    }
}