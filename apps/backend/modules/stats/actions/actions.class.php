<?php

/**
 * stats actions.
 *
 * @package    PhpProject1
 * @subpackage stats
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class statsActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {

        $data = Doctrine_Query::create()
                ->select('SUM(nb_kill) AS total_kill')
                ->from('players')
                ->fetchOne();

        $this->nbKill = $data["total_kill"];

        $data = Doctrine_Query::create()
                ->select('SUM(hs) AS total_hs')
                ->from('players')
                ->fetchOne();
        $this->nbHs = $data["total_hs"];

        $this->nbNotStarted = MatchsTable::getInstance()->createQuery()->where("status IN ?", array(array(Matchs::STATUS_NOT_STARTED)))->count();
        $this->nbClosed = MatchsTable::getInstance()->createQuery()->where("status >= ?", array(Matchs::STATUS_END_MATCH))->count();
        $this->nbInProgress = MatchsTable::getInstance()->createQuery()->where("status >= ? AND status <= ?", array(Matchs::STATUS_STARTING, Matchs::STATUS_OT_SECOND_SIDE))->andWhere("enable = ?", 1)->count();

        $this->nbServers = ServersTable::getInstance()->createQuery()->count();

   }

}
