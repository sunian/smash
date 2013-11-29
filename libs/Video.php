<?php
/**
 * Created by PhpStorm.
 * User: Arjun
 * Date: 11/15/13
 * Time: 11:55 PM
 */
require_once('DbUtil.php');
require_once('Technique.php');
require_once('Player.php');
require_once('Character.php');
require_once('Version.php');
require_once('SearchBox.php');

class Video extends JSONObject {
    public $video_id = null;
    public $title = null;
    public $url = null;
    public $date_added = null;
    public $players = null;
    public $techniques = null;
    public $characters = null;
    public $versions = null;
    public $tournament = null;
    public $playerPlaysChar = null;

    public function createVideo()
    {
        try {
            if($this->url{strlen($this->url)-1}=='/') $this->url = substr($this->url, 0, strlen($this->url)-1);
            $conn = DbUtil::connect();
            $sql_string = "SELECT video_id FROM video WHERE url = :url";
            $params = array("url" => $this->url);
            $stmt = $conn->prepare($sql_string);
            $stmt->execute($params);
            if ($row = $stmt->fetch()) {
                // Error message!!
                return "That video already exists!";
            }
            $stmt->closeCursor();
            $sql_string = "INSERT INTO video (title, url, date_added, tournament_id) VALUES (:title, :url, NOW(),:tourny)";
            $stmt = $conn->prepare($sql_string);
            $params["title"] = $this->title;
            $params["tourny"] = $this->tournament;
            $stmt->execute($params);
            $stmt->closeCursor();
            return false;

        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public static function getQueryFields() {
        return array(
            QueryField::nu("title", "Title", "input", "1"),
            QueryField::nu("version", "Game Version", "select:createVersionSelector", "*"),
            QueryField::nu("video_player", "Player(Character) uses Technique",
                "select:createPlayerSelector select:createCharacterSelector <br>uses&nbsp; select:createTechniqueSelector", "*"),
            QueryField::nu("tournament", "Tourny", "select:createTournamentSelector", "1")
        );
    }

    public static function constructDataTableFrom($searchbox = false) {

        $table = new DataTable("Videos", array(
            new TableColumn("Title", "newTitle", "input", "Title"),
            new TableColumn("Video URL", "newURL", "input", "Video URL"),
            new TableColumn("Tournament", "newTourny", "select", "createTournamentSelector"),
            new TableColumn("Date Added", "newDate", "none", "")
        ));
        $params = array();
        $projection = "v.title, v.url, v.date_added, v.video_id, v.tournament_id as t_id, t.name";
        $joins = "";
        $where = "";
        $crazy = "";
        $table->renderData = function ($row) {
            echo "<tr>";
            echo "<td><a href='video.php?t=", $row["video_id"], "'>", $row["title"], "</a></td>";
            echo "<td><a href='", $row["url"], "'>", $row["url"], "</a></td>";
            if ($row["t_id"])
                echo "<td><a href='tournaments.php?t=", $row["t_id"], "'>", $row["name"], "</a></td>";
            else
                echo "<td></td>";
            echo "<td class='moment'>", $row["date_added"], "</td>";
            echo "</tr>";
        };
        if ($searchbox) {
            foreach ($searchbox->fields as $queryField) {
                if (count($queryField->values) == 0) continue;
                switch ($queryField->id) {
                    case "version":
                        $joins .= " left outer join video_version as vv on v.video_id = vv.video_id";
                        if (strlen($where) > 0) $where .= " and";
                        $where .= " vv.version_id in (";
                        break;
                    case "video_player":
//                        $joins .= " left outer join video_player as vp on v.video_id = vp.video_id";
//                        $projection .= ", vp.player_id, vp.character_id";
                        break;
                }

                $i = 0;
                foreach ($queryField->values as $field) {
                    switch ($queryField->id) {
                        case "title":
                            if (strlen($where) > 0) $where .= " and";
                            $where .= " v.title like concat('%',:title$i,'%')";
                            $params["title$i"] = $field[0];
                            break;
                        case "tournament":
                            if (strlen($where) > 0) $where .= " and";
                            $where .= " t.tournament_id = :tournament$i";
                            $params["tournament$i"] = $field[0];
                            break;
                        case "version":
                            if ($i > 0) $where .= ",";
                            $where .= ":version$i";
                            $params["version$i"] = $field[0];
                            break;
                        case "video_player":
                            $crazy .= " inner join (select v.video_id from video_player as v";
                            if ($field[1] > 0) {
                                $crazy .= " inner join `character` as c$i on v.character_id = c$i.character_id";
                            }
                            if ($field[2] > 0) {
                                $crazy .= " inner join
                                    (select video_player_id from technique_usage where technique_id = :technique$i )
                                    as tch$i on v.video_player_id = tch$i.video_player_id";
                                $params["technique$i"] = $field[2];
                            }
                            if ($field[0] > 0) {
                                $crazy .= " where ";
                                $crazy .= " v.player_id = :player$i";
                                $params["player$i"] = $field[0];
                                if ($field[1] > 0) $crazy .= " and";
                            }
                            if ($field[1] > 0) {
                                if ($field[0] < 0) $crazy .= " where ";
                                $crazy .= " c$i.identity_id = :character$i";
                                $params["character$i"] = $field[1];
                            }
                            $crazy .= " group by v.video_id) as vp$i on x.video_id = vp$i.video_id";
                            break;
                    }
                    $i++;
                }
                switch ($queryField->id) {
                    case "version":
                        $where .= ") ";
                        break;
                }
            }

        }
        $sqlQuery = "Select * from (SELECT " . $projection .
            " FROM video as v left outer join tournament as t on v.tournament_id = t.tournament_id " . $joins .
            (strlen($where) > 0 ? " where " . $where : "") .
            " ) as x " . $crazy . " group by x.video_id ORDER BY x.date_added DESC";
        echo "query=$sqlQuery _ ";
        $table->setData($sqlQuery, $params);
        return $table;
    }

    public function populateFieldsFromID() {
        $conn = DbUtil::connect();
        $sqlString = "SELECT title, url, date_added, tournament_id as tournament FROM video WHERE video_id = :video_id";
        $params = array("video_id"=>$this->video_id);
        $stmt = $conn->prepare($sqlString);
        $stmt->execute($params);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $row = $stmt->fetch();
        $this->date_added = $row["date_added"];
        $this->url = $row["url"];
        $this->title = $row["title"];

        $this->populateTechniques();
        $this->populatePlayers();
        $this->populateCharacters();
        $this->populateVersions();
        $this->populatePlayerPlaysChar();
    }

    private function populateTechniques() {
        try {
            $conn = DbUtil::connect();
            $sql_string = "SELECT technique_id, name, abbreviation FROM video NATURAL JOIN video_player NATURAL JOIN technique_usage NATURAL JOIN technique" .
                " WHERE video_id = :video_id";
            $params = array("video_id" => $this->video_id);
            $stmt = $conn->prepare($sql_string);
            $stmt->execute($params);
            $this->techniques = $stmt->fetchAll(PDO::FETCH_CLASS, "Technique");
        }
        catch(PDOException $e) {
            return $e->getMessage();
        }
    }

    private function populatePlayerPlaysChar() {
        try {
            $conn = DbUtil::connect();
            $sql_string = "SELECT player_id, character_id FROM video_player WHERE video_id = :video_id";
            $params = array("video_id" => $this->video_id);
            $stmt = $conn->prepare($sql_string);
            $stmt->execute($params);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $count = 0;
            while($row = $stmt->fetch()) {
                $thisPlayerPlays = new PlayerPlaysChar();
                $thisPlayerPlays->player = new Player();
                $thisPlayerPlays->character = new Character();
                $thisPlayerPlays->player->player_id = $row["player_id"];
                $thisPlayerPlays->character->id = $row["character_id"];
                $thisPlayerPlays->player->populateFieldsFromID();
                $thisPlayerPlays->character->populateFieldsFromID();
                $this->playerPlaysChar[$count++] = $thisPlayerPlays;
            }
        }
        catch(PDOException $e) {
            return $e->getMessage();
        }
    }

    private function populateVersions() {
        try {
            $conn = DbUtil::connect();
            $sql_string = "SELECT v.title AS title, v.version_number, p.name AS pretty_name
                FROM video NATURAL JOIN video_version NATURAL JOIN version AS v NATURAL JOIN pretty_version AS p" .
                " WHERE video_id = :video_id";
            $params = array("video_id" => $this->video_id);
            $stmt = $conn->prepare($sql_string);
            $stmt->execute($params);
            $this->versions = $stmt->fetchAll(PDO::FETCH_CLASS, "Technique");
        }
        catch(PDOException $e) {
            return $e->getMessage();
        }
}

    public function getIDFromURL() {
        $query = substr($this->url, strpos($this->url, "?"));
        $query = substr($query, strpos($query, "v=")+2);
        if(strpos($query, "&")>-1) $query = substr($query, 0, strpos($query, "&"));
        return $query;
    }

    private function populatePlayers() {
        try {
            $conn = DbUtil::connect();
            $sql_string = "SELECT player_id FROM video_player WHERE video_id = :video_id";
            $stmt = $conn->prepare($sql_string);
            $params = array("video_id"=>$this->video_id);
            $stmt->execute($params);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $count = 0;
            while($row = $stmt->fetch()) {
                $thisPlayer = new Player();
                $thisPlayer->player_id = $row["player_id"];
                $thisPlayer->populateFieldsFromID();
                $this->players[$count++] = $thisPlayer;
            }
        }
        catch(PDOException $e) {
//            echo "Error in populate players\n";
//            echo $e->getMessage();
            return $e->getMessage();
        }
    }

    private function populateCharacters() {
        try {
            $conn = DbUtil::connect();
            $sql_string = "SELECT character_id FROM video_player WHERE video_id = :video_id";
            $stmt = $conn->prepare($sql_string);
            $params = array("video_id"=>$this->video_id);
            $stmt->execute($params);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $count = 0;
            while($row = $stmt->fetch()) {
                $thisCharacter = new Character();
                $thisCharacter->id = $row["character_id"];
                $thisCharacter->populateFieldsFromID();
                $this->characters[$count++] = $thisCharacter;
            }
        }
        catch(PDOException $e) {
//            echo "Error in populate characters\n";
//            echo $e->getMessage();
            return $e->getMessage();
        }
    }
}

class PlayerPlaysChar {
    public $player;
    public $character;
}
