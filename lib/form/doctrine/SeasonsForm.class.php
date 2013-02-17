<?php

/**
 * Seasons form.
 *
 * @package    PhpProject1
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class SeasonsForm extends BaseSeasonsForm
{
  public function configure()
  {
    $this->useFields(array("id", "name", "link"));
    $this->widgetSchema["start"] = new sfWidgetFormDate(array('format' => '%day% - %month% - %year%'));
    $this->widgetSchema["end"] = new sfWidgetFormDate(array('format' => '%day% - %month% - %year%'));
    $this->validatorSchema['start'] = new sfValidatorDateTime(array('required' => false));
    $this->validatorSchema['end'] = new sfValidatorDateTime(array('required' => false));
  }
}
