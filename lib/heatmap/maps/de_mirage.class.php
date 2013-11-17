<?php

class de_mirage extends MapsHeatmap {

    public function __construct($match_id) {
        $this->setStartX(-2672);
        $this->setStartY(-2672);
        $this->setEndX(1440);
        $this->setEndY(912);
        $this->setFlipV(true);
        $this->setResX(500);
        $this->setResY(434);
        $this->calcSize();
        $this->setMatchId($match_id);
    }

    public function getMapImage() {
        return "/images/maps/csgo/overview/de_mirage.png";
    }

}

?>
