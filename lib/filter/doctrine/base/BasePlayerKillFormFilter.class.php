<?php

/**
 * PlayerKill filter form base class.
 *
 * @package    PhpProject1
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePlayerKillFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'match_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Match'), 'add_empty' => true)),
      'map_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Map'), 'add_empty' => true)),
      'killer_name' => new sfWidgetFormFilterInput(),
      'killer_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Killer'), 'add_empty' => true)),
      'killer_team' => new sfWidgetFormFilterInput(),
      'killed_name' => new sfWidgetFormFilterInput(),
      'killed_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Killed'), 'add_empty' => true)),
      'killed_team' => new sfWidgetFormFilterInput(),
      'weapon'      => new sfWidgetFormFilterInput(),
      'headshot'    => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'round_id'    => new sfWidgetFormFilterInput(),
      'created_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'match_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Match'), 'column' => 'id')),
      'map_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Map'), 'column' => 'id')),
      'killer_name' => new sfValidatorPass(array('required' => false)),
      'killer_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Killer'), 'column' => 'id')),
      'killer_team' => new sfValidatorPass(array('required' => false)),
      'killed_name' => new sfValidatorPass(array('required' => false)),
      'killed_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Killed'), 'column' => 'id')),
      'killed_team' => new sfValidatorPass(array('required' => false)),
      'weapon'      => new sfValidatorPass(array('required' => false)),
      'headshot'    => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'round_id'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('player_kill_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PlayerKill';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'match_id'    => 'ForeignKey',
      'map_id'      => 'ForeignKey',
      'killer_name' => 'Text',
      'killer_id'   => 'ForeignKey',
      'killer_team' => 'Text',
      'killed_name' => 'Text',
      'killed_id'   => 'ForeignKey',
      'killed_team' => 'Text',
      'weapon'      => 'Text',
      'headshot'    => 'Boolean',
      'round_id'    => 'Number',
      'created_at'  => 'Date',
      'updated_at'  => 'Date',
    );
  }
}
