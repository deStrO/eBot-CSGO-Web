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
        $query =  MatchsTable::getInstance()->getMatchsInProgressQuery();
        if (sfConfig::get("app_mode") == "net") {
            $query->andWhere("status > 0");
        }
        $this->matchs = $query->limit(10)->execute();
    }
        
    public function executeIngame(sfWebRequest $request) { }

}
