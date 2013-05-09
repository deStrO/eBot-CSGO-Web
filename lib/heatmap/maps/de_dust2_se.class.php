<?php

class de_dust2_se extends MapsHeatmap {

	public function __construct($match_id) {
		$this->setStartX(-2216);
		$this->setStartY(-1176);
		$this->setEndX(1792);
		$this->setEndY(3244);
		$this->setFlipV(true);
		$this->setResX(500);
		$this->setResY(530);
		$this->calcSize();
		$this->setMatchId($match_id);
	}

	public function getMapImage() {
		return "/images/maps/csgo/overview/de_dust2_se_radar.png";
	}

}

?>
