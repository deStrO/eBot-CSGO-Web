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
        unset($this["ip"], $this["identifier_id"], $this["force_zoom_match"], $this["config_switch_auto"], $this["tv_record_file"], $this["server_id"], $this["ingame_enable"], $this["config_auto_change_password"], $this["created_at"], $this["updated_at"], $this["current_map"], $this["status"], $this["score_a"], $this["score_b"], $this["config_heatmap"], $this["enable"]);

        $password = array('frosch', 'gehen', 'rennen', 'gucken', 'fliegen', 'rasen', 'snobb', 'peter', 'wackel', 'dackel', 'gut', 'schlecht', 'win', 'loss', 'tragen',
              'weg', 'berlin', 'aachen', 'mensch', 'tier', 'turtle', 'adler', 'raupe', 'rauben', 'bank', 'schalter', 'ticket', 'bahn', 'zug', 'delay', 'flugzeug', 'ratte',
              'nager', 'hase', 'feld', 'gras', 'kraut', 'gurke', 'apfel', 'salat', 'tomate', 'dressing', 'essig', 'zwiebel', 'kuchen', 'zucker', 'salz', 'kaffee', 'tee');

        $password = $password[rand(0, count($password)-1)];

        $this->widgetSchema["config_ot"]->setLabel("OverTime");
        $this->widgetSchema["config_streamer"]->setLabel("Streamer");
        $this->widgetSchema["config_knife_round"]->setLabel("KnifeRound");
        $this->widgetSchema["config_knife_round"]->setDefault(true);
        $this->widgetSchema["config_full_score"]->setLabel("Joueur tous les rounds");
        $this->widgetSchema["config_password"]->setLabel("Mot de passe");
        $this->widgetSchema["config_password"]->setDefault($password);
        $this->widgetSchema["rules"]->setDefault('');

        $this->widgetSchema['team_a']->setOption('method', 'getNameFlag');
        $this->widgetSchema['team_b']->setOption('method', 'getNameFlag');

        $flags = sfConfig::get("app_flag_team".((sfContext::getInstance()->getUser()->getCulture() != "fr") ? "_en" : ""));
        $aFlags = array();
        $aFlags[""] = "";
        foreach ($flags as $k => $flag) {
            $aFlags[$k] = $flag;
        }

        $this->widgetSchema["team_a_flag"] = new sfWidgetFormSelect(array("choices" => $aFlags, 'default' => 'FR'));
        $this->widgetSchema["team_b_flag"] = new sfWidgetFormSelect(array("choices" => $aFlags, 'default' => 'FR'));

        $this->widgetSchema["max_round"] = new sfWidgetFormSelect(array("choices" => array("15" => "MR15", "12" => "MR12", "9" => "MR9", "5" => "MR5", "3" => "MR3"), "default" => "MR15"));
    }

}
