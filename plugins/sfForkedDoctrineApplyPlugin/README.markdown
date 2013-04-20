# sfForkedDoctrineApply plugin #
Originally forked from [sfDoctrineApply](http://www.symfony-project.org/plugins/sfDoctrineApplyPlugin) plugin (version 1.1.1).

It has been stripped of Zend Mail dependency as proposed by stephenrs on symfony forums,
[here](http://forum.symfony-project.org/index.php/t/25217/)

For news about sfForkedDoctrineApplyPlugin, please visit [fizyk's website](http://www.fizyk.net.pl/blog/tag/sfForkedDoctrineApply).

##Requirements##
* symfony 1.4
* sfDoctrineGuardPlugin 5.x - installed and configured

Requirements should be similar as the original plugin, although I can only be
sure of symfony 1.4.
When sfDoctrineGuardPlugin will introduce email in official package, our current
plugin should be modified to use sfGuardUser's email field, not it's own.

##Changes to sfDoctrineApplyPlugin##

* removed all Zend Mail dependency
* created a general library with all sfApplyActions functions
* introduced inheritance to Profile model.

##Installation##

Installation should be simple as:

    symfony plugin:install sfForkedDoctrineApplyPlugin

However it is also possible to install it through archive:

    symfony plugin:install sfForkedDoctrineApplyPlugin-1.5.7.tgz

just place downloaded package in your project's root first.

You can also install it manually, unpacking archive, placing it's content in your
project's plugin/ directory, and enabling it in your ProjectConfiguration.class.php file:

config/ProjectConfiguration.class.php

    class ProjectConfiguration extends sfProjectConfiguration
    {
        //....//
        public function setup()
        {
            //....//
            $this->enablePlugins('sfDoctrineGuardPlugin');
            $this->enablePlugins('sfForkedDoctrineApplyPlugin');
            //....//
        }
    }

After that create migrations:

    ./symfony doctrine:generate-migrations-diff

Review your migration files after this step, and run:

    ./symfony doctrine:migrate
    ./symfony doctrine:build --all-classes

All you need to do now is to enable sfApply module in your settings.yml file:

**apps/APPLICATION/config/settings.yml**

    all:
      .settings:
        #...#
        enabled_modules: [default, ... , sfGuardAuth, sfApply]

Doing this will also automatically add necessary routes to your app

That's pretty much all you have to do. If you're not satisfied with the model
provided, you can extend it using e.g column_aggregation inheritance:

**config/doctrine/schema.yml**

    MyProfile:
      inheritance:
        type: column_aggregation
        extends: sfGuardUserProfile

You can add your own columns, relations, behaviours or even model names if needed.
With doctrine inheritance you can have different profile types.

You can also add your own columns or redeclare to the provided model instead of using aggregation:

**config/doctrine/schema.yml**

    sfGuardUserProfile:
      columns:
        my_column: { type: int }
        #...

##Upgrade##


### sfForkedDoctrineApply 1.5x ###

If you're planning to upgrade to sfForked 1.5.x from versions olders than 1.4, 
be sure to migrate to 1.4 before migrating to 1.5.

After migrating to 1.5 migrate databse, since we drop email field, and no longer 
use profile email field in code. 


    ./symfony doctrine:migrate
    ./symfony doctrine:build --all-classes
    ./symfony doctrine:clean-model-files


All calls to profile's email filed by getEmail or setEmail 
methods will be redirected to user's getEmailAddress or setEmailAddress method.


##Configuration##

To configure this plugin to actually send registration emails,
You need to set up email settings according to
[day 16](http://www.symfony-project.org/jobeet/1_4/Doctrine/en/16) of Jobeet tutorial.

###Basic###

In order to send emails with confirmation codes you've got to add these settings in your app.yml:

**apps/APPLICATION/config/app.yml**


    sfForkedApply:
      from:
        email: "your@emailaddress.com"
        fullname: "the staff at yoursite.com"
    # that section is as a fallback only
    sfApplyPlugin:
      from:
        email: "your@emailaddress.com"
        fullname: "the staff at yoursite.com"


You should also turn on i18n engine, as this plugin, like the project it rooted
from is fully internationalised (You might have to prepare i18n files for your language though):

**apps/APPLICATION/config/settings.yml**

    all:
      .settings:
        i18n: true

You can modify URL's for the sfApply module's action. To do that, simply add this options to your app.yml file:

        all:
          #...
          sfForkedApply:
            #...
            routes:
              apply: /user/new
              reset: /user/password-reset
              resetRequest: /user/reset-request
              resetCancel: /user/reset-cancel
              validate: /user/confirm/:validate
              settings: /user/settings

Each key's value represents route's url, you can change them as you want.

###sfForkedDoctrineApply:clear-validation-codes task###

Now sfForkedDoctrineApply makes use of the initialy created validate_at field,
allowing you to expire validate codes. To remove all codes older than a required time just run:

    ./symfony sfForkedDoctrineApply:clear-validation-codes -d="number_of_days"

You can ommit the --d parameter, to leave the default value of 1 day.

###CAPTCHA###

Starting from 1.1.0 version, sfForkedDoctrineApplyPlugin integrates reCaptcha. To use it, you have to install [sfFormExtraPlugin](http://www.symfony-project.org/plugins/sfFormExtraPlugin) to get access to [reCaptcha](http://recaptcha.net/) widget and validator. Second step is to be conducted in your app.yml file, and add these:

**apps/APPLICATION/config/app.yml**

    all:
      #...
      recaptcha:
        enabled:        true
        public_key:     YOUR_PUBLIC_reCAPTCHA_KEY
        private_key:    YOUR_PRIVATE_reCAPTCHA_KEY

enabled property is for enabling and disabling captcha. After setting this,
reCaptcha will appear on apply and reset request pages.

###Custom forms###

It's possible to define custom forms, however all custom forms must extend the
Apply ones. To use custom forms, you need to define them in your app.yml file:

**apps/APPLICATION/config/app.yml**

    all:
      #...
      sfForkedApply:
        applyForm: sfApplyApplyForm
        resetForm: sfApplyResetForm
        resetRequestForm: sfApplyResetRequestForm
        settingsForm: sfApplySettingsForm

The above example uses standard sfApplyForms.

###Email editing###

To allow users to edit their emails, you've got to add app_sfForkedApply_mail_editable setting:

    all:
      #...
      sfForkedApply:
        #...
        mail_editable: false

Now, when user will try to edit their email, he'll receive confirmation email on his old address. editMail action will also get route generated which will be composed from settings URL to which "/email" end will be added.

###Confirmation disabling###

It is possible, although not recommended to disable email confirmations for the following actions:

* Apply (apply) - new users will be registered and logged as soon as they submit valid apply form.
* Password reset (reset) - this will disable the reset request, password change will be possible only for logged in users.
* Email edit (email) - new email will immediately replace old one.
* Password reset for logged users (reset_logged) - this will send confirmation email for logged users (default is disabled).

To disable confirmation emails for any of this actions, simply add and modify following options to application's app.yml file:

    all:
      #...
      sfForkedApply:
        #...
        confirmation:
          reset: true
          apply: true
          email: true
          reset_logged: false

###Login redirect###

There are two settings regarding directing user after actions he takes within sfApply module:

    all:
      #...
      sfForkedApply:
        afterLogin: after_login_route
        after: after_route
      # as a fallback we use old options too:
      sfApplyPlugin:
        afterLogin: after_login_route
        after: after_route

You can use these settings to direct user to your own pages after user loggs in, or in other cases with second setting.

## Displaying Login and Logout Prompts##
(fragment of sfDoctrineApplyPlugin's README)

You probably have pages on which logging in is optional. It's nice to
display a login prompt directly on these pages. If you want to do that,
try including my login/logout prompt component from your
`apps/frontend/templates/layout.php` file:

    <?php include_component('sfApply', 'login') ?>

Note that you can suppress the login prompt on pages that do include
this partial by setting the `sf_apply_login` slot:

    <?php slot('sf_apply_login') ?>
    <?php end_slot() ?>


## Credits ##

sfDoctrineApplyPlugin was written by *Tom Boutell*. He can be contacted
at [tom@punkave.com](mailto:tom@punkave.com). See also [www.punkave.com](http://www.punkave.com/) and
[www.boutell.com](http://www.boutell.com/) for further information about his work.

Changes resulting in forking the original plugin were written by [stephenrs](http://forum.symfony-project.org/index.php/u/11253/).

Both Tom and Stephen deserves a Big thanks.

sfForkedDoctrineApplyPlugin was created by Grzegorz Śliwiński as a result of those changes with some (more and more with each release) additions.
You can contact him at [fizyk@fizyk.net.pl](mailto:fizyk@fizyk.net.pl) or through
jabber at the same address and follow his adventures on his [homepage](http://www.fizyk.net.pl/).

###Translations###
* Dutch - Jasper Moelker
* French - Pierre Grandin
* German - Daniel Hanke
* Italian - Alessandro Rossi
* Polish - Grzegorz Śliwiński
* Russian - Serg Puhoff
* Spanish - Alex Otero
* Portugese (Brazil) - Alan Candido

Support and Help
------------
sfForkedDoctrineApply is completely free, but you can support it's creator:

[![Support sfForkedDoctrineApplyPlugin](http://www.pledgie.com/campaigns/13968.png?skin_name=chrome "Support sfForkedDoctrineApplyPlugin")](http://www.pledgie.com/campaigns/13968)

For help regarding this plugin, you can go to plugin's github issue tracker, or contact author via jabber/xmpp/gtalk protocol: [fizyk@fizyk.net.pl](xmpp:fizyk@fizyk.net.pl) or though e-mail: [fizyk@fizyk.net.pl](mailto:fizyk@fizyk.net.pl)