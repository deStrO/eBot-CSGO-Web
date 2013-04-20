<?php use_helper('I18N') ?>
<?php if (has_slot('sf_apply_login')): ?>
  <?php include_slot('sf_apply_login') ?>
<?php else: ?>
  <?php if ($loggedIn): ?>
    <?php include_partial('sfApply/logoutPrompt') ?>
  <?php else: ?>
    <?php include_partial('sfApply/loginPrompt', array("form" => $form)) ?>
  <?php endif ?>
<?php endif ?>
