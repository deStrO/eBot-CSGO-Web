<?php

/**
 * Matchs form.
 *
 * @package    PhpProject1
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class MatchsForm extends BaseMatchsForm {

    public function configure() {
        unset($this["ip"], $this["tv_record_file"], $this["server_id"], $this["ingame_enable"], $this["config_auto_change_password"], $this["created_at"], $this["updated_at"], $this["current_map"], $this["status"], $this["score_a"], $this["score_b"], $this["config_heatmap"], $this["enable"]);

        $this->widgetSchema["config_full_score"]->setLabel("Full Score (all rounds)");
        $this->widgetSchema["config_ot"]->setLabel("OverTime");
        $this->widgetSchema["config_knife_round"]->setLabel("Knife Round");
        $this->widgetSchema["config_switch_auto"]->setLabel("Switch auto");
        $this->widgetSchema["config_password"]->setLabel("Mot de passe");
        
        $flags = sfConfig::get("app_flag_team");
        $aFlags = array();
        foreach ($flags as $flag) {
            $aFlags[$flag] = $flag;
        }
        
        $this->widgetSchema["team_a_flag"] = new sfWidgetFormSelect(array("choices" => $aFlags));
        $this->widgetSchema["team_b_flag"] = new sfWidgetFormSelect(array("choices" => $aFlags));
    }

}
