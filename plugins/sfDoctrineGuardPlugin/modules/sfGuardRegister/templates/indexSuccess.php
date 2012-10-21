<?php use_helper('I18N') ?>
<h1><?php echo __('Register', null, 'sf_guard') ?></h1>

<?php echo get_partial('sfGuardRegister/form', array('form' => $form)) ?>