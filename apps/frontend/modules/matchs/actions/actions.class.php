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

    public function executeMatchsInProgress(sfWebRequest $request) {
        $this->filter = new MatchsActiveFormFilter($this->getFilters());
        $query = $this->filter->buildQuery($this->getFilters());
        $this->filterValues = $this->getFilters();

        $table = MatchsTable::getInstance();
        $this->pager = null;
        $this->pager = new sfDoctrinePager(
                'Matchs', 12
        );
        $this->pager->setQuery($query->andWhere("status >= ? AND status <= ?", array(Matchs::STATUS_NOT_STARTED, Matchs::STATUS_END_MATCH))->orderBy("status DESC"));
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();

        $this->page = $request->getParameter('page', 1);
        $this->url = "@matchs_current_page";

        $this->servers = ServersTable::getInstance()->findAll();
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
        return $this->renderText(file_get_contents(sfConfig::get("app_log_match") . "/match-" . $match->getId() . ".html"));
    }

    public function executeExternalLivemap(sfWebRequest $request) {
        $this->setLayout("layout_external");
        $this->forward404Unless($request->getParameter("id"));
        $this->match = MatchsTable::getInstance()->find($request->getParameter("id"));
        $this->ebot_ip = sfConfig::get("app_ebot_ip");
        $this->ebot_port = sfConfig::get("app_ebot_port");
    }

    public function executeExternalCoverage(sfWebRequest $request) {
        $this->setLayout("layout_external");
        $this->forward404Unless($request->getParameter("id"));
        $this->match = MatchsTable::getInstance()->find($request->getParameter("id"));
        $this->ebot_ip = sfConfig::get("app_ebot_ip");
        $this->ebot_port = sfConfig::get("app_ebot_port");
    }

    public function executeDemo(sfWebRequest $request) {
        $this->map = $this->getRoute()->getObject();
        $this->forward404Unless($this->map);
        $this->demo = MapsTable::getInstance()->createQuery()->select("tv_record_file")->where("id = ?", $request->getParameter("id"))->execute();
        if (file_exists($demo_file = sfConfig::get("app_demo_path") . DIRECTORY_SEPARATOR . $this->demo[0]->getTvRecordFile() . ".dem.zip") && !preg_match('=/=', $this->demo[0]->getTvRecordFile())) {
            $apache_modules = array();
            if (function_exists("apache_get_modules"))
                $apache_modules = apache_get_modules();
            if (in_array("mod_xsendfile", $apache_modules)) {
                header("X-Sendfile: $demo_file");
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=" . $this->demo[0]->getTvRecordFile() . ".dem.zip");
            } else {
                header("Content-Type: application/octet-stream");
                header("Content-Disposition: attachment; filename=" . $this->demo[0]->getTvRecordFile() . ".dem.zip");
                readfile($demo_file);
            }
        }
        return sfView::NONE;
    }

    public function executeExternalTvStats(sfWebRequest $request) {
        $this->setLayout("layout_external");
        $this->forward404Unless($request->getParameter("id"));
        $this->match = MatchsTable::getInstance()->find($request->getParameter("id"));

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

    public function executeExternalTvStatsGunround(sfWebRequest $request) {
        $this->setLayout("layout_external");
        $this->forward404Unless($request->getParameter("id"));
        $this->match = MatchsTable::getInstance()->find($request->getParameter("id"));
    }

    public function executeExternalTvStatsTeams(sfWebRequest $request) {
        $this->setLayout("layout_external");
        $this->forward404Unless($request->getParameter("ids"));
        $this->forward404Unless($request->getParameter("team1"));
        $this->forward404Unless($request->getParameter("team2"));
        $this->matchs = MatchsTable::getInstance()->createQuery()->where("id IN ?", array(explode(",", $request->getParameter("ids"))))->execute();
        $this->team1 = TeamsTable::getInstance()->find($request->getParameter("team1"));
        $this->team2 = TeamsTable::getInstance()->find($request->getParameter("team2"));
    }

    public function executeExternalLiveStats(sfWebRequest $request) {
        $this->setLayout("layout_external");
        $this->ebot_ip = sfConfig::get("app_ebot_ip");
        $this->ebot_port = sfConfig::get("app_ebot_port");

        $this->filter = new MatchsActiveFormFilter($this->getFilters());
        $query = $this->filter->buildQuery($this->getFilters());
        $this->filterValues = $this->getFilters();

        $type = $request->getParameter('type', 'live');

        if ($type == 'live')
            $this->matchs = $query->andWhere("status >= ?", array(Matchs::STATUS_NOT_STARTED))->andWhere("status <= ?", array(Matchs::STATUS_END_MATCH))->orderBy("enable DESC, status DESC")->limit(15)->execute();
        /* elseif ($type == 'archived')
          $this->matchs = $query->andWhere("status >= ?", array(Matchs::STATUS_NOT_STARTED))->andWhere("status <= ?", array(Matchs::STATUS_ARCHIVE))->andWhere("updated_at <= ADDTIME(NOW(), )"->orderBy("id ASC$
         */
    }

    public function executeDemos(sfWebRequest $request) {
        
    }

    public function executeExportPlayers(sfWebRequest $request) {
        $this->match = $this->getRoute()->getObject();
        $players = $this->match->getPlayers();

        $stats = array();
        foreach ($players as $player) {
            if ($player->team == "other")
                continue;
            $stats[$player->getSteamid()] = array(
                "userName" => $player->getPseudo(),
                "steamId" => $player->getSteamid(),
                "v1" => $player->nb1,
                "v2" => $player->nb2,
                "v3" => $player->nb3,
                "v4" => $player->nb4,
                "v5" => $player->nb5,
                "nb1kill" => $player->nb1kill,
                "nb2kill" => $player->nb2kill,
                "nb3kill" => $player->nb3kill,
                "nb4kill" => $player->nb4kill,
                "nb5kill" => $player->nb5kill,
                "kill" => $player->nb_kill,
                "death" => $player->death,
                "headshot" => $player->hs,
                "point" => $player->point,
                "tk" => $player->tk,
                "defuse" => $player->defuse,
                "bombe" => $player->bombe,
                "assist" => $player->assist,
                "firstKill" => $player->firstkill,
            );
        }

        return $this->renderText(json_encode($stats));
    }

    public function executeExportRounds(sfWebRequest $request) {
        $this->match = $this->getRoute()->getObject();
        $rounds = $this->match->getRoundSummaries();

        $stats = array();

        $side = $this->match->getMap()->getCurrentSide() == "ct" ? "t" : "ct";
        $scoreCT = 0;
        $scoreT = 0;

        foreach ($rounds as $round) {
            $data = unserialize(utf8_decode($round->getBestActionParam()));
            $player = PlayersTable::getInstance()->find($data['player']);

            switch ($round->best_action_type) {
                case "1kill":
                    $bestAction = array(
                        "type" => "kill",
                        "nb" => 1,
                        "player" => $player->getSteamid()
                    );
                    break;
                case "2kill":
                    $bestAction = array(
                        "type" => "kill",
                        "nb" => 2,
                        "player" => $player->getSteamid()
                    );
                    break;
                case "3kill":
                    $bestAction = array(
                        "type" => "kill",
                        "nb" => 3,
                        "player" => $player->getSteamid()
                    );
                    break;
                case "4kill":
                    $bestAction = array(
                        "type" => "kill",
                        "nb" => 4,
                        "player" => $player->getSteamid()
                    );
                    break;
                case "5kill":
                    $bestAction = array(
                        "type" => "kill",
                        "nb" => 5,
                        "player" => $player->getSteamid()
                    );
                    break;
                case "1v1":
                    $bestAction = array(
                        "type" => "cluch",
                        "situation" => 1,
                        "player" => $player->getSteamid()
                    );
                    break;
                case "1v2":
                    $bestAction = array(
                        "type" => "cluch",
                        "situation" => 2,
                        "player" => $player->getSteamid()
                    );
                    break;
                case "1v3":
                    $bestAction = array(
                        "type" => "cluch",
                        "situation" => 3,
                        "player" => $player->getSteamid()
                    );
                    break;
                case "1v4":
                    $bestAction = array(
                        "type" => "cluch",
                        "situation" => 4,
                        "player" => $player->getSteamid()
                    );
                    break;
                case "1v5":
                    $bestAction = array(
                        "type" => "cluch",
                        "situation" => 5,
                        "player" => $player->getSteamid()
                    );
                    break;
            }

            if ($side == "ct") {
                $scoreA = $round->getScoreA();
                $scoreB = $round->getScoreB();
            } else {
                $scoreB = $round->getScoreA();
                $scoreA = $round->getScoreB();
            }

            if ($round->getRoundId() > 15) {
                if ($side == "t") {
                    $scoreA = $round->getScoreA();
                    $scoreB = $round->getScoreB();
                } else {
                    $scoreB = $round->getScoreA();
                    $scoreA = $round->getScoreB();
                }
            }



            if ($round->getCtWin())
                $scoreCT++;
            else
                $scoreT++;


            $stats[$round->getRoundId()] = array(
                "roundId" => (integer) $round->getRoundId(),
                "bombPlanted" => $round->getBombPlanted(),
                "bombDefused" => $round->getBombDefused(),
                "bombExploded" => $round->getBombExploded(),
                "winType" => $round->getWinType(),
                "teamWin" => $round->getCtWin() ? "CT" : "TERRORIST",
                "ctWin" => $round->getCtWin(),
                "tWin" => $round->getTWin(),
                "scoreCT" => $scoreA,
                "scoreT" => $scoreB,
                "bestKiller" => array(
                    "player" => $round->getBestKiller()->getSteamid(),
                    "nb" => (integer) $round->getBestKillerNb(),
                    "fk" => $round->getBestKillerFk(),
                ),
                "bestAction" => $bestAction
            );
        }


        return $this->renderText(json_encode($stats));
    }

    public function executeExportKills(sfWebRequest $request) {
        $this->match = $this->getRoute()->getObject();

        $stats = array();
        $rounds = $this->match->getRoundSummaries();

        $stats = array();

        foreach ($rounds as $round) {
            $events = PlayersHeatmapTable::getInstance()->createQuery()->where("match_id = ?", $this->match->getId())->andWhere("round_id	= ?", $round->getRoundId())->andWhere("event_name = ?", "kill")->orderBy("id ASC")->execute();

            foreach ($events as $event) {
                $kill = PlayerKillTable::getInstance()->createQuery()->where("match_id = ?", $this->match->getId())->andWhere("round_id	= ?", $round->getRoundId())->andWhere("killer_id = ?", $event->getAttackerId())->andWhere("killed_id = ?", $event->getPlayerId())->fetchOne();
                if ($kill)
                    $stats[$round->getRoundId()][] = array(
                        "killerUserName" => $event->getAttackerName(),
                        "killerUserId" => $event->getAttackerId(),
                        "killerUserSteamId" => $event->getKiller()->getSteamid(),
                        "killerUserTeam" => $event->getAttackerTeam(),
                        "killerPosX" => $event->getAttackerX(),
                        "killerPosY" => $event->getAttackerY(),
                        "killerPosZ" => $event->getAttackerZ(),
                        "killedUserName" => $event->getPlayerName(),
                        "killedUserId" => $event->getPlayerId(),
                        "killedUserSteamId" => $event->getPlayer()->getSteamid(),
                        "killedUserTeam" => $event->getPlayerTeam(),
                        "killedPosX" => $event->getEventX(),
                        "killedPosY" => $event->getEventY(),
                        "killedPosZ" => $event->getEventZ(),
                        "weapon" => $kill->getWeapon(),
                        "headshot" => $kill->getHeadshot(),
                    );
            }
        }

        return $this->renderText(json_encode($stats));
    }

    public function executeExportEstats(sfWebRequest $request) {
        $mO = $this->getRoute()->getObject();
        $stats = array();

        $match = $mO->toArray();
        unset($match['id'], $match['config_password'], $match['season_id'], $match['server_id'], $match['rules'], $match['team_a'], $match['team_b'], $match['config_authkey'], $match['current_map']);
        $stats['match'] = $match;

        foreach ($mO->getMaps() as $mapO) {
            $map = $mapO->toArray();
            unset($map['match_id'], $map['id']);
            $stats['map'] = $map;

            foreach ($mapO->getMapsScore() as $sO) {
                $score = $sO->toArray();
                unset($score['id'], $score['map_id']);
                $stats['scores'][] = $score;
            }
        }

        foreach ($mO->getPlayers() as $pO) {
            $player = $pO->toArray();
            unset($player['map_id'], $player['match_id'], $player['ip']);
            $stats['players'][] = $player;
        }


        $heatmap = PlayersHeatmapTable::getInstance()->createQuery()->where("match_id = ?", $mO->getId())->orderBy("created_at ASC")->execute();
        foreach ($heatmap as $hO) {
            $data = $hO->toArray();
            unset($data['id'], $data['match_id'], $data['map_id']);
            $stats['heatmap'][] = $data;
        }

        $kills = PlayerKillTable::getInstance()->createQuery()->where("match_id = ?", $mO->getId())->orderBy("created_at ASC")->execute();
        foreach ($kills as $hO) {
            $data = $hO->toArray();
            unset($data['match_id'], $data['map_id']);
            $stats['kills'][] = $data;
        }
        
        $kills = RoundTable::getInstance()->createQuery()->where("match_id = ?", $mO->getId())->orderBy("created_at ASC")->execute();
        foreach ($kills as $hO) {
            $data = $hO->toArray();
            unset($data['id'], $data['match_id'], $data['map_id']);
            $stats['rounds'][] = $data;
        }
        
        $kills = RoundSummaryTable::getInstance()->createQuery()->where("match_id = ?", $mO->getId())->orderBy("created_at ASC")->execute();
        foreach ($kills as $hO) {
            $data = $hO->toArray();
            unset($data['id'], $data['match_id'], $data['map_id']);
            $stats['summary'][] = $data;
        }

        return $this->renderText(json_encode($stats));
    }

}
