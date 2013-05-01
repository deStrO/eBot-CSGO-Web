<?php

/**
 * Advertising form.
 *
 * @package    PhpProject1
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class AdvertisingForm extends BaseAdvertisingForm
{
  public function configure()
  {
    unset($this["created_at"], $this["updated_at"]);
    $this->widgetSchema['active']->setDefault(true);
    $this->widgetSchema['message'] = new sfWidgetFormTextArea();
  }
}
