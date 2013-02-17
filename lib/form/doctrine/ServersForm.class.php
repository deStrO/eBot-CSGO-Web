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

        $this->widgetSchema["rcon"] = new sfWidgetFormInputPassword();

        $this->widgetSchema["hostname"]->setLabel("Nom du serveur");
    }

}
