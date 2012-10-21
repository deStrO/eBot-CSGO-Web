<?php

/**
 * PluginsfGuardGroup form.
 *
 * @package    sfDoctrineGuardPlugin
 * @subpackage form
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: PluginsfGuardGroupForm.class.php 24629 2009-12-01 00:34:36Z Jonathan.Wage $
 */
abstract class PluginsfGuardGroupForm extends BasesfGuardGroupForm
{
  /**
   * @see sfForm
   */
  public function setupInheritance()
  {
    parent::setupInheritance();

    unset(
      $this['created_at'],
      $this['updated_at']
    );

    $this->widgetSchema['users_list']->setLabel('Users');
    $this->widgetSchema['permissions_list']->setLabel('Permissions');
  }
}