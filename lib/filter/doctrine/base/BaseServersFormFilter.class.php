<?php

/**
 * Servers filter form base class.
 *
 * @package    PhpProject1
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseServersFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ip'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'rcon'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'hostname'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'tv_ip'      => new sfWidgetFormFilterInput(),
      'created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'ip'         => new sfValidatorPass(array('required' => false)),
      'rcon'       => new sfValidatorPass(array('required' => false)),
      'hostname'   => new sfValidatorPass(array('required' => false)),
      'tv_ip'      => new sfValidatorPass(array('required' => false)),
      'created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('servers_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Servers';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'ip'         => 'Text',
      'rcon'       => 'Text',
      'hostname'   => 'Text',
      'tv_ip'      => 'Text',
      'created_at' => 'Date',
      'updated_at' => 'Date',
    );
  }
}
