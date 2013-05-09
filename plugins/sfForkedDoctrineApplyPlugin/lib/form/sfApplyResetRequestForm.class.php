<?php

class sfApplyResetRequestForm extends sfForm
{
    public function configure()
    {
        parent::configure();

        $this->setWidget('username_or_email',
          new sfWidgetFormInput( array(), array('maxlength' => 100)));

        $this->setValidator('username_or_email',
          new sfValidatorOr( array(
              new sfValidatorAnd( array(
                  new sfValidatorString( array(
                      'required' => true,
                      'trim' => true,
                      'min_length' => 4,
                      'max_length' => 16 ) ),
                  new sfValidatorDoctrineChoice( array(
                      'model' => 'sfGuardUser',
                      'column' => 'username' ),
                          array( "invalid" => "There is no such user.")))),
              new sfValidatorEmail(array('required' => true) ) )
        ));
        
        $this->widgetSchema->setNameFormat('sfApplyResetRequest[%s]');
        $this->widgetSchema->setFormFormatterName('list');

        //Include captcha if enabled
        if ($this->isCaptchaEnabled() )
        {
            $this->addCaptcha();
        }
    }

    public function isCaptchaEnabled()
    {
        return sfConfig::get('app_recaptcha_enabled');
    }

    public function addCaptcha()
    {
        $this->widgetSchema['captcha'] = new sfWidgetFormReCaptcha(array(
          'public_key' => sfConfig::get('app_recaptcha_public_key')
        ));

        $this->validatorSchema['captcha'] = new sfValidatorReCaptcha(array(
          'private_key' => sfConfig::get('app_recaptcha_private_key')
        ));
        $this->validatorSchema['captcha']
            ->setMessage('captcha', sfContext::getInstance()->getI18N()->
                __('The captcha is not valid (%error%).', array(), 'sfForkedApply'))
            ->setMessage('server_problem', sfContext::getInstance()->getI18N()->
                __('Unable to check the captcha from the server (%error%).', array(), 'sfForkedApply'));
    }
    
    public function getStylesheets()
    {
        return array( '/sfForkedDoctrineApplyPlugin/css/forked' => 'all' );
    }
}

