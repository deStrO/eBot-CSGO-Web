<?php use_helper('I18N', 'Url') ?>
<?php echo __('
<p>
We have received your request to recover your username and possibly your password on: %1%
</p><p>
Your username is: %USERNAME%
</p><p>
If you have lost your password or wish to reset it, click on the link that follows:
</p><p>
%2%
</p><p>
You will then be prompted for the new password you wish to use.
</p><p>
Your password will not be changed unless you click on the link above and complete the form.
</p>'
, array("%1%" => link_to($sf_request->getHost(), $sf_request->getUriPrefix()),
  "%2%" => link_to(url_for("sfApply/confirm?validate=$validate", true), "sfApply/confirm?validate=$validate", array("absolute" => true)),
  "%USERNAME%" => $username), 'sfForkedApply') ?>

