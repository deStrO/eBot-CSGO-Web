<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Maps
 *
 * @author deStrO
 */
abstract class MapsHeatmap {

    protected $match_id = 0;
    private $sizeX = 0;
    private $sizeY = 0;
    private $startX = 0;
    private $startY = 0;
    private $endX = 0;
    private $endY = 0;
    private $flipV = false;
    private $flipH = false;
    private $resX = 0;
    private $resY = 0;
    private $points = array();

    public function getFlipV() {
        return $this->flipV;
    }

    public function getMatchId() {
        return $this->match_id;
    }

    public function setMatchId($match_id) {
        $this->match_id = $match_id;
    }

    public function setFlipV($flipV) {
        $this->flipV = $flipV;
    }

    public function getFlipH() {
        return $this->flipH;
    }

    public function setFlipH($flipH) {
        $this->flipH = $flipH;
    }

    public function getResX() {
        return $this->resX;
    }

    public function setResX($resX) {
        $this->resX = $resX;
    }

    public function getResY() {
        return $this->resY;
    }

    public function setResY($resY) {
        $this->resY = $resY;
    }

    public function getSizeX() {
        return $this->sizeX;
    }

    public function setSizeX($sizeX) {
        $this->sizeX = $sizeX;
    }

    public function getSizeY() {
        return $this->sizeY;
    }

    public function setSizeY($sizeY) {
        $this->sizeY = $sizeY;
    }

    public function getStartX() {
        return $this->startX;
    }

    public function setStartX($startX) {
        $this->startX = $startX;
    }

    public function getStartY() {
        return $this->startY;
    }

    public function setStartY($startY) {
        $this->startY = $startY;
    }

    public function getEndX() {
        return $this->endX;
    }

    public function setEndX($endX) {
        $this->endX = $endX;
    }

    public function getEndY() {
        return $this->endY;
    }

    public function setEndY($endY) {
        $this->endY = $endY;
    }

    public function calcSize() {
        $this->sizeX = $this->endX - $this->startX;
        $this->sizeY = $this->endY - $this->startY;
    }

    abstract public function getMapImage();

    public function calculPointToRes(&$x, &$y) {
        $x += ( $this->getStartX() < 0) ? $this->getStartX() * -1 : $this->getStartX();
        $y += ( $this->getStartY() < 0) ? $this->getStartY() * -1 : $this->getStartY();

        $posX = floor(($x / $this->getSizeX()) * $this->getResX());
        $posY = floor(($y / $this->getSizeY()) * $this->getResY());

        if ($this->getFlipH()) {
            $posX = ($posX - $this->getResX()) * -1;
        }

        if ($this->getFlipV()) {
            $posY = ($posY - $this->getResY()) * -1;
        }

        $x = $posX;
        $y = $posY;
    }

    private function isInMap($x, $y) {
        return ($x >= $this->startX) && ($x <= $this->endX) && ($y >= $this->startY) && ($y <= $this->endY);
    }

    public function addInformation($event_id, $event_name, $event_x, $event_y, $player_name, $player_team, $round, $round_time, $event_damage = 0, $side = -1, $attacker_x = 0, $attacker_y = 0, $attacker_name = "", $attacker_team = "") {
        if (!$this->isInMap($event_x, $event_y))
            return;

        $this->points[] = array(
            "event_id" => $event_id,
            "event_name" => $event_name,
            "event_x" => $event_x,
            "event_y" => $event_y,
            "player_name" => $player_name,
            "player_team" => $player_team,
            "round" => $round,
            "round_time" => $round_time,
            "event_damage" => $event_damage,
            "attacker_x" => $attacker_x,
            "attacker_y" => $attacker_y,
            "attacker_name" => $attacker_name,
            "attacker_team" => $attacker_team,
            "side" => $side
        );
    }

    public function buildHeatMap($event_name, $round = null, $side = -1, $player_name = null, $sideName = "all") {
        $points = array();
        switch ($event_name) {
            default:
            case "kill":
                foreach ($this->points as $point) {
                    if ($point["event_name"] == $event_name)
                        $points[] = $point;
                }
                break;
        }

        $pointToReturn = array();
        foreach ($points as $point) {
            if ($round != null) {
                if (!in_array($point['round'], $round)) {
                    continue;
                }
            }

            if ($side >= 0) {
                if ($point['side'] != $side) {
                    continue;
                }
            }

            if ($player_name) {
                if (!in_array($point['player_name'], $player_name)) {
                    continue;
                }
            }

            if ($sideName != "all") {
                if ($sideName == "ct") {
                    if ($point['player_team'] != "CT")
                        continue;
                } elseif ($sideName == "t") {
                    if ($point['player_team'] != "TERRORIST")
                        continue;
                }
            }

            $x = $point["event_x"];
            $y = $point["event_y"];

            $this->calculPointToRes($x, $y);

            $pointToReturn[] = array("x" => $x, "y" => $y);
        }

        return $pointToReturn;
    }

