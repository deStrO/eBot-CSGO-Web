<?php

class sfApplySettingsForm extends sfGuardUserProfileForm
{
  public function configure()
  {
    parent::configure();
    $this->removeFields();

    // We're editing the user who is logged in. It is not appropriate
    // for the user to get to pick somebody else's userid, or change
    // the validate field which is part of how their account is
    // verified by email. Also, users cannot change their email
    // addresses as they are our only verified connection to the user.

    $this->widgetSchema->setLabels(
            array(
                'firstname' => 'First Name',
                'lastname' => 'Last name') );
    $this->widgetSchema->moveField('firstname', sfWidgetFormSchema::FIRST);
    $this->widgetSchema->moveField('lastname', sfWidgetFormSchema::AFTER, 'firstname');

    $this->widgetSchema->setNameFormat('sfApplySettings[%s]');
    $this->widgetSchema->setFormFormatterName('list');

    $this->setValidator( 'firstname' , new sfValidatorApplyFirstname() );
    $this->setValidator( 'lastname', new sfValidatorApplyLastname() );



  }

  protected function removeFields()
  {
    unset(
        $this['user_id'],
        $this['validate'],
        $this['email'],
        $this['email_new'],
        $this['validate_at'],
        $this['id'],
        $this['created_at'],
        $this['updated_at']
    );
  }
}