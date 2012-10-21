<?php

/**
 * BasesfGuardUserAdminForm
 *
 * @package    sfDoctrineGuardPlugin
 * @subpackage form
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: BasesfGuardUserAdminForm.class.php 25546 2009-12-17 23:27:55Z Jonathan.Wage $
 */
class BasesfGuardUserAdminForm extends BasesfGuardUserForm
{
  /**
   * @see sfForm
   */
  public function setup()
  {
    parent::setup();

    unset(
      $this['last_login'],
      $this['created_at'],
      $this['updated_at'],
      $this['salt'],
      $this['algorithm']
    );

    $this->widgetSchema['groups_list']->setLabel('Groups');
    $this->widgetSchema['permissions_list']->setLabel('Permissions');

    $this->widgetSchema['password'] = new sfWidgetFormInputPassword();
    $this->validatorSchema['password']->setOption('required', false);
    $this->widgetSchema['password_again'] = new sfWidgetFormInputPassword();
    $this->validatorSchema['password_again'] = clone $this->validatorSchema['password'];

    $this->widgetSchema->moveField('password_again', 'after', 'password');

    $this->mergePostValidator(new sfValidatorSchemaCompare('password', sfValidatorSchemaCompare::EQUAL, 'password_again', array(), array('invalid' => 'The two passwords must be the same.')));
  }
}