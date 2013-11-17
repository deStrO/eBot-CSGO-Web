<?php

class de_cache extends MapsHeatmap {

	public function __construct($match_id) {
		$this->setStartX(-1792);
		$this->setStartY(-1476);
		$this->setEndX(3328);
		$this->setEndY(2278);
		$this->setFlipV(true);
		$this->setResX(500);
		$this->setResY(366);
		$this->calcSize();
		$this->setMatchId($match_id);
	}

	public function getMapImage() {
		return "/images/maps/csgo/overview/de_cache.png";
	}

}

?>
