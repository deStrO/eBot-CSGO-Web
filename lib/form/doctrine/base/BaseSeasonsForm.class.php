<?php

/**
 * Seasons form base class.
 *
 * @method Seasons getObject() Returns the current form's model object
 *
 * @package    PhpProject1
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseSeasonsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'name'       => new sfWidgetFormInputText(),
      'event'      => new sfWidgetFormInputText(),
      'start'      => new sfWidgetFormDateTime(),
      'end'        => new sfWidgetFormDateTime(),
      'link'       => new sfWidgetFormInputText(),
      'logo'       => new sfWidgetFormInputText(),
      'active'     => new sfWidgetFormInputCheckbox(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'       => new sfValidatorPass(),
      'event'      => new sfValidatorPass(),
      'start'      => new sfValidatorDateTime(),
      'end'        => new sfValidatorDateTime(),
      'link'       => new sfValidatorPass(array('required' => false)),
      'logo'       => new sfValidatorPass(array('required' => false)),
      'active'     => new sfValidatorBoolean(array('required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('seasons[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Seasons';
  }

}
