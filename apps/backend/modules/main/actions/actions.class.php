<?php

/**
 * main actions.
 *
 * @package    PhpProject1
 * @subpackage main
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class mainActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {
        $this->news = file_get_contents("http://www.esport-tools.net/ebot/news.txt?version=".sfConfig::get("app_version"));
    }

}
