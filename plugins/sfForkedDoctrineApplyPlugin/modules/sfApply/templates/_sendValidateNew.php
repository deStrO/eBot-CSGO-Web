<?php use_helper('I18N', 'Url') ?>
<?php echo __('<p>
Thanks for applying for an account on %1%.
</p><p>
To prevent abuse of the site, we require that you activate your account by clicking on the following link:
</p><p>
%2%
</p><p>
Thanks again for joining us.
</p>'
, array("%1%" => link_to($sf_request->getHost(), $sf_request->getUriPrefix()),
  "%2%" => link_to(url_for("sfApply/confirm?validate=$validate", true), "sfApply/confirm?validate=$validate", array("absolute" => true))),
        'sfForkedApply') ?>
