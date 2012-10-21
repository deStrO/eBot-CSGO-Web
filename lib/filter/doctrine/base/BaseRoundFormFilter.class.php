<?php

/**
 * Round filter form base class.
 *
 * @package    PhpProject1
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseRoundFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'match_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Match'), 'add_empty' => true)),
      'map_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Map'), 'add_empty' => true)),
      'event_name' => new sfWidgetFormFilterInput(),
      'event_time' => new sfWidgetFormFilterInput(),
      'killer_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Killer'), 'add_empty' => true)),
      'killed_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Killed'), 'add_empty' => true)),
      'round_id'   => new sfWidgetFormFilterInput(),
      'created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'match_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Match'), 'column' => 'id')),
      'map_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Map'), 'column' => 'id')),
      'event_name' => new sfValidatorPass(array('required' => false)),
      'event_time' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'killer_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Killer'), 'column' => 'id')),
      'killed_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Killed'), 'column' => 'id')),
      'round_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('round_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Round';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'match_id'   => 'ForeignKey',
      'map_id'     => 'ForeignKey',
      'event_name' => 'Text',
      'event_time' => 'Number',
      'killer_id'  => 'ForeignKey',
      'killed_id'  => 'ForeignKey',
      'round_id'   => 'Number',
      'created_at' => 'Date',
      'updated_at' => 'Date',
    );
  }
}
