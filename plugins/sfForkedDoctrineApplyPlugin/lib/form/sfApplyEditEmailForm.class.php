<?php

class sfApplyEditEmailForm extends sfForm
{
  public function configure()
  {
    $this->setWidget( 'email', new sfWidgetFormInputText() );
    $this->setValidator('email', new sfValidatorAnd( array(
            new sfValidatorEmail( array('required' => true, 'trim' => true) ),
            new sfValidatorString( array('required' => true, 'max_length' => 255) ),
            new sfValidatorApplyEditMail(
                    array( 'id' => sfContext::getInstance()->getUser()->getGuardUser()->getId() ),
                    array('invalid' => 'An account with that email address already exists. If you have forgotten your password, click "cancel", then "Reset My Password."') )
        )));

    $this->widgetSchema->setNameFormat('sfApplyEditEmail[%s]');
    $this->widgetSchema->setFormFormatterName('list');
  }

  public function getStylesheets()
  {
    return array( '/sfForkedDoctrineApplyPlugin/css/forked' => 'all' );
  }
}

