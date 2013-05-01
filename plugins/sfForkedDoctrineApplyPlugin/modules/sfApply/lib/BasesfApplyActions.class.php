<?php
/**
 * sfApplyActionsLibrary is an sfActions based library for sfApplyActions class.
 * Inherit it if you want to override any methods, and still use some of the
 * original functionality.
 *
 * @author fizyk
 */
class BasesfApplyActions extends sfActions
{
  //When user is applying for new account
  public function executeApply(sfRequest $request)
  {
    //If user is logged in, we're forwarding him to settings page from apply
    $this->forwardIf($this->getUser()->isAuthenticated(), 'sfApply', 'settings');

    // we're getting default or customized applyForm for the task
    if( !( ($this->form = $this->newForm( 'applyForm' ) ) instanceof sfGuardUserProfileForm) )
    {
      // if the form isn't instance of sfApplyApplyForm, we don't accept it
      throw new InvalidArgumentException(
          'The custom apply form should be instance of sfApplyApplyForm' );
    }

    //Code below is used when user is sending his application!
    if( $request->isMethod('post') )
    {
      //gathering form request in one array
      $formValues = $request->getParameter( $this->form->getName() );
      if(sfConfig::get('app_recaptcha_enabled') )
      {
        $captcha = array(
          'recaptcha_challenge_field' => $request->getParameter('recaptcha_challenge_field'),
          'recaptcha_response_field'  => $request->getParameter('recaptcha_response_field'),
        );
        //Adding captcha to form array
        $formValues = array_merge( $formValues, array('captcha' => $captcha)  );
      }
        //binding request form parameters with form
      $this->form->bind( $formValues, $request->getFiles( $this->form->getName() ) );
      if ($this->form->isValid())
      {
        $guid = "n" . self::createGuid();
        $this->form->getObject()->setValidate( $guid );
        $date = new DateTime();
        $this->form->getObject()->setValidateAt( $date->format( 'Y-m-d H:i:s' ) );
        $this->form->save();
        $confirmation = sfConfig::get( 'app_sfForkedApply_confirmation' );
        if( $confirmation['apply'] )
        {
          try
          {
            //Extracting object and sending creating verification mail
            $profile = $this->form->getObject();
            $this->sendVerificationMail($profile);
            return 'After';
          }
          catch (Exception $e)
          {
            //Cleaning after possible exception thrown in ::sendVerificationMail() method
            $profile = $this->form->getObject();
            $user = $profile->getUser();
            $user->delete();
            //We rethrow exception for the dev environment. This catch
            //catches other than mailer exception, i18n as well. So developer
            //now knows what he's up to.
            if( sfContext::getInstance()->getConfiguration()->getEnvironment() === 'dev' )
            {
              throw $e;
            }
            return 'MailerError';
          }
        }
        else
        {
          $this->activateUser( $this->form->getObject()->getUser() );
          return $this->redirect( '@homepage' );
        }
      }
    }
  }

  //Processes reset requests
  public function executeResetRequest(sfRequest $request)
  {
    $user = $this->getUser();

    $confirmation = sfConfig::get( 'app_sfForkedApply_confirmation' );
    //if user is authenticated and confirmation for reset for logged users is disabled
    if( $user->isAuthenticated() && !$confirmation['reset_logged'] )
    {
      $this->redirect( 'sfApply/reset' );
    }
    //if user is authenticated and confirmation for reset for logged users is enabled
    elseif( $user->isAuthenticated() && $confirmation['reset_logged'] )
    {
      return $this->resetRequestBody( $this->getUser()->getGuardUser() );
    }
    else
    {
      $this->forward404Unless( $confirmation['reset'] );
      // we're getting default or customized resetRequestForm for the task
      if( !( ($this->form = $this->newForm( 'resetRequestForm'  ) )  instanceof sfApplyResetRequestForm) )
      {
        // if the form isn't instance of sfApplySettingsForm, we don't accept it
        throw new InvalidArgumentException(
            'The custom resetRequest form should be instance of sfApplyResetRequestForm'
            );
      }
      if ($request->isMethod('post'))
      {
        //gathering form request in one array
        $formValues = $request->getParameter( $this->form->getName() );
        if(sfConfig::get('app_recaptcha_enabled') )
        {
          $captcha = array(
            'recaptcha_challenge_field' => $request->getParameter('recaptcha_challenge_field'),
            'recaptcha_response_field'  => $request->getParameter('recaptcha_response_field'),
          );
          //Adding captcha to form array
          $formValues = array_merge( $formValues, array('captcha' => $captcha)  );
        }
        //binding request form parameters with form
        $this->form->bind($formValues);
        if ($this->form->isValid())
        {
          // The form matches unverified users, but retrieveByUsername does not, so
          // use an explicit query. We'll special-case the unverified users in
          // resetRequestBody

          $username_or_email = $this->form->getValue('username_or_email');
          if (strpos($username_or_email, '@') !== false)
          {
            $user = Doctrine::getTable('sfGuardUser')->createQuery('u')->
                    where('u.email_address = ?', $username_or_email)->
                    fetchOne();

          }
          else
          {
            $user = Doctrine::getTable('sfGuardUser')->createQuery('u')->
                    where('u.username = ?', $username_or_email)->fetchOne();
          }
          return $this->resetRequestBody($user);
        }
      }
    }
  }

