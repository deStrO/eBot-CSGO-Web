<?php

/**
 * User permission reference model.
 *
 * @package    sfDoctrineGuardPlugin
 * @subpackage model
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: PluginsfGuardUserPermission.class.php 25546 2009-12-17 23:27:55Z Jonathan.Wage $
 */
abstract class PluginsfGuardUserPermission extends BasesfGuardUserPermission
{
  public function postSave($event)
  {
    parent::postSave($event);
    $this->getUser()->reloadGroupsAndPermissions();
  }
}
