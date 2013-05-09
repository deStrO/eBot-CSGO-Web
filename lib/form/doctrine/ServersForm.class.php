<?php

/**
 * Servers form.
 *
 * @package    PhpProject1
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ServersForm extends BaseServersForm {

    public function configure() {
        $this->useFields(array("id", "ip", "rcon", "hostname", "tv_ip"));

        $this->widgetSchema["ip"]->setLabel("Server IP");
        $this->widgetSchema["rcon"]->setLabel("RCON Password");
        $this->widgetSchema["hostname"]->setLabel("Internal Servername");
        $this->widgetSchema["tv_ip"]->setLabel("GOTV IP");

        $this->getWidgetSchema()->moveField('rcon', sfWidgetFormSchema::AFTER, 'hostname');
    }

}