  public function executeConfirm(sfRequest $request)
  {
    $validate = $this->request->getParameter('validate');
    // 0.6.3: oops, this was in sfGuardUserProfilePeer in my application
    // and therefore never got shipped with the plugin until I built
    // a second site and spotted it!

    // Note that this only works if you set foreignAlias and
    // foreignType correctly
    $sfGuardUser = Doctrine_Query::create()
        ->from("sfGuardUser u")
        ->innerJoin("u.Profile p with p.validate = ?", $validate)
        ->fetchOne();
    if (!$sfGuardUser)
    {
      return 'Invalid';
    }
    $type = self::getValidationType($validate);
    if (!strlen($validate))
    {
      return 'Invalid';
    }
    $profile = $sfGuardUser->getProfile();
    //clearing validate and validate_at fields
    $profile->setValidate( null );
    $profile->setValidateAt( null );
    $profile->save();
    if ($type == 'New')
    {
      $this->activateUser( $sfGuardUser );
    }
    if ($type == 'Reset')
    {
      $this->getUser()->setAttribute('sfApplyReset', $sfGuardUser->getId());
      return $this->redirect('sfApply/reset');
    }
    if( $type == 'Email' )
    {
      if( sfConfig::get( 'app_sfForkedApply_mail_editable' ) )
      {
        $profile->getUser()->setEmailAddress( $profile->getEmailNew() );
        $profile->setEmailNew( null );
        $profile->save();
        $this->getUser()->setFlash( 'sf_forked_apply',
            sfContext::getInstance()->getI18N()->
            __( 'Your email has been changed.',
                array(), 'sfForkedApply' ) );
        return $this->redirect( '@homepage' );
      }
      return 'Invalid';
    }
  }

