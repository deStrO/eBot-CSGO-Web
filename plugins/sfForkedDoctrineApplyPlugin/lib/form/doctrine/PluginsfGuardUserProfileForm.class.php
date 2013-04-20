<?php

/**
 * PluginsfGuardUserProfile form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginsfGuardUserProfileForm extends BasesfGuardUserProfileForm
{
    public function setup()
    {
        parent::setup();

        //unset type field if it's set.
        if( isset( $this['type'] ) )
        {
           unset( $this['type'] );
        }
    }

    protected function isCaptchaEnabled()
    {
        return sfConfig::get('app_recaptcha_enabled');
    }

    protected function addCaptcha()
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