    public function buildMapDot($event_name, $round = null, $side = -1, $player_name = null, $sideName = "all") {
        switch ($event_name) {
            default:
            case "kill":
                foreach ($this->points as $point) {
                    if ($point["event_name"] == $event_name)
                        $points[] = $point;
                }
                break;
        }

        $pointToReturn = array();
        foreach ($points as $point) {
            if ($round != null) {
                if (!in_array($point['round'], $round)) {
                    continue;
                }
            }

            if ($side >= 0) {
                if ($point['side'] != $side) {
                    continue;
                }
            }

            if ($player_name) {
                if (!in_array($point['player_name'], $player_name)) {
                    continue;
                }
            }

            if ($sideName != "all") {
                if ($sideName == "ct") {
                    if ($point['player_team'] != "CT")
                        continue;
                } elseif ($sideName == "t") {
                    if ($point['player_team'] != "TERRORIST")
                        continue;
                }
            }

            $x = $point["event_x"];
            $y = $point["event_y"];
            $this->calculPointToRes($x, $y);
            $pointToReturn[] = array("x" => $x, "y" => $y, "name" => $point['player_name'], "team" => $point['player_team']);
        }

        return $pointToReturn;
    }

    public function buildTraceDead($round = null, $side = -1, $player_name = null, $sideName = "all") {
        foreach ($this->points as $point) {
            if ($point["event_name"] == "kill")
                $points[] = $point;
        }

        $pointToReturn = array();

        foreach ($points as $point) {
            if ($round != null) {
                if (!in_array($point['round'], $round)) {
                    continue;
                }
            }

            if ($side >= 0) {
                if ($point['side'] != $side) {
                    continue;
                }
            }

            if ($player_name) {
                if (!in_array($point['player_name'], $player_name)) {
                    continue;
                }
            }

            if (($point["attacker_x"] == 0) && ($point["attacker_y"] == 0)) {
                continue;
            }

            if ($sideName != "all") {
                if ($sideName == "ct") {
                    if ($point['player_team'] != "CT")
                        continue;
                } elseif ($sideName == "t") {
                    if ($point['player_team'] != "TERRORIST")
                        continue;
                }
            }

            $x = $point["event_x"];
            $y = $point["event_y"];
            $this->calculPointToRes($x, $y);

            $x2 = $point["attacker_x"];
            $y2 = $point["attacker_y"];
            $this->calculPointToRes($x2, $y2);
            $pointToReturn[] = array("x" => $x, "y" => $y, "name" => $point['player_name'], "team" => $point['player_team'], "x2" => $x2, "y2" => $y2, "attacker" => $point['attacker_name'], "attacker_team" => $point['attacker_team']);
        }

        return $pointToReturn;
    }

    public function buildLiveMap($event_id, $round = null, $side = -1, $player_name = null, $sideName = "all") {
        foreach ($this->points as $point) {
            if ($point["event_name"] == "kill")
                $points[] = $point;
        }

        $pointToReturn = array();

        foreach ($points as $point) {
            if ($point['event_id'] <= $event_id) {
                continue;
            }

            if ($round != null) {
                if (!in_array($point['round'], $round)) {
                    continue;
                }
            }

            if ($side >= 0) {
                if ($point['side'] != $side) {
                    continue;
                }
            }

            if ($player_name) {
                if (!in_array($point['player_name'], $player_name)) {
                    continue;
                }
            }

            if (($point["attacker_x"] == 0) && ($point["attacker_y"] == 0)) {
                continue;
            }

            if ($sideName != "all") {
                if ($sideName == "ct") {
                    if ($point['player_team'] != "CT")
                        continue;
                } elseif ($sideName == "t") {
                    if ($point['player_team'] != "TERRORIST")
                        continue;
                }
            }

            $x = $point["event_x"];
            $y = $point["event_y"];
            $this->calculPointToRes($x, $y);

            $x2 = $point["attacker_x"];
            $y2 = $point["attacker_y"];
            $this->calculPointToRes($x2, $y2);

            $pointToReturn[] = array("x" => $x, "y" => $y, "name" => $point['player_name'], "team" => $point['player_team'], "x2" => $x2, "y2" => $y2, "attacker" => $point['attacker_name'], "attacker_team" => $point['attacker_team']);
        }
        return $pointToReturn;
    }

}

?>
