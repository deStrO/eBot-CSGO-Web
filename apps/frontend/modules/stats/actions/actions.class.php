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

    public function executeGlobal(sfWebRequest $request) {
        $this->filter = new MatchsFormFilter($this->getFilters());
        $query = $this->filter->buildQuery($this->getFilters());
        $this->filterValues = $this->getFilters();

        $matchs = $this->getUser()->getAttribute("global.stats.matchs", array());
        if (count($matchs) > 0) {
            $this->matchs = $query->andWhere("id IN ?", array($matchs))->execute();
        } else {
            $this->matchs = $query->execute();
        }

        if ($request->getMethod() == sfWebRequest::POST) {
            if (is_array($request->getPostParameter("ids"))) {
                $matchTab = array();
                foreach ($request->getPostParameter("ids") as $id) {
                    if (!is_numeric($id))
                        continue;
                    if (in_array($id, $matchTab))
                        continue;
                    if (!MatchsTable::getInstance()->find($id))
                        continue;
                    $matchTab[] = $id;
                }

                if (count($matchTab) == 0) {
                    $this->getUser()->setFlash("notification.error", "No entry found.");
                    $this->getUser()->setAttribute("global.stats.matchs", null);
                } else {
                    $this->getUser()->setFlash("notification.ok", "Filter applied.");
                    $this->getUser()->setAttribute("global.stats.matchs", $matchTab);
                }
            } else {
                $this->getUser()->setFlash("notification.ok", "The filter was resetted.");
                $this->getUser()->setAttribute("global.stats.matchs", null);
            }

            $this->redirect("global_stats");
        }
    }

    public function executePlayerStat(sfWebRequest $request) {
        $this->id = $request->getParameter("id");
        $this->forward404Unless($this->id);

        $this->stats = PlayersTable::getInstance()->createQuery()->where("steamid = ?", $this->id)->orderBy("id ASC")->execute();
        $this->forward404Unless($this->stats->count() > 0);
    }

    public function executeMapsStats(sfWebRequest $request) {
        $this->filter = new MatchsFormFilter($this->getFilters());
        $query = $this->filter->buildQuery($this->getFilters());
        $this->filterValues = $this->getFilters();

        $this->matchs = $query->andWhere("status >= ?", array(Matchs::STATUS_END_MATCH))->execute();
    }

    public function executeWeaponStats(sfWebRequest $request) {
        $query = "SELECT `weapon`, count(*) as nb FROM player_kill WHERE headshot = 0 GROUP BY `weapon`";
        $rs = Doctrine_Manager::getInstance()->getCurrentConnection()->fetchAssoc($query);
        foreach ($rs as $v) {
            $weapons[$v["weapon"]]["normal"] = $v["nb"];
        }

        $query = "SELECT `weapon`, count(*) as nb FROM player_kill WHERE headshot = 1 GROUP BY `weapon`";
        $rs = Doctrine_Manager::getInstance()->getCurrentConnection()->fetchAssoc($query);
        foreach ($rs as $v) {
            $weapons[$v["weapon"]]["hs"] = $v["nb"];
        }

        $this->weapons = $weapons;
    }

    public function executeFilters(sfWebRequest $request) {
        $this->filter = new MatchsFormFilter();
        $this->filter->bind($request->getPostParameter($this->filter->getName()));
        if ($this->filter->isValid()) {
            $this->setFilters($this->filter->getValues());
        }

        $this->redirect($request->getReferer());
    }

    public function executeFiltersClear(sfWebRequest $request) {
        $this->setFilters(array());
        $this->redirect($request->getReferer());
    }

    private function getFilters() {
        return $this->getUser()->getAttribute('matchs.filters', array(), 'admin_module');
    }

    private function setFilters($filters) {
        return $this->getUser()->setAttribute('matchs.filters', $filters, 'admin_module');
    }

}
