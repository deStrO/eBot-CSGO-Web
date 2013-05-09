<?php

class de_train_se extends MapsHeatmap {

    public function __construct($match_id) {
        $this->setStartX(-2036);
        $this->setStartY(-1792);
        $this->setEndX(2028);
        $this->setEndY(1820);
        $this->setFlipV(true);
        $this->setResX(500);
        $this->setResY(442);
        $this->calcSize();
        $this->setMatchId($match_id);
    }

    public function getMapImage() {
        return "/images/maps/csgo/overview/de_train_se_radar.png";
    }

}

?>
