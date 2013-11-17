<?php

class de_inferno extends MapsHeatmap {

    public function __construct($match_id) {
        $this->setStartX(-1980);
        $this->setStartY(-808);
        $this->setEndX(2720);
        $this->setEndY(3616);
        $this->setFlipV(true);
        $this->setResX(500);
        $this->setResY(471);
        $this->calcSize();
        $this->setMatchId($match_id);
    }

    public function getMapImage() {
        return "/images/maps/csgo/overview/de_inferno.png";
    }

}

?>
