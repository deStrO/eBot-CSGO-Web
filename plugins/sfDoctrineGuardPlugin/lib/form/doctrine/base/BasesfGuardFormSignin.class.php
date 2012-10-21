<?php

/**
 * BasesfGuardFormSignin
 *
 * @package    sfDoctrineGuardPlugin
 * @subpackage form
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 * @version    SVN: $Id: BasesfGuardFormSignin.class.php 25546 2009-12-17 23:27:55Z Jonathan.Wage $
 */
class BasesfGuardFormSignin extends BaseForm
{
  /**
   * @see sfForm
   */
  public function setup()
  {
    $this->setWidgets(array(
      'username' => new sfWidgetFormInputText(),
      'password' => new sfWidgetFormInputPassword(array('type' => 'password')),
      'remember' => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'username' => new sfValidatorString(),
      'password' => new sfValidatorString(),
      'remember' => new sfValidatorBoolean(),
    ));

    if (sfConfig::get('app_sf_guard_plugin_allow_login_with_email', true))
    {
      $this->widgetSchema['username']->setLabel('Username or E-Mail');
    }

    $this->validatorSchema->setPostValidator(new sfGuardValidatorUser());

    $this->widgetSchema->setNameFormat('signin[%s]');
  }
}