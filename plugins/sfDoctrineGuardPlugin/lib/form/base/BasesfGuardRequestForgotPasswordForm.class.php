<?php

/**
 * BasesfGuardRequestForgotPasswordForm for requesting a forgot password email
 *
 * @package    sfDoctrineGuardPlugin
 * @subpackage form
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 * @version    SVN: $Id: BasesfGuardRequestForgotPasswordForm.class.php 23536 2009-11-02 21:41:21Z Kris.Wallsmith $
 */
class BasesfGuardRequestForgotPasswordForm extends BaseForm
{
  public function setup()
  {
    $this->widgetSchema['email_address'] = new sfWidgetFormInput();
    $this->validatorSchema['email_address'] = new sfValidatorString();

    $this->widgetSchema->setNameFormat('forgot_password[%s]');
  }

  public function isValid()
  {
    $valid = parent::isValid();
    if ($valid)
    {
      $values = $this->getValues();
      $this->user = Doctrine_Core::getTable('sfGuardUser')
        ->createQuery('u')
        ->where('u.email_address = ?', $values['email_address'])
        ->fetchOne();

      if ($this->user)
      {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }
}