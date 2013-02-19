<?php

/**
 * sekyo actions.
 *
 * @package    PhpProject1
 * @subpackage sekyo
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class streamActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeMatch(sfWebRequest $request) {
        $this->forward404Unless($request->getParameter("id"));
        $this->match = MatchsTable::getInstance()->find($request->getParameter("id"));
        if (!$this->match->exists()) {
            $this->redirect("homepage");
        }
        
        $this->setLayout("layout_stream");
    }

}
