<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 *
 * @package    symfony
 * @subpackage plugin
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfGuardSecurityUser.class.php 30264 2010-07-16 16:59:21Z Jonathan.Wage $
 */
class sfGuardSecurityUser extends sfBasicSecurityUser
{
  protected $user = null;

  /**
   * Initializes the sfGuardSecurityUser object.
   *
   * @param sfEventDispatcher $dispatcher The event dispatcher object
   * @param sfStorage $storage The session storage object
   * @param array $options An array of options
   */
  public function initialize(sfEventDispatcher $dispatcher, sfStorage $storage, $options = array())
  {
    parent::initialize($dispatcher, $storage, $options);

    if (!$this->isAuthenticated())
    {
      // remove user if timeout
      $this->getAttributeHolder()->removeNamespace('sfGuardSecurityUser');
      $this->user = null;
    }
  }

  /**
   * Returns the referer uri.
   *
   * @param string $default The default uri to return
   * @return string $referer The referer
   */
  public function getReferer($default)
  {
    $referer = $this->getAttribute('referer', $default);
    $this->getAttributeHolder()->remove('referer');

    return $referer;
  }

  /**
   * Sets the referer.
   *
   * @param string $referer
   */
  public function setReferer($referer)
  {
    if (!$this->hasAttribute('referer'))
    {
      $this->setAttribute('referer', $referer);
    }
  }

  /**
   * Returns whether or not the user has the given credential.
   *
   * @param string $credential The credential name
   * @param boolean $useAnd Whether or not to use an AND condition
   * @return boolean
   */
  public function hasCredential($credential, $useAnd = true)
  {
    if (empty($credential))
    {
      return true;
    }

    if (!$this->getGuardUser())
    {
      return false;
    }

    if ($this->getGuardUser()->getIsSuperAdmin())
    {
      return true;
    }

    return parent::hasCredential($credential, $useAnd);
  }

  /**
   * Returns whether or not the user is a super admin.
   *
   * @return boolean
   */
  public function isSuperAdmin()
  {
    return $this->getGuardUser() ? $this->getGuardUser()->getIsSuperAdmin() : false;
  }

  /**
   * Returns whether or not the user is anonymous.
   *
   * @return boolean
   */
  public function isAnonymous()
  {
    return !$this->isAuthenticated();
  }

  /**
   * Signs in the user on the application.
   *
   * @param sfGuardUser $user The sfGuardUser id
   * @param boolean $remember Whether or not to remember the user
   * @param Doctrine_Connection $con A Doctrine_Connection object
   */
  public function signIn($user, $remember = false, $con = null)
  {
    // signin
    $this->setAttribute('user_id', $user->getId(), 'sfGuardSecurityUser');
    $this->setAuthenticated(true);
    $this->clearCredentials();
    $this->addCredentials($user->getAllPermissionNames());

    // save last login
    $user->setLastLogin(date('Y-m-d H:i:s'));
    $user->save($con);

    // remember?
    if ($remember)
    {
      $expiration_age = sfConfig::get('app_sf_guard_plugin_remember_key_expiration_age', 15 * 24 * 3600);

      // remove old keys
      Doctrine_Core::getTable('sfGuardRememberKey')->createQuery()
        ->delete()
        ->where('created_at < ?', date('Y-m-d H:i:s', time() - $expiration_age))
        ->execute();

      // remove other keys from this user
      Doctrine_Core::getTable('sfGuardRememberKey')->createQuery()
        ->delete()
        ->where('user_id = ?', $user->getId())
        ->execute();

      // generate new keys
      $key = $this->generateRandomKey();

      // save key
      $rk = new sfGuardRememberKey();
      $rk->setRememberKey($key);
      $rk->setUser($user);
      $rk->setIpAddress($_SERVER['REMOTE_ADDR']);
      $rk->save($con);

      // make key as a cookie
      $remember_cookie = sfConfig::get('app_sf_guard_plugin_remember_cookie_name', 'sfRemember');
      sfContext::getInstance()->getResponse()->setCookie($remember_cookie, $key, time() + $expiration_age);
    }
  }

  /**
   * Returns a random generated key.
   *
   * @param int $len The key length
   * @return string
   */
  protected function generateRandomKey($len = 20)
  {
    return base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
  }

  /**
   * Signs out the user.
   *
   */
  public function signOut()
  {
    $this->getAttributeHolder()->removeNamespace('sfGuardSecurityUser');
    $this->user = null;
    $this->clearCredentials();
    $this->setAuthenticated(false);
    $expiration_age = sfConfig::get('app_sf_guard_plugin_remember_key_expiration_age', 15 * 24 * 3600);
    $remember_cookie = sfConfig::get('app_sf_guard_plugin_remember_cookie_name', 'sfRemember');
    sfContext::getInstance()->getResponse()->setCookie($remember_cookie, '', time() - $expiration_age);
  }

