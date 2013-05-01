<?php

/**
 * MapsScore filter form base class.
 *
 * @package    PhpProject1
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseMapsScoreFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'map_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Map'), 'add_empty' => true)),
      'type_score'   => new sfWidgetFormChoice(array('choices' => array('' => '', 'normal' => 'normal', 'ot' => 'ot'))),
      'score1_side1' => new sfWidgetFormFilterInput(),
      'score1_side2' => new sfWidgetFormFilterInput(),
      'score2_side1' => new sfWidgetFormFilterInput(),
      'score2_side2' => new sfWidgetFormFilterInput(),
      'created_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'map_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Map'), 'column' => 'id')),
      'type_score'   => new sfValidatorChoice(array('required' => false, 'choices' => array('normal' => 'normal', 'ot' => 'ot'))),
      'score1_side1' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'score1_side2' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'score2_side1' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'score2_side2' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('maps_score_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'MapsScore';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'map_id'       => 'ForeignKey',
      'type_score'   => 'Enum',
      'score1_side1' => 'Number',
      'score1_side2' => 'Number',
      'score2_side1' => 'Number',
      'score2_side2' => 'Number',
      'created_at'   => 'Date',
      'updated_at'   => 'Date',
    );
  }
}
