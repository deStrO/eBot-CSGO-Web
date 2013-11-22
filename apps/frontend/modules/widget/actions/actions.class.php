<?php

/**
 * widget actions.
 *
 * @package    PhpProject1
 * @subpackage widget
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class widgetActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeMatchPlayersStat(sfWebRequest $request) {
        $this->match = $this->getRoute()->getObject();
        $this->setLayout("layout_widget");
    }

}
