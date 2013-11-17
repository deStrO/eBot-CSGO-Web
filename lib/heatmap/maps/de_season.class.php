<?php

class de_season extends MapsHeatmap {

    public function __construct($match_id) {
        $this->setStartX(-848);
        $this->setStartY(-2464);
        $this->setEndX(3760);
        $this->setEndY(2032);
        $this->setFlipV(true);
        $this->setResX(500);
        $this->setResY(488);
        $this->calcSize();
        $this->setMatchId($match_id);
    }

    public function getMapImage() {
        return "/images/maps/csgo/overview/de_season.png";
    }

}

?>
