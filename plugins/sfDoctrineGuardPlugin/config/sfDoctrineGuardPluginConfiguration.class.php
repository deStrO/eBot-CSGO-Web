<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfDoctrineGuardPlugin configuration.
 * 
 * @package    sfDoctrineGuardPlugin
 * @subpackage config
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 * @version    SVN: $Id: sfDoctrineGuardPluginConfiguration.class.php 25546 2009-12-17 23:27:55Z Jonathan.Wage $
 */
class sfDoctrineGuardPluginConfiguration extends sfPluginConfiguration
{
  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    if (sfConfig::get('app_sf_guard_plugin_routes_register', true) && in_array('sfGuardAuth', sfConfig::get('sf_enabled_modules', array())))
    {
      $this->dispatcher->connect('routing.load_configuration', array('sfGuardRouting', 'listenToRoutingLoadConfigurationEvent'));
    }

    foreach (array('sfGuardUser', 'sfGuardGroup', 'sfGuardPermission', 'sfGuardRegister', 'sfGuardForgotPassword') as $module)
    {
      if (in_array($module, sfConfig::get('sf_enabled_modules', array())))
      {
        $this->dispatcher->connect('routing.load_configuration', array('sfGuardRouting', 'addRouteFor'.str_replace('sfGuard', '', $module)));
      }
    }
  }
}
