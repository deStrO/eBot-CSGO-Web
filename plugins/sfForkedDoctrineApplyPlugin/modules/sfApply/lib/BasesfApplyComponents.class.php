<?php
/**
 * sfApply actions.
 *
 * @package    5seven5
 * @subpackage sfApply
 * @author     Tom Boutell, tom@punkave.com
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */


class BasesfApplyComponents extends sfComponents
{
    public function executeLogin()
    {
        $this->loggedIn = $this->getUser()->isAuthenticated();
        if (!$this->loggedIn)
        {
            $class = sfConfig::get('app_sf_guard_plugin_signin_form',
                    'sfGuardFormSignin');
            $this->form = new $class();
            $this->form->getWidgetSchema()->setFormFormatterName('list');
        }
    }
}
