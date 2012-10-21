<?php use_helper('I18N') ?>

<h2><?php echo __('Oops! The page you asked for is secure and you do not have proper credentials.', null, 'sf_guard') ?></h2>

<p><?php echo sfContext::getInstance()->getRequest()->getUri() ?></p>

<h3><?php echo __('Login below to gain access', null, 'sf_guard') ?></h3>

<?php echo get_component('sfGuardAuth', 'signin_form') ?>