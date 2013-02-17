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
            $this->getUser()->setFlash("notification_error", $this->__("Il n'y a pas de match a lancé"));
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
                $this->getUser()->setFlash("notification_error", $this->__("Il n'y a plus de serveur disponible"));
                $this->redirect("matchs_current");
            }

            $match->setIp($server->getIp());
            $match->setServer($server);
            $match->setEnable(1);
            $match->setStatus(Matchs::STATUS_STARTING);
            $match->setScoreA(0);
            $match->setScoreB(0);
            $match->save();
        }

        $this->getUser()->setFlash("notification_ok", $this->__("Le(s) match(s) a/ont été lancés"));
        $this->redirect("matchs_current");
    }

    public function executeStop(sfWebRequest $request) {
        $match = $this->getRoute()->getObject();
        $this->forward404Unless($match);
        $this->forward404Unless($match->getEnable());
        $this->forward404Unless(($match->getStatus() >= Matchs::STATUS_WU_KNIFE) && ($match->getStatus() <= Matchs::STATUS_WU_OT_2_SIDE));

        $match->stop();

        $this->getUser()->setFlash("notification_ok", $this->__("La commande a été envoyée au serveur, le match sera arrêté"));
        $this->redirect("matchs_current");
    }

    public function executeStopRS(sfWebRequest $request) {
        $match = $this->getRoute()->getObject();
        $this->forward404Unless($match);
        $this->forward404Unless($match->getEnable());
        $this->forward404Unless(($match->getStatus() >= Matchs::STATUS_WU_KNIFE) && ($match->getStatus() <= Matchs::STATUS_WU_OT_2_SIDE));

        $match->stop(true);

        $this->getUser()->setFlash("notification_ok", $this->__("La commande a été envoyée au serveur, le match sera arrêté"));
        $this->redirect("matchs_current");
    }

    public function executeDelete(sfWebRequest $request) {
        $match = $this->getRoute()->getObject();
        $this->forward404Unless($match);
        $this->forward404Unless(!$match->getEnable() || $match->getStatus() == Matchs::STATUS_NOT_STARTED || $match->getStatus() == Matchs::STATUS_END_MATCH);

        $match->delete();

        $this->getUser()->setFlash("notification_ok", $this->__("Le match a été supprimé"));
        $this->redirect($request->getReferer());
    }

    public function executePassKnife(sfWebRequest $request) {
        $match = $this->getRoute()->getObject();
        $this->forward404Unless($match);
        $this->forward404Unless($match->getEnable());
        $this->forward404Unless(($match->getStatus() == Matchs::STATUS_WU_KNIFE));

        $match->passKnife();

        $this->getUser()->setFlash("notification_ok", $this->__("La commande a été envoyée au serveur, le knife sera skip"));
        $this->redirect("matchs_current");
    }

    public function executeForceKnife(sfWebRequest $request) {
        $match = $this->getRoute()->getObject();
        $this->forward404Unless($match);
        $this->forward404Unless($match->getEnable());
        $this->forward404Unless(($match->getStatus() == Matchs::STATUS_WU_KNIFE));

        $match->forceKnife();

        $this->getUser()->setFlash("notification_ok", $this->__("La commande a été envoyée au serveur, le knife va être lancé"));
        $this->redirect("matchs_current");
    }

    public function executeForceKnifeEnd(sfWebRequest $request) {
        $match = $this->getRoute()->getObject();
        $this->forward404Unless($match);
        $this->forward404Unless($match->getEnable());
        $this->forward404Unless(($match->getStatus() == Matchs::STATUS_END_KNIFE));

        $match->forceKnifeEnd();

        $this->getUser()->setFlash("notification_ok", $this->__("La commande a été envoyée au serveur, passage au warmup"));
        $this->redirect("matchs_current");
    }

    public function executeForceStart(sfWebRequest $request) {
        $match = $this->getRoute()->getObject();
        $this->forward404Unless($match);
        $this->forward404Unless($match->getEnable());
        $this->forward404Unless(in_array($match->getStatus(), array(Matchs::STATUS_WU_1_SIDE, Matchs::STATUS_WU_2_SIDE, Matchs::STATUS_WU_OT_1_SIDE, Matchs::STATUS_WU_OT_2_SIDE)));

        $match->forceStart();

        $this->getUser()->setFlash("notification_ok", $this->__("La commande a été envoyée au serveur, forcage du démarrage du match"));
        $this->redirect("matchs_current");
    }

    public function executeStopBack(sfWebRequest $request) {
        $match = $this->getRoute()->getObject();
        $this->forward404Unless($match);
        $this->forward404Unless($match->getEnable());
        $this->forward404Unless(in_array($match->getStatus(), array(Matchs::STATUS_FIRST_SIDE, Matchs::STATUS_SECOND_SIDE, Matchs::STATUS_OT_FIRST_SIDE, Matchs::STATUS_OT_SECOND_SIDE)));

        $match->stopBack();

        $this->getUser()->setFlash("notification_ok", $this->__("La commande a été envoyée au serveur, retour au warmup"));
        $this->redirect("matchs_current");
    }

    public function executePauseUnpause(sfWebRequest $request) {
        $match = $this->getRoute()->getObject();
        $this->forward404Unless($match);
        $this->forward404Unless($match->getEnable());
        $this->forward404Unless(in_array($match->getStatus(), array(Matchs::STATUS_FIRST_SIDE, Matchs::STATUS_SECOND_SIDE, Matchs::STATUS_OT_FIRST_SIDE, Matchs::STATUS_OT_SECOND_SIDE)));

        $match->pauseUnpause();

        $this->getUser()->setFlash("notification_ok", $this->__("La commande a été envoyée au serveur, pause"));
        $this->redirect("matchs_current");
    }

    public function executeStart(sfWebRequest $request) {
        $this->forward404Unless($request->getPostParameter("match_id"));
        $this->forward404Unless(is_numeric($request->getPostParameter("match_id")));

        $match = MatchsTable::getInstance()->find($request->getPostParameter("match_id"));
        $this->forward404Unless($match && $match->exists());

        $this->forward404Unless(!$match->getEnable());

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
            $this->getUser()->setFlash("notification_error", $this->__("Pas de serveurs disponibles"));
            $this->redirect("matchs_current");
        }

        $match->setIp($server->getIp());
        $match->setServer($server);
        $match->setEnable(1);
        if ($match->getStatus() == Matchs::STATUS_NOT_STARTED)
            $match->setStatus(Matchs::STATUS_STARTING);
        $match->setScoreA(0);
        $match->setScoreB(0);
        $match->save();

        $this->getUser()->setFlash("notification_ok", $this->__("Le match a été démarré sur ") . $server->getIp());
        $this->redirect("matchs_current");
    }

    public function executeSetArchive(sfWebRequest $request) {
        $match = $this->getRoute()->getObject();
        $this->forward404Unless($match);
        $this->forward404Unless($match->getStatus() == Matchs::STATUS_END_MATCH);

        $match->setEnable(0);
        $match->setStatus(Matchs::STATUS_ARCHIVE);
        $match->save();

        $this->getUser()->setFlash("notification_ok", $this->__("Le match a été archivé"));
        $this->redirect("matchs_current");
    }

    public function executeArchiveAll(sfWebRequest $request) {
        $matchs = MatchsTable::getInstance()->createQuery()->where("status = ?", Matchs::STATUS_END_MATCH)->execute();

        if ($matchs->count() == 0) {
            $this->getUser()->setFlash("notification_error", $this->__("Il n'y a pas de match à archiver"));
            $this->redirect("matchs_current");
        }

        foreach ($matchs as $match) {
            $match->setEnable(0);
            $match->setStatus(Matchs::STATUS_ARCHIVE);
            $match->save();
        }

        $this->getUser()->setFlash("notification_ok", $matchs->count() . $this->__(" matchs a/ont été archivé(s)"));
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
            $match->setIp(null);
            $match->setServer(null);
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
            $this->getUser()->setFlash("notification_ok", $this->__("Le match a été remis à zéro"));
        } else {
            $this->getUser()->setFlash("notification_ok", $this->__("Impossible de remettre à zéro"));
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
            $match->save();

            $this->getUser()->setFlash("notification_ok", $this->__("Le match a été relancé sur ") . $match->getServer()->getIp());
        } else {
            $this->getUser()->setFlash("notification_error", $this->__("Un match est déjà en cours avec ") . $match->getServer()->getIp());
            $match->setIp(null);
            $match->setServer(null);
            $match->setEnable(0);
            $match->save();

            $this->getUser()->setFlash("notification_ok", $this->__("Le match a été détaché du serveur"));
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
                        'Matchs',
                        25
        );
        $this->pager->setQuery($query->andWhere("status >= ? AND status <= ?", array(Matchs::STATUS_NOT_STARTED, Matchs::STATUS_END_MATCH))->orderBy("status ASC"));
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();

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
                        'Matchs',
                        25
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
        $this->servers = ServersTable::getInstance()->findAll();
        $this->maps = sfConfig::get("app_maps");

        if ($request->getMethod() == sfWebRequest::POST) {
            $this->form->bind($request->getPostParameter($this->form->getName()));
            if ($this->form->isValid() && in_array($_POST["maps"], $this->maps)) {
                $match = $this->form->save();

                $server = null;
                $server_id = $request->getPostParameter("server_id");
                if (is_numeric($server_id) && $server_id != 0) {
                    $server = ServersTable::getInstance()->find($server_id);
                    if (!$server) {
                        $this->getUser()->setFlash("notification.error", "Le serveur n'existe pas/plus");
                        $server = null;
                    } else {
                        $match->setServer($server);
                        $match->setIp($server->getIp());
                    }
                }

                $side = $request->getPostParameter("side");
                if (!in_array($side, array("ct", "t"))) {
                    $side = rand(100) > 50 ? "ct" : "t";
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
                $match->save();

                $this->getUser()->setFlash("notification_ok", $this->__("Le match a été créé avec succès et porte l'ID ") . $match->getId());

                $this->redirect("matchs_current");
            }
        }
    }

    public function executeEdit(sfWebRequest $request) {
        $this->match = $this->getRoute()->getObject();
        $this->forward404Unless($this->match);
        $this->maps = sfConfig::get("app_maps");
        $this->servers = ServersTable::getInstance()->findAll();

        if ($this->match->getEnable()) {
            $this->getUser()->setFlash("notification_error", $this->__("Vous ne pouvez pas editer un match qui est en cours"));
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
                $match = $this->form->save();

                if ($match->getStatus() == 0) {
                    $server = null;
                    $server_id = $request->getPostParameter("server_id");
                    if (is_numeric($server_id) && $server_id != 0) {
                        $server = ServersTable::getInstance()->find($server_id);
                        if (!$server) {
                            $this->getUser()->setFlash("notification.error", "Le serveur n'existe pas/plus");
                            $match->setServer(null);
                            $match->setIp(null);
                            $match->save();
                        } else {
                            $match->setServer($server);
                            $match->setIp($server->getIp());
                            $match->save();
                        }
                    } else {
                        $match->setServer(null);
                        $match->setIp(null);
                        $match->save();
                    }
                }

                $map = $match->getMap();
                $map->setMapName($_POST["maps"]);
                $map->save();

                $this->getUser()->setFlash("notification_ok", $this->__("Le match a été sauvé avec succès"));
                $this->redirect($this->generateUrl("matchs_edit", $this->match));
            }
        }
    }

    public function executeEditScore(sfWebRequest $request) {
        $this->map_score = $this->getRoute()->getObject();
        $this->match = $this->map_score->getMap()->getMatch();
        $this->forward404Unless($this->match);

        if ($this->match->getEnable()) {
            $this->getUser()->setFlash("notification_error", $this->__("Vous ne pouvez pas editer un match qui est en cours"));
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

                $this->getUser()->setFlash("notification_ok", $this->__("Les scores ont été mis à jours - Nouveau score: ") . $score_a . " - " . $score_b);
                $this->redirect($this->generateUrl("matchs_edit", $this->match));
            } else {
                $this->getUser()->setFlash("notification_error", $this->__("Erreur de donnée"));
                $this->redirect($this->generateUrl("matchs_edit", $this->match));
            }
        } else {
            $this->getUser()->setFlash("notification_error", $this->__("Erreur de donnée"));
            $this->redirect($this->generateUrl("matchs_edit", $this->match));
        }
    }

    public function executeView(sfWebRequest $request) {
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
