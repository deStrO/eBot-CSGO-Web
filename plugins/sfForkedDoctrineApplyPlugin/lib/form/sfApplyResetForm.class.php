<?php

class sfApplyResetForm extends sfForm
{
  public function configure()
  {
    $this->setWidget('password', new sfWidgetFormInputPassword(
        array(), array('maxlength' => 128)));
    $this->setWidget('password2', new sfWidgetFormInputPassword(
        array(), array('maxlength' => 128)));

    $this->widgetSchema->setLabels( array(
      'password' => 'Choose NEW Password',
      'password2' => 'Confirm NEW Password'));
    
    $this->widgetSchema->setNameFormat('sfApplyReset[%s]');
    $this->widgetSchema->setFormFormatterName('list');

    $this->setValidator('password', new sfValidatorApplyPassword() );
    $this->setValidator('password2', new sfValidatorApplyPassword() );

    $this->validatorSchema->setPostValidator( 
            new sfValidatorSchemaCompare( 'password', sfValidatorSchemaCompare::EQUAL,
                    'password2', array(), array('invalid' => 'The passwords did not match.')));
  }

  public function getStylesheets()
  {
    return array( '/sfForkedDoctrineApplyPlugin/css/forked' => 'all' );
  }
}

