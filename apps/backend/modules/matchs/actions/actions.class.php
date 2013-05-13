<?php

/**
 * matchs actions.
 *
 * @package    PhpProject1
 * @subpackage matchs
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class matchsActions extends sfActions {

    private function __($text, $args = array()) {
        return $this->getContext()->getI18N()->__($text, $args, 'messages');
    }

    public function executeStartAll(sfWebRequest $request) {
        $matchs = MatchsTable::getInstance()->createQuery()->where("status = ?", Matchs::STATUS_NOT_STARTED)->execute();
        if ($matchs->count() == 0) {
            $this->getUser()->setFlash("notification_error", $this->__("No match started"));
            $this->redirect("matchs_current");
        }

        foreach ($matchs as $match) {
            $server = null;
            MatchsTable::getInstance()->clear();
            ServersTable::getInstance()->clear();
            $matchs = MatchsTable::getInstance()->getMatchsInProgressQuery()->andWhere("enable = ?", 1)->andWhere("status < ? ", Matchs::STATUS_END_MATCH)->andWhere("status > ? ", Matchs::STATUS_NOT_STARTED)->execute();
            $servers = ServersTable::getInstance()->createQuery()->orderBy("RAND()")->execute();
            $used = array();
            foreach ($matchs as $m) {
                $used[] = $m->getServer()->getIp();
            }

            foreach ($servers as $s) {
                if (in_array($s->getIp(), $used)) {
                    continue;
                }

                $server = $s;
                break;
            }

            if (is_null($server)) {
                $this->getUser()->setFlash("notification_error", $this->__("No Server available"));
                $this->redirect("matchs_current");
            }

            $match->setIp($server->getIp());
            $match->setServer($server);
            $match->setEnable(1);
            $match->setStatus(Matchs::STATUS_STARTING);
            $match->setScoreA(0);
            $match->setScoreB(0);
            if ($match->getConfigAuthkey() == "")
                $match->setConfigAuthkey(uniqid(mt_rand(), true));
            $match->save();
        }

        $this->getUser()->setFlash("notification_ok", $this->__("The matches were started successfully"));
        $this->redirect("matchs_current");
    }

    public function executeStop(sfWebRequest $request) {
        $match = $this->getRoute()->getObject();
        $this->forward404Unless($match);
        $this->forward404Unless($match->getEnable());
        $this->forward404Unless(($match->getStatus() >= Matchs::STATUS_WU_KNIFE) && ($match->getStatus() <= Matchs::STATUS_WU_OT_2_SIDE));

        $match->stop();

        $this->getUser()->setFlash("notification_ok", $this->__("Command send to the server, match will be stopped."));
        $this->redirect("matchs_current");
    }

    public function executeStopRS(sfWebRequest $request) {
        $match = $this->getRoute()->getObject();
        $this->forward404Unless($match);
        $this->forward404Unless($match->getEnable());
        $this->forward404Unless(($match->getStatus() >= Matchs::STATUS_WU_KNIFE) && ($match->getStatus() <= Matchs::STATUS_WU_OT_2_SIDE));

        $match->stop(true);

        $this->getUser()->setFlash("notification_ok", $this->__("Command send to the server, match will be stopped."));
        $this->redirect("matchs_current");
    }

    public function executeDelete(sfWebRequest $request) {
        $match = $this->getRoute()->getObject();
        $this->forward404Unless($match);
        $this->forward404Unless(!$match->getEnable() || $match->getStatus() == Matchs::STATUS_NOT_STARTED || $match->getStatus() == Matchs::STATUS_END_MATCH);

        $match->delete();

        $this->getUser()->setFlash("notification_ok", $this->__("Match deleted"));
        $this->redirect($request->getReferer());
    }

    public function executePassKnife(sfWebRequest $request) {
        $match = $this->getRoute()->getObject();
        $this->forward404Unless($match);
        $this->forward404Unless($match->getEnable());
        $this->forward404Unless(($match->getStatus() == Matchs::STATUS_WU_KNIFE));

        $match->passKnife();

        $this->getUser()->setFlash("notification_ok", $this->__("Command send to the server, knife will be skipped."));
        $this->redirect("matchs_current");
    }

    public function executeForceKnife(sfWebRequest $request) {
        $match = $this->getRoute()->getObject();
        $this->forward404Unless($match);
        $this->forward404Unless($match->getEnable());
        $this->forward404Unless(($match->getStatus() == Matchs::STATUS_WU_KNIFE));

        $match->forceKnife();

        $this->getUser()->setFlash("notification_ok", $this->__("Command send to the server, knife will be forced."));
        $this->redirect("matchs_current");
    }

    public function executeForceKnifeEnd(sfWebRequest $request) {
        $match = $this->getRoute()->getObject();
        $this->forward404Unless($match);
        $this->forward404Unless($match->getEnable());
        $this->forward404Unless(($match->getStatus() == Matchs::STATUS_END_KNIFE));

        $match->forceKnifeEnd();

        $this->getUser()->setFlash("notification_ok", $this->__("Command send to the server, match will skipped to warmup."));
        $this->redirect("matchs_current");
    }

    public function executeForceStart(sfWebRequest $request) {
        $match = $this->getRoute()->getObject();
        $this->forward404Unless($match);
        $this->forward404Unless($match->getEnable());
        $this->forward404Unless(in_array($match->getStatus(), array(Matchs::STATUS_WU_1_SIDE, Matchs::STATUS_WU_2_SIDE, Matchs::STATUS_WU_OT_1_SIDE, Matchs::STATUS_WU_OT_2_SIDE)));

        $match->forceStart();

        $this->getUser()->setFlash("notification_ok", $this->__("Command send to the server, match will be started."));
        $this->redirect("matchs_current");
    }

    public function executeStopBack(sfWebRequest $request) {
        $match = $this->getRoute()->getObject();
        $this->forward404Unless($match);
        $this->forward404Unless($match->getEnable());
        $this->forward404Unless(in_array($match->getStatus(), array(Matchs::STATUS_FIRST_SIDE, Matchs::STATUS_SECOND_SIDE, Matchs::STATUS_OT_FIRST_SIDE, Matchs::STATUS_OT_SECOND_SIDE)));

        $match->stopBack();

        $this->getUser()->setFlash("notification_ok", $this->__("Command send to the server, match will be stopped to warmup."));
        $this->redirect("matchs_current");
    }

    public function executePauseUnpause(sfWebRequest $request) {
        $match = $this->getRoute()->getObject();
        $this->forward404Unless($match);
        $this->forward404Unless($match->getEnable());
        $this->forward404Unless(in_array($match->getStatus(), array(Matchs::STATUS_FIRST_SIDE, Matchs::STATUS_SECOND_SIDE, Matchs::STATUS_OT_FIRST_SIDE, Matchs::STATUS_OT_SECOND_SIDE)));

        $match->pauseUnpause();

        $this->getUser()->setFlash("notification_ok", $this->__("Command send to the server, match will be paused/unpaused."));
        $this->redirect("matchs_current");
    }

    public function executeStart(sfWebRequest $request) {
        $this->forward404Unless($request->getPostParameter("match_id"));
        $this->forward404Unless(is_numeric($request->getPostParameter("match_id")));

        $match = MatchsTable::getInstance()->find($request->getPostParameter("match_id"));
        $this->forward404Unless($match && $match->exists());

        $this->forward404Unless(!$match->getEnable());

        $server = null;
        $server_id = null;

        $server_id = $match->getServerId();
        if (!isset($server_id))
            $server_id = $request->getPostParameter("server_id");
        if (is_numeric($server_id) && $server_id != 0) {
            $server = ServersTable::getInstance()->find($server_id);
            $this->forward404Unless($server && $server->exists());
        }

        if (is_null($server)) {
            $matchs = MatchsTable::getInstance()->getMatchsInProgressQuery()->andWhere("enable = ?", 1)->andWhere("status < ? ", Matchs::STATUS_END_MATCH)->andWhere("status > ? ", Matchs::STATUS_NOT_STARTED)->execute();
            $servers = ServersTable::getInstance()->createQuery()->orderBy("RAND()")->execute();
            $used = array();
            foreach ($matchs as $m) {
                $used[] = $m->getServer()->getIp();
            }

            foreach ($servers as $s) {
                if (in_array($s->getIp(), $used)) {
                    continue;
                }

                $server = $s;
                break;
            }
        }

        if (is_null($server)) {
            $this->getUser()->setFlash("notification_error", $this->__("No server available"));
            $this->redirect("matchs_current");
        }

        $match->setIp($server->getIp());
        $match->setServer($server);

        $match->setEnable(1);
        if ($match->getStatus() == Matchs::STATUS_NOT_STARTED)
            $match->setStatus(Matchs::STATUS_STARTING);
        $match->setScoreA(0);
        $match->setScoreB(0);
        if ($match->getConfigAuthkey() == "")
            $match->setConfigAuthkey(uniqid(mt_rand(), true));
        $match->save();

        $this->getUser()->setFlash("notification_ok", $this->__("Match will be started on") . " " . $server->getIp());
        $this->redirect("matchs_current");
    }

    public function executeSetArchive(sfWebRequest $request) {
        $match = $this->getRoute()->getObject();
        $this->forward404Unless($match);
        $this->forward404Unless($match->getStatus() == Matchs::STATUS_END_MATCH);

        $match->setEnable(0);
        $match->setStatus(Matchs::STATUS_ARCHIVE);
        $match->save();

        $this->getUser()->setFlash("notification_ok", $this->__("Match archived"));
        $this->redirect("matchs_current");
    }

    public function executeArchiveAll(sfWebRequest $request) {
        $matchs = MatchsTable::getInstance()->createQuery()->where("status = ?", Matchs::STATUS_END_MATCH)->execute();

        if ($matchs->count() == 0) {
            $this->getUser()->setFlash("notification_error", $this->__("No Match archived"));
            $this->redirect("matchs_current");
        }

        foreach ($matchs as $match) {
            $match->setEnable(0);
            $match->setStatus(Matchs::STATUS_ARCHIVE);
            $match->save();
        }

        $this->getUser()->setFlash("notification_ok", $matchs->count() . $this->__(" match(es) archived"));
        $this->redirect("matchs_current");
    }

    public function executeReset(sfWebRequest $request) {
        $match = $this->getRoute()->getObject();
        $this->forward404Unless($match);
        $this->forward404Unless(!$match->getEnable());
        if (($match->getStatus() >= Matchs::STATUS_WU_KNIFE) && ($match->getStatus() < Matchs::STATUS_END_MATCH)) {
            $match->setScoreA(0);
            $match->setScoreB(0);
            $match->setStatus(0);
            $match->setIngameEnable(NULL);
            $match->setIsPaused(NULL);
//			$match->setIp(null);
//			$match->setServer(null);
            $match->save();
            foreach ($match->getMaps() as $map) {
                $map->score_1 = 0;
                $map->score_2 = 0;
                $map->setNbOt(0);
                $map->setStatus(0);
                $map->save();
                foreach ($map->getMapsScore() as $score) {
                    $score->delete();
                }
            }

            foreach ($match->getRoundSummary() as $round) {
                $round->delete();
            }
            $this->getUser()->setFlash("notification_ok", $this->__("Match is resetted"));
        } else {
            $this->getUser()->setFlash("notification_error", $this->__("Match can't be resetted"));
        }

        $this->redirect("matchs_current");
    }

    public function executeStartRetry(sfWebRequest $request) {
        $match = $this->getRoute()->getObject();
        $this->forward404Unless($match);
        $this->forward404Unless(!$match->getEnable());

        $nb = MatchsTable::getInstance()->createQuery()
                ->where("server_id = ?", $match->getServerId())
                ->andWhere("id != ?", $match->getId())
                ->andWhere("enable = ?", 1)
                ->andWhere("status < ?", Matchs::STATUS_END_MATCH)
                ->count();

        if ($nb == 0) {
            $match->setEnable(1);
            if ($match->getConfigAuthkey() == "")
                $match->setConfigAuthkey(uniqid(mt_rand(), true));
            $match->save();

            $this->getUser()->setFlash("notification_ok", $this->__("Match is restarted on") . " " . $match->getServer()->getIp());
        } else {
            $this->getUser()->setFlash("notification_error", $this->__("A match is currently played on") . $match->getServer()->getIp());
            $match->setIp(null);
            $match->setServer(null);
            $match->setEnable(0);
            $match->save();

            $this->getUser()->setFlash("notification_ok", $this->__("Server resetted from match"));
        }

        $this->redirect("matchs_current");
    }

    public function executeMatchsInProgress(sfWebRequest $request) {
        $this->filter = new MatchsFormFilter($this->getFilters());
        $query = $this->filter->buildQuery($this->getFilters());
        $this->filterValues = $this->getFilters();

        $table = MatchsTable::getInstance();
        $this->pager = null;
        $this->pager = new sfDoctrinePager(
                'Matchs', 12
        );
        $this->pager->setQuery($query->andWhere("status >= ? AND status <= ?", array(Matchs::STATUS_NOT_STARTED, Matchs::STATUS_END_MATCH))->orderBy("id ASC"));
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();

        $this->url = "@matchs_current_page";

        $this->servers = ServersTable::getInstance()->findAll();
        $this->seasons = SeasonsTable::getInstance()->findAll();
        $this->ebot_ip = sfConfig::get("app_ebot_ip");
        $this->ebot_port = sfConfig::get("app_ebot_port");
    }

    public function executeMatchsArchived(sfWebRequest $request) {
        $this->filter = new MatchsFormFilter($this->getFilters());
        $query = $this->filter->buildQuery($this->getFilters());
        $this->filterValues = $this->getFilters();

        $table = MatchsTable::getInstance();
        $this->pager = null;
        $this->pager = new sfDoctrinePager(
                'Matchs', 12
        );
        $this->pager->setQuery($query->andWhere("status = ?", Matchs::STATUS_ARCHIVE)->orderBy("id DESC"));
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();

        $this->url = "@matchs_archived_page";
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

    public function executeCreate(sfWebRequest $request) {
        $this->form = new MatchsForm();
        $this->maps = sfConfig::get("app_maps");
        array_push($this->maps, 'tba');
        $this->servers = ServersTable::getInstance()->findAll();

        if ($request->getMethod() == sfWebRequest::POST) {
            $this->form->bind($request->getPostParameter($this->form->getName()));
            if ($this->form->isValid() && in_array($_POST["maps"], $this->maps)) {
                $match = $this->form->save();

                if ($match->getTeamA()->exists()) {
                    $match->setTeamAName($match->getTeamA()->getName());
                    $match->setTeamAFlag($match->getTeamA()->getFlag());
                }

                if ($match->getTeamB()->exists()) {
                    $match->setTeamBName($match->getTeamB()->getName());
                    $match->setTeamBFlag($match->getTeamB()->getFlag());
                }

                $side = $request->getPostParameter("side");
                if (!in_array($side, array("ct", "t"))) {
                    $side = rand(100) > 50 ? "ct" : "t";
                }

                $server = null;
                $server_id = $request->getPostParameter("server_id");
                if (is_numeric($server_id) && $server_id != 0) {
                    $server = ServersTable::getInstance()->find($server_id);
                    if ($server) {
                        $match->setIp($server->getIp());
                        $match->setServer($server);
                    }
                }

                if ($match->getAutoStart()) {
                    if ($match->getStartdate() == NULL)
                        $match->setAutoStart(false);
                }

                $maps = new Maps();
                $maps->setMatch($match);
                $maps->setMapsFor("default");
                $maps->setNbOt(0);
                $maps->setStatus(0);
                $maps->score_1 = 0;
                $maps->score_2 = 0;
                $maps->current_side = $side;
                $maps->setMapName($request->getPostParameter("maps"));
                $maps->save();

                $match->setScoreA(0);
                $match->setScoreB(0);
                $match->setCurrentMap($maps);
                $match->setStatus(Matchs::STATUS_NOT_STARTED);
                $match->setConfigAuthkey(uniqid(mt_rand(), true));
                $match->save();

                $this->getUser()->setFlash("notification_ok", $this->__("Match created with ID") . " " . $match->getId());

                $this->redirect("matchs_create");
            }
        }
    }

    public function executeEdit(sfWebRequest $request) {
        $this->match = $this->getRoute()->getObject();
        $this->forward404Unless($this->match);
        $this->maps = sfConfig::get("app_maps");
        array_push($this->maps, 'tba');
        $this->servers = ServersTable::getInstance()->findAll();

        if ($this->match->getEnable()) {
            $this->getUser()->setFlash("notification_error", $this->__("Match is currently in progress, can't be edited."));
            $this->redirect("matchs_current");
        }

        $this->form = new MatchsForm($this->match);

        $this->formScores = array();
        $scores = MapsScoreTable::getInstance()->createQuery()->where("map_id = ?", $this->match->getMap()->getId())->orderBy("id ASC")->execute();
        foreach ($scores as $score) {
            $this->formScores[] = new MapsScoreForm($score);
        }

        if ($request->getMethod() == sfWebRequest::POST) {
            $this->form->bind($request->getPostParameter($this->form->getName()));
            if ($this->form->isValid() && in_array($_POST["maps"], $this->maps)) {

                $server = null;
                $server_id = $request->getPostParameter("server_id");
                if (is_numeric($server_id) && $server_id != 0) {
                    $server = ServersTable::getInstance()->find($server_id);
                    $this->forward404Unless($server && $server->exists());
                }

                if (is_null($server)) {
                    $matchs = MatchsTable::getInstance()->getMatchsInProgressQuery()->andWhere("enable = ?", 1)->andWhere("status < ? ", Matchs::STATUS_END_MATCH)->andWhere("status > ? ", Matchs::STATUS_NOT_STARTED)->execute();
                    $servers = ServersTable::getInstance()->createQuery()->orderBy("RAND()")->execute();
                    $used = array();
                    foreach ($matchs as $m) {
                        $used[] = $m->getServer()->getIp();
                    }

                    foreach ($servers as $s) {
                        if (in_array($s->getIp(), $used)) {
                            continue;
                        }

                        $server = $s;
                        break;
                    }
                }

                if (is_null($server)) {
                    $this->getUser()->setFlash("notification_error", $this->__("No Server available"));
                    $this->redirect("matchs_current");
                }

                $match = $this->form->save();
                $match->setIp($server->getIp());
                $match->setServer($server);
                if ($match->getAutoStart()) {
                    if ($match->getStartdate() == NULL)
                        $match->setAutoStart(false);
                }
                $match->save();

                $map = $match->getMap();
                $map->setMapName($_POST["maps"]);
                $map->save();

                $this->getUser()->setFlash("notification_ok", $this->__("Match edited successfully"));
                $this->redirect("matchs_current");
            }
        }
    }

    public function executeEditScore(sfWebRequest $request) {
        $this->map_score = $this->getRoute()->getObject();
        $this->match = $this->map_score->getMap()->getMatch();
        $this->forward404Unless($this->match);

        if ($this->match->getEnable()) {
            $this->getUser()->setFlash("notification_error", $this->__("Match is currently in progress."));
            $this->redirect("matchs_current");
        }

        if ($request->getMethod() == sfWebRequest::POST) {
            $this->form = new MapsScoreForm($this->map_score);
            $this->form->bind($request->getPostParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->save();

                MapsScoreTable::getInstance()->clear();
                $score_a = 0;
                $score_b = 0;
                $map = $this->match->getMap();
                foreach ($map->getMapsScore() as $score) {
                    $score_a += $score->getScore1Side1() + $score->getScore1Side2();
                    $score_b += $score->getScore2Side1() + $score->getScore2Side2();
                }

                $map->score_1 = $score_a;
                $map->score_2 = $score_b;
                $map->save();

                $this->match->setScoreA($score_a);
                $this->match->setScoreB($score_b);
                $this->match->save();

                $this->getUser()->setFlash("notification_ok", $this->__("Score updated successfully - New Score") . ": " . $score_a . " - " . $score_b);
                $this->redirect($this->generateUrl("matchs_edit", $this->match));
            } else {
                $this->getUser()->setFlash("notification_error", $this->__("Error, invalid data."));
                $this->redirect($this->generateUrl("matchs_edit", $this->match));
            }
        } else {
            $this->getUser()->setFlash("notification_error", $this->__("Error, invalid data."));
            $this->redirect($this->generateUrl("matchs_edit", $this->match));
        }
    }

    public function executeView(sfWebRequest $request) {
        $this->match = $this->getRoute()->getObject();
        $this->ebot_ip = sfConfig::get("app_ebot_ip");
        $this->ebot_port = sfConfig::get("app_ebot_port");

        $this->heatmap = PlayersHeatmapTable::getInstance()->createQuery()->where("match_id = ?", $this->match->getId())->count() > 0;
        if ($this->heatmap) {
            if (class_exists($this->match->getMap()->getMapName())) {
                $map = $this->match->getMap()->getMapName();
                $this->class_heatmap = new $map($this->match->getId());
            } else {
                $this->heatmap = false;
            }
        }
    }

    public function executeOpenRcon(sfWebRequest $request) {
        $this->match = $this->getRoute()->getObject();
        $this->ebot_ip = sfConfig::get("app_ebot_ip");
        $this->ebot_port = sfConfig::get("app_ebot_port");
        $this->crypt_key = $this->match->getConfigAuthkey();
    }

    public function executeHeatmapData(sfWebRequest $request) {
        $this->match = $this->getRoute()->getObject();

        $this->heatmap = PlayersHeatmapTable::getInstance()->createQuery()->where("match_id = ?", $this->match->getId())->count() > 0;
        if ($this->heatmap) {
            if (class_exists($this->match->getMap()->getMapName())) {
                $map = $this->match->getMap()->getMapName();
                $this->class_heatmap = new $map($this->match->getId());
            } else {
                $this->heatmap = false;
            }
        }

        $map = $this->class_heatmap;
        foreach ($this->match->getPlayersHeatmap() as $event) {
            $map->addInformation($event->getId(), $event->getEventName(), $event->getEventX(), $event->getEventY(), $event->getPlayerId(), ($event->getPlayerTeam() == "CT") ? 1 : 2, $event->getRoundId(), $event->getRoundTime(), 0, 1, $event->getAttackerX(), $event->getAttackerY(), $event->getAttackerName(), $event->getAttackerTeam());
        }

        $type = $request->getPostParameter("type", "kill");

        $points = array();

        if ($type == "allstuff") {
            $points = array_merge($map->buildHeatMap("hegrenade"), $map->buildHeatMap("flashbang"), $map->buildHeatMap("smokegrenade"), $map->buildHeatMap("decoy"), $map->buildHeatMap("molotov"));
        } else {
            $side = $request->getPostParameter("sides", -1);
            if ($side == "all") {
                $side = -1;
            } elseif ($side == "ct") {
                $side = 1;
            } elseif ($side == "t") {
                $side = 2;
            } else {
                $side = -1;
            }
            $points = $map->buildHeatMap($type, $request->getPostParameter("rounds", array()), $side, $request->getPostParameter("players", array()));
        }

        return $this->renderText(json_encode(array("type" => "heatmap", "points" => $points)));
    }

    public function executeLogs(sfWebRequest $request) {
        $match = $this->getRoute()->getObject();
        return $this->renderText(file_get_contents(sfConfig::get("app_log_match_admin") . "/match-" . $match->getId() . ".html"));
    }

}