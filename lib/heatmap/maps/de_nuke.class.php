<?php

class de_nuke extends MapsHeatmap {

    public function __construct($match_id) {
        $this->setStartX(-3008);
        $this->setStartY(-2496);
        $this->setEndX(3523);
        $this->setEndY(960);
        $this->setFlipV(true);
        $this->setResX(500);
        $this->setResY(264);
        $this->calcSize();
        $this->setMatchId($match_id);
    }

    public function getMapImage() {
        return "/images/maps/csgo/overview/de_nuke.png";
    }

}

?>
