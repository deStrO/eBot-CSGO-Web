<?php use_helper('I18N', 'Url') ?>
<?php echo __("Hello %USERNAME%!

We have received your request to change your email address on: %1%

You're going to change your email from: %OLDEMAIL% to %NEWEMAIL%

To continue with your change, click on the link that follows:

%2%

Your email will then be changed pemanently."
, array(
  "%1%" => $sf_request->getHost(),
  "%2%" => url_for("sfApply/confirm?validate=$validate", true),
  "%USERNAME%" => $username,
  "%OLDEMAIL%" => $oldmail,
  "%NEWEMAIL%" => $newmail
  ),
    'sfForkedApply') ?>
