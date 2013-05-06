<?php

/**
 * Matchs form base class.
 *
 * @method Matchs getObject() Returns the current form's model object
 *
 * @package    PhpProject1
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseMatchsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                          => new sfWidgetFormInputHidden(),
      'ip'                          => new sfWidgetFormInputText(),
      'server_id'                   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Server'), 'add_empty' => true)),
      'season_id'                   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Season'), 'add_empty' => true)),
      'team_a'                      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TeamA'), 'add_empty' => true)),
      'team_a_flag'                 => new sfWidgetFormInputText(),
      'team_a_name'                 => new sfWidgetFormInputText(),
      'team_b'                      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TeamB'), 'add_empty' => true)),
      'team_b_flag'                 => new sfWidgetFormInputText(),
      'team_b_name'                 => new sfWidgetFormInputText(),
      'status'                      => new sfWidgetFormInputText(),
      'is_paused'                   => new sfWidgetFormInputCheckbox(),
      'score_a'                     => new sfWidgetFormInputText(),
      'score_b'                     => new sfWidgetFormInputText(),
      'max_round'                   => new sfWidgetFormInputText(),
      'rules'                       => new sfWidgetFormInputText(),
      'overtime_startmoney'         => new sfWidgetFormInputText(),
      'overtime_max_round'          => new sfWidgetFormInputText(),
      'config_full_score'           => new sfWidgetFormInputCheckbox(),
      'config_ot'                   => new sfWidgetFormInputCheckbox(),
      'config_streamer'             => new sfWidgetFormInputCheckbox(),
      'config_knife_round'          => new sfWidgetFormInputCheckbox(),
      'config_switch_auto'          => new sfWidgetFormInputCheckbox(),
      'config_auto_change_password' => new sfWidgetFormInputCheckbox(),
      'config_password'             => new sfWidgetFormInputText(),
      'config_heatmap'              => new sfWidgetFormInputCheckbox(),
      'config_authkey'              => new sfWidgetFormInputText(),
      'enable'                      => new sfWidgetFormInputCheckbox(),
      'map_selection_mode'          => new sfWidgetFormChoice(array('choices' => array('bo2' => 'bo2', 'bo3_modea' => 'bo3_modea', 'bo3_modeb' => 'bo3_modeb', 'normal' => 'normal'))),
      'ingame_enable'               => new sfWidgetFormInputCheckbox(),
      'current_map'                 => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Map'), 'add_empty' => true)),
      'force_zoom_match'            => new sfWidgetFormInputCheckbox(),
      'identifier_id'               => new sfWidgetFormInputText(),
      'startdate'                   => new sfWidgetFormDateTime(),
      'auto_start'                  => new sfWidgetFormInputCheckbox(),
      'auto_start_time'             => new sfWidgetFormInputText(),
      'created_at'                  => new sfWidgetFormDateTime(),
      'updated_at'                  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ip'                          => new sfValidatorPass(array('required' => false)),
      'server_id'                   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Server'), 'required' => false)),
      'season_id'                   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Season'), 'required' => false)),
      'team_a'                      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('TeamA'), 'required' => false)),
      'team_a_flag'                 => new sfValidatorPass(array('required' => false)),
      'team_a_name'                 => new sfValidatorPass(array('required' => false)),
      'team_b'                      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('TeamB'), 'required' => false)),
      'team_b_flag'                 => new sfValidatorPass(array('required' => false)),
      'team_b_name'                 => new sfValidatorPass(array('required' => false)),
      'status'                      => new sfValidatorInteger(array('required' => false)),
      'is_paused'                   => new sfValidatorBoolean(array('required' => false)),
      'score_a'                     => new sfValidatorInteger(array('required' => false)),
      'score_b'                     => new sfValidatorInteger(array('required' => false)),
      'max_round'                   => new sfValidatorInteger(),
      'rules'                       => new sfValidatorPass(),
      'overtime_startmoney'         => new sfValidatorInteger(array('required' => false)),
      'overtime_max_round'          => new sfValidatorInteger(array('required' => false)),
      'config_full_score'           => new sfValidatorBoolean(array('required' => false)),
      'config_ot'                   => new sfValidatorBoolean(array('required' => false)),
      'config_streamer'             => new sfValidatorBoolean(array('required' => false)),
      'config_knife_round'          => new sfValidatorBoolean(array('required' => false)),
      'config_switch_auto'          => new sfValidatorBoolean(array('required' => false)),
      'config_auto_change_password' => new sfValidatorBoolean(array('required' => false)),
      'config_password'             => new sfValidatorPass(array('required' => false)),
      'config_heatmap'              => new sfValidatorBoolean(array('required' => false)),
      'config_authkey'              => new sfValidatorPass(array('required' => false)),
      'enable'                      => new sfValidatorBoolean(array('required' => false)),
      'map_selection_mode'          => new sfValidatorChoice(array('choices' => array(0 => 'bo2', 1 => 'bo3_modea', 2 => 'bo3_modeb', 3 => 'normal'), 'required' => false)),
      'ingame_enable'               => new sfValidatorBoolean(array('required' => false)),
      'current_map'                 => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Map'), 'required' => false)),
      'force_zoom_match'            => new sfValidatorBoolean(array('required' => false)),
      'identifier_id'               => new sfValidatorInteger(array('required' => false)),
      'startdate'                   => new sfValidatorDateTime(array('required' => false)),
      'auto_start'                  => new sfValidatorBoolean(array('required' => false)),
      'auto_start_time'             => new sfValidatorInteger(array('required' => false)),
      'created_at'                  => new sfValidatorDateTime(),
      'updated_at'                  => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('matchs[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Matchs';
  }

}
