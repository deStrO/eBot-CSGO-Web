<?php

/**
 * Maps filter form base class.
 *
 * @package    PhpProject1
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseMapsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'match_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Match'), 'add_empty' => true)),
      'map_name'       => new sfWidgetFormFilterInput(),
      'score_1'        => new sfWidgetFormFilterInput(),
      'score_2'        => new sfWidgetFormFilterInput(),
      'current_side'   => new sfWidgetFormChoice(array('choices' => array('' => '', 'ct' => 'ct', 't' => 't'))),
      'status'         => new sfWidgetFormFilterInput(),
      'maps_for'       => new sfWidgetFormChoice(array('choices' => array('' => '', 'default' => 'default', 'team1' => 'team1', 'team2' => 'team2'))),
      'nb_ot'          => new sfWidgetFormFilterInput(),
      'identifier_id'  => new sfWidgetFormFilterInput(),
      'tv_record_file' => new sfWidgetFormFilterInput(),
      'created_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'match_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Match'), 'column' => 'id')),
      'map_name'       => new sfValidatorPass(array('required' => false)),
      'score_1'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'score_2'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'current_side'   => new sfValidatorChoice(array('required' => false, 'choices' => array('ct' => 'ct', 't' => 't'))),
      'status'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'maps_for'       => new sfValidatorChoice(array('required' => false, 'choices' => array('default' => 'default', 'team1' => 'team1', 'team2' => 'team2'))),
      'nb_ot'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'identifier_id'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'tv_record_file' => new sfValidatorPass(array('required' => false)),
      'created_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('maps_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Maps';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'match_id'       => 'ForeignKey',
      'map_name'       => 'Text',
      'score_1'        => 'Number',
      'score_2'        => 'Number',
      'current_side'   => 'Enum',
      'status'         => 'Number',
      'maps_for'       => 'Enum',
      'nb_ot'          => 'Number',
      'identifier_id'  => 'Number',
      'tv_record_file' => 'Text',
      'created_at'     => 'Date',
      'updated_at'     => 'Date',
    );
  }
}
