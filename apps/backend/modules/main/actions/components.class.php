<?php

class mainComponents extends sfComponents {

    public function executeMenu() {
        $this->nbMatchs = MatchsTable::getInstance()->createQuery()->andWhere("status >= ? AND status <= ?", array(Matchs::STATUS_NOT_STARTED, Matchs::STATUS_END_MATCH))->andWhere("enable = ?", 1)->count();
        $this->ebot_ip = sfConfig::get("app_ebot_ip");
        $this->ebot_port = sfConfig::get("app_ebot_port");
    }

}

?>
