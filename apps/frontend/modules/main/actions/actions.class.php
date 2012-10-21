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
        $this->matchs = MatchsTable::getInstance()->getMatchsInProgressQuery()->limit(10)->execute();
        /*$this->match = MatchsTable::getInstance()->getZoomMatch();
        if (!$this->match) {
            $this->match = MatchsTable::getInstance()->getRandomMatchStarted();
        }
        
        $this->stats = MatchsTable::getInstance()->getRandomMatchStarted();*/
    }
    
    public function executeZoomReload (sfWebRequest $request) {
        $match = $this->getRoute()->getObject();
        
        return $this->renderPartial("zoom", array("match" => $match));
    }
    
    public function executeMatchsReload(sfWebRequest $request) {
        $this->matchs = MatchsTable::getInstance()->getMatchsInProgressQuery()->execute();
        
        return $this->renderPartial("matchsInProgress", array("matchs" => $this->matchs));
    }
    
    public function executeIngame(sfWebRequest $request) { }

}
