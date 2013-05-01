<?php

/**
 * Seasons form.
 *
 * @package    PhpProject1
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class SeasonsForm extends BaseSeasonsForm {
    public function configure() {
        unset($this["created_at"], $this["updated_at"]);

        $this->widgetSchema["start"] = new sfWidgetFormInputText(array("default" => date("d.m.Y")), array("id" => "start", "style" => "width:150px;"));
        $this->widgetSchema["end"] = new sfWidgetFormInputText(array("default" => date("d.m.Y")), array("id" => "end", "style" => "width:150px;"));
        $this->validatorSchema['start'] = new sfValidatorDateTime(array('required' => false));
        $this->validatorSchema['end'] = new sfValidatorDateTime(array('required' => false));

        $this->widgetSchema['logo'] = new sfWidgetFormInputFile(array('label' => 'Season Logo'));
        $this->validatorSchema['logo'] = new sfValidatorFile(array('required' => false, 'path' => sfConfig::get('sf_upload_dir').'/seasons', 'mime_types' => 'web_images'));

        $this->widgetSchema['active']->setDefault(true);
    }
}