  public function executeReset(sfRequest $request)
  {
    //won't present this page to users that are not authenticated or haven't got confirmation code
    if( !$this->getUser()->isAuthenticated() && !$this->getUser()->getAttribute('sfApplyReset', false)  )
    {
      $this->redirect( '@sf_guard_signin' );
    }
    // we're getting default or customized resetForm for the task
    if( !( ($this->form = $this->newForm( 'resetForm') ) instanceof sfApplyResetForm) )
    {
      // if the form isn't instance of sfApplyResetForm, we don't accept it
      throw new InvalidArgumentException(
          'The custom reset form should be instance of sfApplyResetForm'
          );
    }
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter( $this->form->getName() ));
      if ($this->form->isValid())
      {
        //This got fixed (0.9.1), so if user is authenticated, and requests password change, we're still getting his id.
        $this->id = ( $this->getUser()->isAuthenticated() ) ? $this->getUser()->getGuardUser()->getId() : $this->getUser()->getAttribute('sfApplyReset', false);
        $this->forward404Unless($this->id);
        $this->sfGuardUser = Doctrine::getTable('sfGuardUser')->find($this->id);
        $this->forward404Unless($this->sfGuardUser);
        $sfGuardUser = $this->sfGuardUser;
        $sfGuardUser->setPassword($this->form->getValue('password'));
        $sfGuardUser->save();
        $this->getUser()->signIn($sfGuardUser);
        $this->getUser()->setAttribute('sfApplyReset', null);
        return 'After';
      }
    }
    if( $this->getUser()->isAuthenticated() )
    {
      return 'Logged';
    }
  }

  public function executeResetCancel()
  {
    $this->getUser()->setAttribute('sfApplyReset', null);
    return $this->redirect(sfConfig::get('app_sfForkedApply_after', sfConfig::get('app_sfApplyPlugin_after', '@homepage')));
  }

  public function executeSettings(sfRequest $request)
  {
    // sfApplySettingsForm inherits from sfApplyApplyForm, which
    // inherits from sfGuardUserProfile. That minimizes the amount
    // of duplication of effort. If you want, you can use a different
    // form class. I suggest inheriting from sfApplySettingsForm and
    // making further changes after calling parent::configure() from
    // your own configure() method.

    $profile = $this->getUser()->getProfile();
    // we're getting default or customized settingsForm for the task
    if( !( ($this->form = $this->newForm( 'settingsForm', $profile) ) instanceof sfGuardUserProfileForm) )
    {
      // if the form isn't instance of sfApplySettingsForm, we don't accept it
      throw new InvalidArgumentException( sfContext::getInstance()->
          getI18N()->
          __( 'The custom %action% form should be instance of %form%',
                  array( '%action%' => 'settings',
                      '%form%' => 'sfApplySettingsForm' ), 'sfForkedApply' )
          );
    }
    if ($request->isMethod('post'))
    {
      $this->form->bind( $request->getParameter( $this->form->getName() ), $request->getFiles($this->form->getName()) );
      if ($this->form->isValid())
      {
        $this->form->save();
        return $this->redirect('@homepage');
      }
    }
  }

  public function executeEditEmail(sfRequest $request)
  {
    $this->forward404Unless( sfConfig::get( 'app_sfForkedApply_mail_editable' ) );
    if( !( ($this->form = $this->newForm( 'editEmailForm') ) instanceof sfApplyEditEmailForm) )
    {
      // if the form isn't instance of sfApplySettingsForm, we don't accept it
      throw new InvalidArgumentException( sfContext::getInstance()->
          getI18N()->
          __( 'The custom %action% form should be instance of %form%',
                  array( '%action%' => 'editEmail',
                      '%form%' => 'sfApplyEditEmailForm' ), 'sfForkedApply' )
          );
    }
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter( $this->form->getName() ));
      if ($this->form->isValid())
      {
        $profile = $this->getUser()->getGuardUser()->getProfile();
        $confirmation = sfConfig::get( 'app_sfForkedApply_confirmation' );
        if( $confirmation['email'] )
        {
          $profile->setEmailNew( $this->form->getValue( 'email' ) );
          $profile->setValidate('e' . self::createGuid());
          $date = new DateTime();
          $profile->setValidateAt( $date->format( 'Y-m-d H:i:s' ) );
          $profile->save();
          $this->mail(array('subject' => sfConfig::get('app_sfForkedApply_apply_subject', 
                  sfConfig::get('app_sfApplyPlugin_apply_subject',
            sfContext::getInstance()->getI18N()->__("Please verify your email on %1%",
                                                    array('%1%' => $this->getRequest()->getHost()), 'sfForkedApply'))),
            'fullname' => $profile->getFullname(),
            'email' => $profile->getUser()->getEmailAddress(),
            'parameters' => array('username' => $profile->getUser()->getUsername(),
                                  'validate' => $profile->getValidate(),
                                  'oldmail' => $profile->getUser()->getEmailAddress(),
                                  'newmail' => $profile->getEmailNew() ),
            'text' => 'sfApply/sendValidateEmailText',
            'html' => 'sfApply/sendValidateEmail'));
          $this->getUser()->setFlash( 'sf_forked_apply',
              sfContext::getInstance()->getI18N()->
              __( 'To complete email change, follow a link included in a confirmation email we have sent to your old email address: %OLDEMAIL%.',
                  array( '%OLDEMAIL%' => $profile->getUser()->getEmailAddress() ), 'sfForkedApply' ) );
        }
        else
        {
          $profile->getUser()->setEmailAddress( $this->form->getValue( 'email' ) );
          $profile->save();
          $this->getUser()->setFlash( 'sf_forked_apply',
              sfContext::getInstance()->getI18N()->
              __( 'Your email has been changed.',
                  array(), 'sfForkedApply' ) );
        }
        return $this->redirect('@settings');
      }
    }

  }

  /**
   * gets From information for email. may throw Exception.
   * @return array
   */
  protected function getFromAddress()
  {
    $from = sfConfig::get('app_sfForkedApply_from', sfConfig::get('app_sfApplyPlugin_from', false));
    if (!$from)
    {
      throw new Exception('app_sfForkedApply_from is not set');
    }
    // i18n the full name
    return array('email' => $from['email'], 'fullname' => sfContext::getInstance()->getI18N()->__($from['fullname']));
  }

  /**
   * apply uses this. Password reset also uses it in the case of a user who
   * was never verified to begin with.
   * @param object $profile
   */
  protected function sendVerificationMail( $profile )
  {
    $this->mail(array('subject' => sfConfig::get('app_sfForkedApply_apply_subject',
                                    sfConfig::get('app_sfApplyPlugin_apply_subject',
        sfContext::getInstance()->getI18N()->__("Please verify your account on %1%",
                                                array('%1%' => $this->getRequest()->getHost()), 'sfForkedApply'))),
        'fullname' => $profile->getFullname(),
        'email' => $profile->getUser()->getEmailAddress(),
        'parameters' => array('fullname' => $profile->getFullname(),
                                'validate' => $profile->getValidate()),
        'text' => 'sfApply/sendValidateNewText',
        'html' => 'sfApply/sendValidateNew'));
  }

  /**
   * This function has been overriden. Original used Zend_Mail here. It's used
   * to actually compose and send e-mail verification message.
   * @param array $options
   */
  protected function mail( $options )
  {
    //Checking for all required options
    $required = array('subject', 'parameters', 'email', 'fullname', 'html', 'text');
    foreach ($required as $option)
    {
      if (!isset($options[$option]))
      {
        throw new sfException("Required option $option not supplied to sfApply::mail");
      }
    }
    $message = $this->getMailer()->compose();
    $message->setSubject($options['subject']);

    // Render message parts
    $message->setBody($this->getPartial($options['html'], $options['parameters']), 'text/html');
    $message->addPart($this->getPartial($options['text'], $options['parameters']), 'text/plain');

    //getting information on sender (that's us). May be source of exception.
    $address = $this->getFromAddress();
    $message->setFrom(array($address['email'] => $address['fullname']));
    $message->setTo(array($options['email'] => $options['fullname']));

    //Sending email
    $this->getMailer()->send($message);
  }

  /**
   * Method that creates forms
   * @param sfForm $formClass
   * @param object $object
   * @return class
   */
  protected function newForm($formClass, $object = null)
  {
    $key = 'app_sfForkedApply_'.$formClass;
    $class = sfConfig::get( $key );
    if ($object !== null)
    {
      return new $class($object);
    }
    return new $class();
  }

  /**
   * Method to activate user
   * @param sfGuardUser $user
   * @author Grzegorz Śliwiński
   */
  protected function activateUser( sfGuardUser $user )
  {
    $user->setIsActive(true);
    $user->save();
    $this->getUser()->signIn($user);
  }
    
  static public function createGuid()
  {
    $guid = "";
    // This was 16 before, which produced a string twice as
    // long as desired. I could change the schema instead
    // to accommodate a validation code twice as big, but
    // that is completely unnecessary and would break
    // the code of anyone upgrading from the 1.0 version.
    // Ridiculously unpasteable validation URLs are a
    // pet peeve of mine anyway.
    for ($i = 0; ($i < 16); $i++)
    {
      $guid .= sprintf("%02x", mt_rand(0, 255));
    }
    return $guid;
  }

  //Returns validation type
  static public function getValidationType($validate)
  {
    $t = substr($validate, 0, 1);
    if( $t == 'n' )
    {
      return 'New';
    }
    elseif( $t == 'r' )
    {
      return 'Reset';
    }
    elseif( $t == 'e' )
    {
      return 'Email';
    }
    else
    {
      return sfView::NONE;
    }
  }

  public function resetRequestBody( $user )
  {
    if (!$user)
    {
      return 'NoSuchUser';
    }
    $this->forward404Unless($user);
    $profile = $user->getProfile();

    if (!$user->getIsActive())
    {
      $type = $this->getValidationType($profile->getValidate());
      if ($type === 'New')
      {
        try
        {
          $this->sendVerificationMail($profile);
        }
        catch (Exception $e)
        {
          //We rethrow exception for the dev environment. This catch
          //catches other than mailer exception, i18n as well. So developer
          //now knows what he's up to.
          if( sfContext::getInstance()->getConfiguration()->getEnvironment() === 'dev' )
          {
            throw $e;
          }
          return 'UnverifiedMailerError';
        }
        return 'Unverified';
      }
      elseif ($type === 'Reset')
      {
        // They lost their first password reset email. That's OK. let them try again
      }
      else
      {
        return 'Locked';
      }
    }
    $profile->setValidate( 'r' . self::createGuid() );
    $date = new DateTime();
    $profile->setValidateAt( $date->format( 'Y-m-d H:i:s' ) );
    $profile->save();
    try
    {
      $this->mail(array('subject' => sfConfig::get('app_sfForkedApply_reset_subject',
                                    sfConfig::get('app_sfApplyPlugin_reset_subject',
              sfContext::getInstance()->getI18N()
              ->__("Please verify your password reset request on %1%",
              array('%1%' => $this->getRequest()->getHost()), 'sfForkedApply'))),
          'fullname' => $profile->getFullname(),
          'email' => $profile->getUser()->getEmailAddress(),
          'parameters' => array('fullname' => $profile->getFullname(),
                                  'validate' => $profile->getValidate(), 'username' => $user->getUsername()),
          'text' => 'sfApply/sendValidateResetText',
          'html' => 'sfApply/sendValidateReset'));
    }
    catch (Exception $e)
    {
      //We rethrow exception for the dev environment. This catch
      //catches other than mailer exception, i18n as well. So developer
      //now knows what he's up to.
      if( sfContext::getInstance()->getConfiguration()->getEnvironment() === 'dev' )
      {
        throw $e;
      }
      return 'MailerError';
    }
    return 'After';
  }
}
?>
