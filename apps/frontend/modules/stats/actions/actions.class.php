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

        $query = "SELECT `weapon`, count(*) as nb, p.steamid as steamid, p.pseudo as pseudo FROM player_kill pk LEFT JOIN players p ON p.id = pk.killer_id GROUP BY `steamid`, `weapon` ORDER BY `weapon`,`nb` DESC";
        $rs = Doctrine_Manager::getInstance()->getCurrentConnection()->fetchAssoc($query);
        foreach ($rs as $v) {
            $weaponsTOP[$v["weapon"]][$v["steamid"]]["nb"] = $v["nb"];
            $weaponsTOP[$v["weapon"]][$v["steamid"]]["pseudo"] = $v["pseudo"];
        }

        $this->weaponsTOP = $weaponsTOP;

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

    public function executeEntryKills(sfWebRequest $request) {
        $matchs = MatchsTable::getInstance()->findAll();
        $players = array();
        $players2 = array();
        $weapons = array();
        foreach ($matchs as $match) {
            $rounds = RoundSummaryTable::getInstance()->createQuery()->where("match_id = ?", $match->getId())->orderBy("round_id ASC")->execute();
            foreach ($rounds as $round) {
                $kill = PlayerKillTable::getInstance()->createQuery()->where("match_id = ?", $match->getId())->andWhere("round_id = ?", $round->getRoundId())->orderBy("created_at ASC")->limit(1)->fetchOne();
                if ($kill) {
                    $team = $kill->getKillerTeam() == "CT" ? "ct" : "t";
                    $team_killed = $kill->getKilledTeam() == "CT" ? "ct" : "t";
                    $weapons[$kill->getWeapon()]++;
                    if ($team == "ct" && $round->ct_win || $team == "t" && $round->t_win) {
                        @$players[$kill->getKiller()->getSteamId()]['name'] = $kill->getKillerName();
                        @$players[$kill->getKiller()->getSteamId()]['count']++;
                        @$players[$kill->getKiller()->getSteamId()]['weapons'][$kill->getWeapon()]++;
                    } else {
                        @$players[$kill->getKiller()->getSteamId()]['name'] = $kill->getKillerName();
                        @$players[$kill->getKiller()->getSteamId()]['loose']++;
                    }

                    @$players[$kill->getKiller()->getSteamId()]['matchs'][$match->getId()] = $match->getScoreA() + $match->getScoreB();
                    @$players2[$kill->getKilled()->getSteamId()]['matchs'][$match->getId()] = $match->getScoreA() + $match->getScoreB();

                    if ($team_killed == "ct" && $round->t_win || $team_killed == "t" && $round->ct_win) {
                        @$players2[$kill->getKilled()->getSteamId()]['name'] = $kill->getKilledName();
                        @$players2[$kill->getKilled()->getSteamId()]['count']++;
                    } else {
                        @$players2[$kill->getKilled()->getSteamId()]['name'] = $kill->getKilledName();
                        @$players2[$kill->getKilled()->getSteamId()]['loose']++;
                    }

                    $s = $kill->getKiller()->getTeam();
                    $name = "";
                    if ($s == "a") {
                        $name = $match->getTeamAName();
                    } elseif ($s == "b") {
                        $name = $match->getTeamBName();
                    }

                    if ($team == "ct" && $round->ct_win || $team == "t" && $round->t_win) {
                        @$teams[$name]['name'] = $name;
                        @$teams[$name]['count']++;
                    } else {
                        @$teams[$name]['name'] = $name;
                        @$teams[$name]['loose']++;
                    }

                    $name = "";
                    $s = $kill->getKilled()->getTeam();
                    if ($s == "a") {
                        $name = $match->getTeamAName();
                    } elseif ($s == "b") {
                        $name = $match->getTeamBName();
                    }

                    if ($team_killed == "ct" && $round->t_win || $team_killed == "t" && $round->ct_win) {
                        @$teams2[$name]['name'] = $name;
                        @$teams2[$name]['count']++;
                    } else {
                        @$teams2[$name]['name'] = $name;
                        @$teams2[$name]['loose']++;
                    }

                    @$teams[$name]['matchs'][$match->getId()] = $match->getScoreA() + $match->getScoreB();
                    @$teams2[$name]['matchs'][$match->getId()] = $match->getScoreA() + $match->getScoreB();
                }
            }
        }

        function cmp($a, $b) {
            if ($a['count'] == $b['count']) {
                return 0;
            }
            return ($a['count'] > $b['count']) ? -1 : 1;
        }

        uasort($players, "cmp");
        uasort($players2, "cmp");
        uasort($teams, "cmp");
        uasort($teams2, "cmp");
        arsort($weapons);

        $this->playersWin = $players;
        $this->playersLoose = $players2;
        $this->teamsWin = $teams;
        $this->teamsLoose = $teams2;
        $this->weapons = $weapons;
    }

    public function executeHeatmap(sfWebRequest $request) {
        $this->map = $request->getParameter("maps");
        $map = $request->getParameter("maps");
        $this->class_heatmap = new $map(0);
    }

    public function executeHeatmapDataMaps(sfWebRequest $request) {
        $maps = MapsTable::getInstance()->createQuery()->where("map_name = ?", $request->getParameter("maps"))->execute();

        if (class_exists($request->getParameter("maps"))) {
            $map = $request->getParameter("maps");
            $this->class_heatmap = new $map(0);
        } else {
            return $this->renderText(json_encode(array("type" => "heatmap", "points" => array())));
        }

        foreach ($maps as $map) {
            $this->match = $map->getMatch();
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
        }

        return $this->renderText(json_encode(array("type" => "heatmap", "points" => $points)));
    }

    public function executeGunround(sfWebRequest $request) {
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();

        $teamNames = array();
        foreach (MatchsTable::getInstance()->findAll() as $match) {
            $teamNames[$match->getTeamAName()]['count']++;
            $teamNames[$match->getTeamBName()]['count']++;

            if ($match->getScoreA() > $match->getScoreB()) {
                $teamNames[$match->getTeamAName()]['win']++;
                $teamNames[$match->getTeamBName()]['loose']++;
            } else {
                $teamNames[$match->getTeamAName()]['loose']++;
                $teamNames[$match->getTeamBName()]['win']++;
            }
        }

        $teamStats = array();

        foreach ($teamNames as $team => $v) {
            $statsGRA1 = Doctrine_Query::create()->select('count(*) as nb')->from('RoundSummary s')->leftJoin("s.Match m")->andWhere("m.team_a_name = ?", $team)->andWhere("s.team_win = ?", "a")->andWhere("s.round_id = ?", 1)->execute()->toArray();
            $statsGRA2 = Doctrine_Query::create()->select('count(*) as nb')->from('RoundSummary s')->leftJoin("s.Match m")->andWhere("m.team_a_name = ?", $team)->andWhere("s.team_win = ?", "a")->andWhere("s.round_id = ?", 16)->execute()->toArray();
            $statsGRB1 = Doctrine_Query::create()->select('count(*) as nb')->from('RoundSummary s')->leftJoin("s.Match m")->andWhere("m.team_b_name = ?", $team)->andWhere("s.team_win = ?", "b")->andWhere("s.round_id = ?", 1)->execute()->toArray();
            $statsGRB2 = Doctrine_Query::create()->select('count(*) as nb')->from('RoundSummary s')->leftJoin("s.Match m")->andWhere("m.team_b_name = ?", $team)->andWhere("s.team_win = ?", "b")->andWhere("s.round_id = ?", 16)->execute()->toArray();

            $statsA1 = Doctrine_Query::create()->select('s.map_id, count(*) as nb')->from('RoundSummary s')->leftJoin("s.Match m")->andWhere("m.team_a_name = ?", $team)->andWhere("s.team_win = ?", "a")->andWhere("s.round_id IN ?", array(array(1, 2)))->groupBy("s.map_id")->having("COUNT(*) = 2")->execute()->toArray();
            $statsA2 = Doctrine_Query::create()->select('s.map_id, count(*) as nb')->from('RoundSummary s')->leftJoin("s.Match m")->andWhere("m.team_a_name = ?", $team)->andWhere("s.team_win = ?", "a")->andWhere("s.round_id IN ?", array(array(16, 17)))->groupBy("s.map_id")->having("COUNT(*) = 2")->execute()->toArray();
            $statsB1 = Doctrine_Query::create()->select('s.map_id, count(*) as nb')->from('RoundSummary s')->leftJoin("s.Match m")->andWhere("m.team_b_name = ?", $team)->andWhere("s.team_win = ?", "b")->andWhere("s.round_id IN ?", array(array(1, 2)))->groupBy("s.map_id")->having("COUNT(*) = 2")->execute()->toArray();
            $statsB2 = Doctrine_Query::create()->select('s.map_id, count(*) as nb')->from('RoundSummary s')->leftJoin("s.Match m")->andWhere("m.team_b_name = ?", $team)->andWhere("s.team_win = ?", "b")->andWhere("s.round_id IN ?", array(array(16, 17)))->groupBy("s.map_id")->having("COUNT(*) = 2")->execute()->toArray();

            $statsEA1 = Doctrine_Query::create()->select('s.map_id, count(*) as nb')->from('RoundSummary s')->leftJoin("s.Match m")->andWhere("m.team_a_name = ?", $team)->andWhere("((s.round_id = ? AND s.team_win = 'b') OR (s.round_id = ? AND s.team_win = 'a'))", array(1, 2))->groupBy("s.map_id")->having("COUNT(*) = 2")->execute()->toArray();
            $statsEA2 = Doctrine_Query::create()->select('s.map_id, count(*) as nb')->from('RoundSummary s')->leftJoin("s.Match m")->andWhere("m.team_a_name = ?", $team)->andWhere("((s.round_id = ? AND s.team_win = 'b') OR (s.round_id = ? AND s.team_win = 'a'))", array(16, 17))->groupBy("s.map_id")->having("COUNT(*) = 2")->execute()->toArray();
            $statsEB1 = Doctrine_Query::create()->select('s.map_id, count(*) as nb')->from('RoundSummary s')->leftJoin("s.Match m")->andWhere("m.team_b_name = ?", $team)->andWhere("((s.round_id = ? AND s.team_win = 'a') OR (s.round_id = ? AND s.team_win = 'b'))", array(1, 2))->groupBy("s.map_id")->having("COUNT(*) = 2")->execute()->toArray();
            $statsEB2 = Doctrine_Query::create()->select('s.map_id, count(*) as nb')->from('RoundSummary s')->leftJoin("s.Match m")->andWhere("m.team_b_name = ?", $team)->andWhere("((s.round_id = ? AND s.team_win = 'a') OR (s.round_id = ? AND s.team_win = 'b'))", array(16, 17))->groupBy("s.map_id")->having("COUNT(*) = 2")->execute()->toArray();

            $statsLA1 = Doctrine_Query::create()->select('s.map_id, count(*) as nb')->from('RoundSummary s')->leftJoin("s.Match m")->andWhere("m.team_a_name = ?", $team)->andWhere("s.team_win = ?", "b")->andWhere("s.round_id IN ?", array(array(1, 2)))->groupBy("s.map_id")->having("COUNT(*) = 2")->execute()->toArray();
            $statsLA2 = Doctrine_Query::create()->select('s.map_id, count(*) as nb')->from('RoundSummary s')->leftJoin("s.Match m")->andWhere("m.team_a_name = ?", $team)->andWhere("s.team_win = ?", "b")->andWhere("s.round_id IN ?", array(array(16, 17)))->groupBy("s.map_id")->having("COUNT(*) = 2")->execute()->toArray();
            $statsLB1 = Doctrine_Query::create()->select('s.map_id, count(*) as nb')->from('RoundSummary s')->leftJoin("s.Match m")->andWhere("m.team_b_name = ?", $team)->andWhere("s.team_win = ?", "a")->andWhere("s.round_id IN ?", array(array(1, 2)))->groupBy("s.map_id")->having("COUNT(*) = 2")->execute()->toArray();
            $statsLB2 = Doctrine_Query::create()->select('s.map_id, count(*) as nb')->from('RoundSummary s')->leftJoin("s.Match m")->andWhere("m.team_b_name = ?", $team)->andWhere("s.team_win = ?", "a")->andWhere("s.round_id IN ?", array(array(16, 17)))->groupBy("s.map_id")->having("COUNT(*) = 2")->execute()->toArray();

            $statsTA1 = Doctrine_Query::create()->select('s.map_id, count(*) as nb')->from('RoundSummary s')->leftJoin("s.Match m")->andWhere("m.team_a_name = ?", $team)->andWhere("((s.round_id = ? AND s.team_win = 'a') OR (s.round_id = ? AND s.team_win = 'b'))", array(1, 2))->groupBy("s.map_id")->having("COUNT(*) = 2")->execute()->toArray();
            $statsTA2 = Doctrine_Query::create()->select('s.map_id, count(*) as nb')->from('RoundSummary s')->leftJoin("s.Match m")->andWhere("m.team_a_name = ?", $team)->andWhere("((s.round_id = ? AND s.team_win = 'a') OR (s.round_id = ? AND s.team_win = 'b'))", array(16, 17))->groupBy("s.map_id")->having("COUNT(*) = 2")->execute()->toArray();
            $statsTB1 = Doctrine_Query::create()->select('s.map_id, count(*) as nb')->from('RoundSummary s')->leftJoin("s.Match m")->andWhere("m.team_b_name = ?", $team)->andWhere("((s.round_id = ? AND s.team_win = 'b') OR (s.round_id = ? AND s.team_win = 'a'))", array(1, 2))->groupBy("s.map_id")->having("COUNT(*) = 2")->execute()->toArray();
            $statsTB2 = Doctrine_Query::create()->select('s.map_id, count(*) as nb')->from('RoundSummary s')->leftJoin("s.Match m")->andWhere("m.team_b_name = ?", $team)->andWhere("((s.round_id = ? AND s.team_win = 'b') OR (s.round_id = ? AND s.team_win = 'a'))", array(16, 17))->groupBy("s.map_id")->having("COUNT(*) = 2")->execute()->toArray();

            $stats = count($statsA1) + count($statsA2) + count($statsB1) + count($statsB2);
            $statsE = count($statsEA1) + count($statsEA2) + count($statsEB1) + count($statsEB2);
            $statsL = count($statsLA1) + count($statsLA2) + count($statsLB1) + count($statsLB2);
            $statsT = count($statsTA1) + count($statsTA2) + count($statsTB1) + count($statsTB2);
            $statsGR = $statsGRA1[0]['nb'] + $statsGRA2[0]['nb'] + $statsGRB1[0]['nb'] + $statsGRB2[0]['nb'];

            $teamStats[$team] = array(
                "stats" => $stats,
                "statsE" => $statsE,
                "statsL" => $statsL,
                "statsT" => $statsT,
                "statsGR" => $statsGR,
                "count" => $v['count'],
                "win" => $v['win'],
                "loose" => $v['loose']
            );
        }
        ksort($teamStats);
        $this->teamStats = $teamStats;
    }

}
