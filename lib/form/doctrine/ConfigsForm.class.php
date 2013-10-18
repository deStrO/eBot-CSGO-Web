<?php

/**
 * Configs form.
 *
 * @package    PhpProject1
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ConfigsForm extends BaseConfigsForm
{
  public function configure()
  {
      unset($this['created_at'], $this['updated_at'], $this['content']);
  }
}
