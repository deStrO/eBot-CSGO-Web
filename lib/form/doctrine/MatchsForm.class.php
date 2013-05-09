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
        unset($this["config_authkey"], $this["ip"], $this["identifier_id"], $this["force_zoom_match"], $this["config_switch_auto"], $this["server_id"], $this["ingame_enable"], $this["config_auto_change_password"], $this["created_at"], $this["updated_at"], $this["current_map"], $this["status"], $this["score_a"], $this["score_b"], $this["config_heatmap"], $this["enable"], $this["is_paused"]);

        $password = array('frosch', 'gehen', 'rennen', 'gucken', 'fliegen', 'rasen', 'snobb', 'peter', 'wackel', 'dackel', 'gut', 'schlecht', 'win', 'loss', 'tragen',
              'weg', 'berlin', 'aachen', 'mensch', 'tier', 'turtle', 'adler', 'raupe', 'rauben', 'bank', 'schalter', 'ticket', 'bahn', 'zug', 'delay', 'flugzeug', 'ratte',
              'nager', 'hase', 'feld', 'gras', 'kraut', 'gurke', 'apfel', 'salat', 'tomate', 'dressing', 'essig', 'zwiebel', 'kuchen', 'zucker', 'salz', 'kaffee', 'tee',
              'monday', 'tuesday', 'wednesday', 'friday', 'weekend', 'holiday', 'doctor', 'game', 'cup', 'death', 'player', 'monitor', 'hand', 'food', 'paper', 'windows', 'together');

        $password = $password[rand(0, count($password)-1)];

        $this->widgetSchema["max_round"] = new sfWidgetFormSelect(array("choices" => array("15" => "MR15", "12" => "MR12", "9" => "MR9", "5" => "MR5", "3" => "MR3"), "default" => "MR15"));
        $this->widgetSchema["overtime_max_round"] = new sfWidgetFormSelect(array("choices" => array("5" => "MR5", "3" => "MR3"), "default" => "MR5"));
        $this->widgetSchema["auto_start_time"] = new sfWidgetFormSelect(array("choices" => array("5" => "05 Minutes Before Startdate", "10" => "10 Minutes Before Startdate", "15" => "15 Minutes Before Startdate", "30" => "30 Minutes Before Startdate")));
        $this->widgetSchema["startdate"] = new sfWidgetFormInputText(array("default" => date("d.m.Y H:i")), array("id" => "match_startdate", "style" => "width:180px;"));

        $query = Doctrine_Core::getTable('Seasons')->createQuery()->where('active = ?', '1');
        $this->widgetSchema['season_id']->setOption('query', $query);

        $this->widgetSchema["team_a"]->setLabel("Team A");
        $this->widgetSchema["team_b"]->setLabel("Team B");

        $this->widgetSchema["max_round"]->setLabel("Max Rounds (MR)");

        $this->widgetSchema["config_ot"]->setLabel("OverTime");
        $this->widgetSchema["config_streamer"]->setLabel("Streamer Ready");
        $this->widgetSchema["config_knife_round"]->setLabel("Knife Round");
        $this->widgetSchema["config_knife_round"]->setDefault(true);
        $this->widgetSchema["config_full_score"]->setLabel("Play all Rounds");
        $this->widgetSchema["config_password"]->setLabel("Password");
        $this->widgetSchema["config_password"]->setDefault($password);
        $this->widgetSchema["map_selection_mode"]->setDefault("normal");
        $this->widgetSchema["rules"]->setDefault("eps");

        $this->widgetSchema["overtime_startmoney"]->setLabel("Overtime: Startmoney");
        $this->widgetSchema["overtime_startmoney"]->setDefault("16000");
        $this->widgetSchema["overtime_max_round"]->setLabel("Overtime: Max Rounds");

        $this->widgetSchema['team_a']->addOption('method', 'getNameFlag');
        $this->widgetSchema['team_a']->addOption('order_by',array('name','asc'));
        $this->widgetSchema['team_b']->addOption('method', 'getNameFlag');
        $this->widgetSchema['team_b']->addOption('order_by',array('name','asc'));

        $this->widgetSchema["auto_start"]->setLabel("Autostart Match");
        $this->widgetSchema["auto_start_time"]->setLabel("Start Match");

        $flags = sfConfig::get("app_flag_team_en");
        $aFlags = array();
        $aFlags[""] = "";
        foreach ($flags as $k => $flag) {
            $aFlags[$k] = $flag;
        }
        $this->widgetSchema["team_a_flag"] = new sfWidgetFormSelect(array("choices" => $aFlags));
        $this->widgetSchema["team_b_flag"] = new sfWidgetFormSelect(array("choices" => $aFlags));

        $this->widgetSchema["map_selection_mode"]->addOption('choices', array('normal' => 'BO1', 'bo3_modeb' => 'BO3'));

        // MOVING FIELDS

        $this->getWidgetSchema()->moveField('config_password', sfWidgetFormSchema::AFTER, 'rules');
        $this->getWidgetSchema()->moveField('max_round', sfWidgetFormSchema::AFTER, 'config_password');

        $this->getWidgetSchema()->moveField('config_ot', sfWidgetFormSchema::AFTER, 'config_knife_round');
        $this->getWidgetSchema()->moveField('auto_start', sfWidgetFormSchema::AFTER, 'config_ot');

        $this->getWidgetSchema()->moveField('overtime_startmoney', sfWidgetFormSchema::AFTER, 'config_ot');
        $this->getWidgetSchema()->moveField('overtime_max_round', sfWidgetFormSchema::AFTER, 'overtime_startmoney');
        $this->getWidgetSchema()->moveField('startdate', sfWidgetFormSchema::AFTER, 'auto_start');
        $this->getWidgetSchema()->moveField('auto_start_time', sfWidgetFormSchema::AFTER, 'startdate');

    }

}