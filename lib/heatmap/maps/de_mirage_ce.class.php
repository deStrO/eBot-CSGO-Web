<?php

class de_mirage_ce extends MapsHeatmap {

    public function __construct($match_id) {
        $this->setStartX(-2672);
        $this->setStartY(-2536);
        $this->setEndX(1472);
        $this->setEndY(888);
        $this->setFlipV(true);
        $this->setResX(500);
        $this->setResY(413);
        $this->calcSize();
        $this->setMatchId($match_id);
    }

    public function getMapImage() {
        return "/images/maps/csgo/overview/de_mirage_ce.png";
    }

}

?>
