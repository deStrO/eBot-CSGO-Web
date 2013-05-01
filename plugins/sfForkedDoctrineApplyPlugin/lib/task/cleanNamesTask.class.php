<?php

class cleanNamesTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'sfDoctrineApply';
    $this->name             = 'clean-names';
    $this->briefDescription = 'Removes disallowed characters from usernames and full names';
    $this->detailedDescription = <<<EOF
The [clean-names|INFO] task removes disallowed characters from usernames and full names. This is recommended for those
who began using sfDoctrineApplyPlugin prior to October 29th, 2009 unless you are consistently escaping usernames and full names
at output time. Note that email addresses ARE NOT and CAN NOT be "cleaned" in this way because <, & and > are legitimate
characters in (admittedly rare) quoted-string email addresses. Never echo email addresses without escaping them.

This task outputs a list of users, if any, whose usernames (not full names) were changed for your convenience in contacting them
to let them know their username has been altered. This is necessary because otherwise they cannot log in as they no longer
know their username. (This list includes their NEW username, not the original one.)

No report is generated for altered full names since these are not critical for user login.

Call this task with:

  [php symfony sfDoctrineApply:clean-names|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'] ? $options['connection'] : null)->getConnection();
    $table = Doctrine::getTable('sfGuardUser');
    $users = $table->createQuery('u')->innerJoin('u.Profile p')->execute();
    $first = true;
    foreach ($users as $user)
    {
      if (preg_match("/[^\w]/", $user->username))
      {
        $user->username = preg_replace("/[^\w]/", "_", $user->username);
        while ($table->findOneByUsername($user->username))
        {
          $user->username .= rand(0, 9);
          echo($user->username);
        }
        $user->save();
        $profile->save();
        if ($first)
        {
          echo("The following usernames required change, contact them if they are legitimate users and\nlet them know their new username.\n");
          echo("The report below shows the NEW username only.\n\n");
          $first = false;
        }
        echo("Username: " . $user->username . ' Fullname: ' . $user->Profile->fullname . ' Email: ' . $user->getEmailAddress() . "\n");
      }
      $profile = $user->getProfile();
      if (preg_match("/[\<\>\&\|]/", $profile->fullname))
      {
        // No need for a big announcement because we don't log in by our full names
        $profile->fullname = preg_replace("/[\<\>\&\|]/", "_", $profile->fullname);
        $profile->save();
      }
    }
  }
}
