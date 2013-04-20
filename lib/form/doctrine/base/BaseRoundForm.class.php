<?php

/**
 * Round form base class.
 *
 * @method Round getObject() Returns the current form's model object
 *
 * @package    PhpProject1
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseRoundForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'match_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Match'), 'add_empty' => false)),
      'map_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Map'), 'add_empty' => false)),
      'event_name' => new sfWidgetFormInputText(),
      'event_text' => new sfWidgetFormInputText(),
      'event_time' => new sfWidgetFormInputText(),
      'kill_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Kill'), 'add_empty' => true)),
      'round_id'   => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'match_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Match'))),
      'map_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Map'))),
      'event_name' => new sfValidatorPass(array('required' => false)),
      'event_text' => new sfValidatorPass(array('required' => false)),
      'event_time' => new sfValidatorInteger(array('required' => false)),
      'kill_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Kill'), 'required' => false)),
      'round_id'   => new sfValidatorInteger(array('required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('round[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Round';
  }

}
