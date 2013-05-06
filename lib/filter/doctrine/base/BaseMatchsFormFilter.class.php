<?php

/**
 * Matchs filter form base class.
 *
 * @package    PhpProject1
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseMatchsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ip'                          => new sfWidgetFormFilterInput(),
      'server_id'                   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Server'), 'add_empty' => true)),
      'season_id'                   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Season'), 'add_empty' => true)),
      'team_a'                      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TeamA'), 'add_empty' => true)),
      'team_a_flag'                 => new sfWidgetFormFilterInput(),
      'team_a_name'                 => new sfWidgetFormFilterInput(),
      'team_b'                      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TeamB'), 'add_empty' => true)),
      'team_b_flag'                 => new sfWidgetFormFilterInput(),
      'team_b_name'                 => new sfWidgetFormFilterInput(),
      'status'                      => new sfWidgetFormFilterInput(),
      'is_paused'                   => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'score_a'                     => new sfWidgetFormFilterInput(),
      'score_b'                     => new sfWidgetFormFilterInput(),
      'max_round'                   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'rules'                       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'overtime_startmoney'         => new sfWidgetFormFilterInput(),
      'overtime_max_round'          => new sfWidgetFormFilterInput(),
      'config_full_score'           => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'config_ot'                   => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'config_streamer'             => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'config_knife_round'          => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'config_switch_auto'          => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'config_auto_change_password' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'config_password'             => new sfWidgetFormFilterInput(),
      'config_heatmap'              => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'config_authkey'              => new sfWidgetFormFilterInput(),
      'enable'                      => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'map_selection_mode'          => new sfWidgetFormChoice(array('choices' => array('' => '', 'bo2' => 'bo2', 'bo3_modea' => 'bo3_modea', 'bo3_modeb' => 'bo3_modeb', 'normal' => 'normal'))),
      'ingame_enable'               => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'current_map'                 => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Map'), 'add_empty' => true)),
      'force_zoom_match'            => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'identifier_id'               => new sfWidgetFormFilterInput(),
      'startdate'                   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'auto_start'                  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'auto_start_time'             => new sfWidgetFormFilterInput(),
      'created_at'                  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'                  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'ip'                          => new sfValidatorPass(array('required' => false)),
      'server_id'                   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Server'), 'column' => 'id')),
      'season_id'                   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Season'), 'column' => 'id')),
      'team_a'                      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('TeamA'), 'column' => 'id')),
      'team_a_flag'                 => new sfValidatorPass(array('required' => false)),
      'team_a_name'                 => new sfValidatorPass(array('required' => false)),
      'team_b'                      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('TeamB'), 'column' => 'id')),
      'team_b_flag'                 => new sfValidatorPass(array('required' => false)),
      'team_b_name'                 => new sfValidatorPass(array('required' => false)),
      'status'                      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_paused'                   => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'score_a'                     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'score_b'                     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'max_round'                   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rules'                       => new sfValidatorPass(array('required' => false)),
      'overtime_startmoney'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'overtime_max_round'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'config_full_score'           => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'config_ot'                   => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'config_streamer'             => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'config_knife_round'          => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'config_switch_auto'          => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'config_auto_change_password' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'config_password'             => new sfValidatorPass(array('required' => false)),
      'config_heatmap'              => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'config_authkey'              => new sfValidatorPass(array('required' => false)),
      'enable'                      => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'map_selection_mode'          => new sfValidatorChoice(array('required' => false, 'choices' => array('bo2' => 'bo2', 'bo3_modea' => 'bo3_modea', 'bo3_modeb' => 'bo3_modeb', 'normal' => 'normal'))),
      'ingame_enable'               => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'current_map'                 => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Map'), 'column' => 'id')),
      'force_zoom_match'            => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'identifier_id'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'startdate'                   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'auto_start'                  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'auto_start_time'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'                  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'                  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('matchs_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Matchs';
  }

  public function getFields()
  {
    return array(
      'id'                          => 'Number',
      'ip'                          => 'Text',
      'server_id'                   => 'ForeignKey',
      'season_id'                   => 'ForeignKey',
      'team_a'                      => 'ForeignKey',
      'team_a_flag'                 => 'Text',
      'team_a_name'                 => 'Text',
      'team_b'                      => 'ForeignKey',
      'team_b_flag'                 => 'Text',
      'team_b_name'                 => 'Text',
      'status'                      => 'Number',
      'is_paused'                   => 'Boolean',
      'score_a'                     => 'Number',
      'score_b'                     => 'Number',
      'max_round'                   => 'Number',
      'rules'                       => 'Text',
      'overtime_startmoney'         => 'Number',
      'overtime_max_round'          => 'Number',
      'config_full_score'           => 'Boolean',
      'config_ot'                   => 'Boolean',
      'config_streamer'             => 'Boolean',
      'config_knife_round'          => 'Boolean',
      'config_switch_auto'          => 'Boolean',
      'config_auto_change_password' => 'Boolean',
      'config_password'             => 'Text',
      'config_heatmap'              => 'Boolean',
      'config_authkey'              => 'Text',
      'enable'                      => 'Boolean',
      'map_selection_mode'          => 'Enum',
      'ingame_enable'               => 'Boolean',
      'current_map'                 => 'ForeignKey',
      'force_zoom_match'            => 'Boolean',
      'identifier_id'               => 'Number',
      'startdate'                   => 'Date',
      'auto_start'                  => 'Boolean',
      'auto_start_time'             => 'Number',
      'created_at'                  => 'Date',
      'updated_at'                  => 'Date',
    );
  }
}
