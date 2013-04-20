<?php use_helper('I18N', 'Url') ?>
<?php echo __('We have received your request to recover your username and possibly your password on:

%1%

Your username is: %USERNAME%

If you have lost your password or wish to reset it, click on the link that follows:

%2%

You will then be prompted for the new password you wish to use.

Your password will NOT be changed unless you click on the link above and complete the form.'
, array("%1%" => url_for($sf_request->getUriPrefix()),
  "%2%" => url_for("sfApply/confirm?validate=$validate", true),
  "%USERNAME%" => $username), 'sfForkedApply') ?>

