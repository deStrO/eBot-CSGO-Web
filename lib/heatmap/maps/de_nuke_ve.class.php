<?php

class de_nuke_ve extends MapsHeatmap {

    public function __construct($match_id) {
        $this->setStartX(-3008);
        $this->setStartY(-2496);
        $this->setEndX(3523);
        $this->setEndY(960);
        $this->setFlipV(true);
        $this->setResX(750);
        $this->setResY(398);
        $this->calcSize();
        $this->setMatchId($match_id);
    }

    public function getMapImage() {
        return "/images/maps/csgo/overview/de_nuke_ve_radar.png";
    }

}

?>
