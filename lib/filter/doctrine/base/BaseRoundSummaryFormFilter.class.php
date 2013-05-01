<?php

/**
 * RoundSummary filter form base class.
 *
 * @package    PhpProject1
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseRoundSummaryFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'match_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Match'), 'add_empty' => true)),
      'map_id'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Map'), 'add_empty' => true)),
      'bomb_planted'      => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'bomb_defused'      => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'bomb_exploded'     => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'win_type'          => new sfWidgetFormChoice(array('choices' => array('' => '', 'bombdefused' => 'bombdefused', 'bombeexploded' => 'bombeexploded', 'normal' => 'normal', 'saved' => 'saved'))),
      'team_win'          => new sfWidgetFormChoice(array('choices' => array('' => '', 'a' => 'a', 'b' => 'b'))),
      'ct_win'            => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      't_win'             => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'score_a'           => new sfWidgetFormFilterInput(),
      'score_b'           => new sfWidgetFormFilterInput(),
      'best_killer'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('BestKiller'), 'add_empty' => true)),
      'best_killer_nb'    => new sfWidgetFormFilterInput(),
      'best_killer_fk'    => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'best_action_type'  => new sfWidgetFormFilterInput(),
      'best_action_param' => new sfWidgetFormFilterInput(),
      'backup_file_name'  => new sfWidgetFormFilterInput(),
      'round_id'          => new sfWidgetFormFilterInput(),
      'created_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'match_id'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Match'), 'column' => 'id')),
      'map_id'            => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Map'), 'column' => 'id')),
      'bomb_planted'      => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'bomb_defused'      => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'bomb_exploded'     => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'win_type'          => new sfValidatorChoice(array('required' => false, 'choices' => array('bombdefused' => 'bombdefused', 'bombeexploded' => 'bombeexploded', 'normal' => 'normal', 'saved' => 'saved'))),
      'team_win'          => new sfValidatorChoice(array('required' => false, 'choices' => array('a' => 'a', 'b' => 'b'))),
      'ct_win'            => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      't_win'             => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'score_a'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'score_b'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'best_killer'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('BestKiller'), 'column' => 'id')),
      'best_killer_nb'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'best_killer_fk'    => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'best_action_type'  => new sfValidatorPass(array('required' => false)),
      'best_action_param' => new sfValidatorPass(array('required' => false)),
      'backup_file_name'  => new sfValidatorPass(array('required' => false)),
      'round_id'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('round_summary_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'RoundSummary';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'match_id'          => 'ForeignKey',
      'map_id'            => 'ForeignKey',
      'bomb_planted'      => 'Boolean',
      'bomb_defused'      => 'Boolean',
      'bomb_exploded'     => 'Boolean',
      'win_type'          => 'Enum',
      'team_win'          => 'Enum',
      'ct_win'            => 'Boolean',
      't_win'             => 'Boolean',
      'score_a'           => 'Number',
      'score_b'           => 'Number',
      'best_killer'       => 'ForeignKey',
      'best_killer_nb'    => 'Number',
      'best_killer_fk'    => 'Boolean',
      'best_action_type'  => 'Text',
      'best_action_param' => 'Text',
      'backup_file_name'  => 'Text',
      'round_id'          => 'Number',
      'created_at'        => 'Date',
      'updated_at'        => 'Date',
    );
  }
}