  /**
   * Returns the related sfGuardUser.
   *
   * @return sfGuardUser
   */
  public function getGuardUser()
  {
    if (!$this->user && $id = $this->getAttribute('user_id', null, 'sfGuardSecurityUser'))
    {
      $this->user = Doctrine_Core::getTable('sfGuardUser')->find($id);

      if (!$this->user)
      {
        // the user does not exist anymore in the database
        $this->signOut();

        throw new sfException('The user does not exist anymore in the database.');
      }
    }

    return $this->user;
  }

  /**
   * Returns the string representation of the object.
   *
   * @return string
   */
  public function __toString()
  {
    return $this->getGuardUser()->__toString();
  }

  /**
   * Returns the sfGuardUser object's username.
   *
   * @return string
   */
  public function getUsername()
  {
    return $this->getGuardUser()->getUsername();
  }

  /**
   * Returns the name(first and last) of the user
   *
   * @return string
   */
  public function getName()
  {
    return $this->getGuardUser()->getName();
  }

  /**
   * Returns the sfGuardUser object's email.
   *
   * @return string
   */
  public function getEmail()
  {
    return $this->getGuardUser()->getEmail();
  }

  /**
   * Sets the user's password.
   *
   * @param string $password The password
   * @param Doctrine_Collection $con A Doctrine_Connection object
   */
  public function setPassword($password, $con = null)
  {
    $this->getGuardUser()->setPassword($password);
    $this->getGuardUser()->save($con);
  }

  /**
   * Returns whether or not the given password is valid.
   *
   * @return boolean
   */
  public function checkPassword($password)
  {
    return $this->getGuardUser()->checkPassword($password);
  }

  /**
   * Returns whether or not the user belongs to the given group.
   *
   * @param string $name The group name
   * @return boolean
   */
  public function hasGroup($name)
  {
    return $this->getGuardUser() ? $this->getGuardUser()->hasGroup($name) : false;
  }

  /**
   * Returns the user's groups.
   *
   * @return array|Doctrine_Collection
   */
  public function getGroups()
  {
    return $this->getGuardUser() ? $this->getGuardUser()->getGroups() : array();
  }

  /**
   * Returns the user's group names.
   *
   * @return array
   */
  public function getGroupNames()
  {
    return $this->getGuardUser() ? $this->getGuardUser()->getGroupNames() : array();
  }

  /**
   * Returns whether or not the user has the given permission.
   *
   * @param string $name The permission name
   * @return string
   */
  public function hasPermission($name)
  {
    return $this->getGuardUser() ? $this->getGuardUser()->hasPermission($name) : false;
  }

  /**
   * Returns the Doctrine_Collection of single sfGuardPermission objects.
   *
   * @return Doctrine_Collection
   */
  public function getPermissions()
  {
    return $this->getGuardUser()->getPermissions();
  }

  /**
   * Returns the array of permissions names.
   *
   * @return array
   */
  public function getPermissionNames()
  {
    return $this->getGuardUser() ? $this->getGuardUser()->getPermissionNames() : array();
  }

  /**
   * Returns the array of all permissions.
   *
   * @return array
   */
  public function getAllPermissions()
  {
    return $this->getGuardUser() ? $this->getGuardUser()->getAllPermissions() : array();
  }

  /**
   * Returns the array of all permissions names.
   *
   * @return array
   */
  public function getAllPermissionNames()
  {
    return $this->getGuardUser() ? $this->getGuardUser()->getAllPermissionNames() : array();
  }

  /**
   * Returns the related profile object.
   *
   * @return Doctrine_Record
   */
  public function getProfile()
  {
    return $this->getGuardUser() ? $this->getGuardUser()->getProfile() : null;
  }

  /**
   * Adds a group from its name to the current user.
   *
   * @param string $name The group name
   * @param Doctrine_Connection $con A Doctrine_Connection object
   */
  public function addGroupByName($name, $con = null)
  {
    return $this->getGuardUser()->addGroupByName($name, $con);
  }

  /**
   * Adds a permission from its name to the current user.
   *
   * @param string $name The permission name
   * @param Doctrine_Connection $con A Doctrine_Connection object
   */
  public function addPermissionByName($name, $con = null)
  {
    return $this->getGuardUser()->addPermissionByName($name, $con);
  }
}
