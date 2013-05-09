<?php
/**
 * sfForkedDoctrineApply configuration class. Adds listener for the form.post_configure event
 * @author: Grzegorz Śliwinski
 */
class sfForkedDoctrineApplyPluginConfiguration extends sfPluginConfiguration
{
   
  public function initialize()
  {
    //We're adding listener for the routing.load_configuration event if there's sfApply.
    if( in_array('sfApply', sfConfig::get('sf_enabled_modules', array())))
    {
      $this->dispatcher->connect('routing.load_configuration', array($this, 'listenToRoutingLoadConfigurationEvent'));
    }
  }


  /**
   * Listens to the routing.load_configuration event.
   *
   * @param sfEvent An sfEvent instance
   * @static
   */
  public function listenToRoutingLoadConfigurationEvent(sfEvent $event)
  {
    $r = $event->getSubject();
    $routesUri = sfConfig::get( 'app_sfForkedApply_routes' );
    // preprend our routes to routing
    $r->prependRoute('apply',
        new sfRoute( $routesUri['apply'] , array('module' => 'sfApply', 'action' => 'apply')));
    $r->prependRoute('reset',
        new sfRoute( $routesUri['reset'], array('module' => 'sfApply', 'action' => 'reset')));
    $r->prependRoute('resetRequest',
        new sfRoute($routesUri['resetRequest'], array('module' => 'sfApply', 'action' => 'resetRequest')));
    $r->prependRoute('resetCancel',
        new sfRoute($routesUri['resetCancel'], array('module' => 'sfApply', 'action' => 'resetCancel')));
    $r->prependRoute('validate',
        new sfRoute($routesUri['validate'], array('module' => 'sfApply', 'action' => 'confirm')));
    $r->prependRoute('settings',
        new sfRoute($routesUri['settings'], array('module' => 'sfApply', 'action' => 'settings')));
    //we're adding email route, if defined
    if( sfConfig::get( 'app_sfForkedApply_mail_editable' ))
    {
      $r->prependRoute('editEmail',
          new sfRoute( $routesUri['settings'].'/email', array('module' => 'sfApply', 'action' => 'editEmail')));
    }

  }
}