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

    public function executeStartAll(sfWebRequest $request) {
        $matchs = MatchsTable::getInstance()->createQuery()->where("status = ?", Matchs::STATUS_NOT_STARTED)->execute();
        if ($matchs->count() == 0) {
            $this->getUser()->setFlash("notification_error", "Il n'y a pas de match a lancé");
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
                $this->getUser()->setFlash("notification_error", "Il n'y a plus de serveur disponible");
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

        $this->getUser()->setFlash("notification_ok", "Le(s) match(s) a/ont été lancés");
        $this->redirect("matchs_current");
    }

    public function executeStop(sfWebRequest $request) {
        $match = $this->getRoute()->getObject();
        $this->forward404Unless($match);
        $this->forward404Unless($match->getEnable());
        $this->forward404Unless(($match->getStatus() >= Matchs::STATUS_WU_KNIFE) && ($match->getStatus() <= Matchs::STATUS_WU_OT_2_SIDE));

        $match->stop();

        $this->getUser()->setFlash("notification_ok", "La commande a été envoyée au serveur, le match sera arrêté");
        $this->redirect("matchs_current");
    }

    public function executeStopRS(sfWebRequest $request) {
        $match = $this->getRoute()->getObject();
        $this->forward404Unless($match);
        $this->forward404Unless($match->getEnable());
        $this->forward404Unless(($match->getStatus() >= Matchs::STATUS_WU_KNIFE) && ($match->getStatus() <= Matchs::STATUS_WU_OT_2_SIDE));

        $match->stop(true);

        $this->getUser()->setFlash("notification_ok", "La commande a été envoyée au serveur, le match sera arrêté");
        $this->redirect("matchs_current");
    }

    public function executeDelete(sfWebRequest $request) {
        $match = $this->getRoute()->getObject();
        $this->forward404Unless($match);
        $this->forward404Unless(!$match->getEnable() || $match->getStatus() == Matchs::STATUS_NOT_STARTED);

        $match->delete();

        $this->getUser()->setFlash("notification_ok", "Le match a été supprimé");
        $this->redirect("matchs_current");
    }

    public function executePassKnife(sfWebRequest $request) {
        $match = $this->getRoute()->getObject();
        $this->forward404Unless($match);
        $this->forward404Unless($match->getEnable());
        $this->forward404Unless(($match->getStatus() == Matchs::STATUS_WU_KNIFE));

        $match->passKnife();

        $this->getUser()->setFlash("notification_ok", "La commande a été envoyée au serveur, le knife sera skip");
        $this->redirect("matchs_current");
    }

    public function executeForceKnife(sfWebRequest $request) {
        $match = $this->getRoute()->getObject();
        $this->forward404Unless($match);
        $this->forward404Unless($match->getEnable());
        $this->forward404Unless(($match->getStatus() == Matchs::STATUS_WU_KNIFE));

        $match->forceKnife();

        $this->getUser()->setFlash("notification_ok", "La commande a été envoyée au serveur, le knife va être lancé");
        $this->redirect("matchs_current");
    }

    public function executeForceKnifeEnd(sfWebRequest $request) {
        $match = $this->getRoute()->getObject();
        $this->forward404Unless($match);
        $this->forward404Unless($match->getEnable());
        $this->forward404Unless(($match->getStatus() == Matchs::STATUS_END_KNIFE));

        $match->forceKnifeEnd();

        $this->getUser()->setFlash("notification_ok", "La commande a été envoyée au serveur, passage au warmup");
        $this->redirect("matchs_current");
    }

    public function executeForceStart(sfWebRequest $request) {
        $match = $this->getRoute()->getObject();
        $this->forward404Unless($match);
        $this->forward404Unless($match->getEnable());
        $this->forward404Unless(in_array($match->getStatus(), array(Matchs::STATUS_WU_1_SIDE, Matchs::STATUS_WU_2_SIDE, Matchs::STATUS_WU_OT_1_SIDE, Matchs::STATUS_WU_OT_2_SIDE)));

        $match->forceStart();

        $this->getUser()->setFlash("notification_ok", "La commande a été envoyée au serveur, forcage du démarrage du match");
        $this->redirect("matchs_current");
    }

    public function executeStopBack(sfWebRequest $request) {
        $match = $this->getRoute()->getObject();
        $this->forward404Unless($match);
        $this->forward404Unless($match->getEnable());
        $this->forward404Unless(in_array($match->getStatus(), array(Matchs::STATUS_FIRST_SIDE, Matchs::STATUS_SECOND_SIDE, Matchs::STATUS_OT_FIRST_SIDE, Matchs::STATUS_OT_SECOND_SIDE)));

        $match->stopBack();

        $this->getUser()->setFlash("notification_ok", "La commande a été envoyée au serveur, retour au warmup");
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
            $this->getUser()->setFlash("notification_error", "Pas de serveurs disponibles");
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

        $this->getUser()->setFlash("notification_ok", "Le match a été démarré sur " . $server->getIp());
        $this->redirect("matchs_current");
    }

    public function executeSetArchive(sfWebRequest $request) {
        $match = $this->getRoute()->getObject();
        $this->forward404Unless($match);
        $this->forward404Unless($match->getStatus() == Matchs::STATUS_END_MATCH);

        $match->setEnable(0);
        $match->setStatus(Matchs::STATUS_ARCHIVE);
        $match->save();

        $this->getUser()->setFlash("notification_ok", "Le match a été archivé");
        $this->redirect("matchs_current");
    }

    public function executeArchiveAll(sfWebRequest $request) {
        $matchs = MatchsTable::getInstance()->createQuery()->where("status = ?", Matchs::STATUS_END_MATCH)->execute();

        if ($matchs->count() == 0) {
            $this->getUser()->setFlash("notification_error", "Il n'y a pas de match à archiver");
            $this->redirect("matchs_current");
        }

        foreach ($matchs as $match) {
            $match->setEnable(0);
            $match->setStatus(Matchs::STATUS_ARCHIVE);
            $match->save();
        }

        $this->getUser()->setFlash("notification_ok", $matchs->count() . " a/ont été archivé(s)");
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

            $this->getUser()->setFlash("notification_ok", "Le match a été relancé sur " . $match->getServer()->getIp());
        } else {
            $this->getUser()->setFlash("notification_error", "Un match est déjà en cours avec " . $match->getServer()->getIp());
            $match->setIp(null);
            $match->setServer(null);
            $match->setEnable(0);
            $match->save();

            $this->getUser()->setFlash("notification_ok", "Le match a été détaché du serveur");
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
                        12
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
                        12
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

        if ($request->getMethod() == sfWebRequest::POST) {
            $this->form->bind($request->getPostParameter($this->form->getName()));
            if ($this->form->isValid() && in_array($_POST["maps"], $this->maps)) {
                $match = $this->form->save();

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
                $maps->current_side = "ct";
                $maps->setMapName($request->getPostParameter("maps"));
                $maps->save();

                $match->setScoreA(0);
                $match->setScoreB(0);
                $match->setCurrentMap($maps);
                $match->setStatus(Matchs::STATUS_NOT_STARTED);
                $match->save();

                $this->getUser()->setFlash("notification_ok", "Le match a été créé avec succès et porte l'ID " . $match->getId());

                $this->redirect("matchs_create");
            }
        }
    }

    public function executeEdit(sfWebRequest $request) {
        $this->match = $this->getRoute()->getObject();
        $this->forward404Unless($this->match);
        $this->maps = sfConfig::get("app_maps");

        if ($this->match->getEnable()) {
            $this->getUser()->setFlash("notification_error", "Vous ne pouvez pas editer un match qui est en cours");
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

                $map = $match->getMap();
                $map->setMapName($_POST["maps"]);
                $map->save();

                $this->getUser()->setFlash("notification_ok", "Le match a été sauvé avec succès");
                $this->redirect($this->generateUrl("matchs_edit", $this->match));
            }
        }
    }

    public function executeEditScore(sfWebRequest $request) {
        $this->map_score = $this->getRoute()->getObject();
        $this->match = $this->map_score->getMap()->getMatch();
        $this->forward404Unless($this->match);

        if ($this->match->getEnable()) {
            $this->getUser()->setFlash("notification_error", "Vous ne pouvez pas editer un match qui est en cours");
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

                $this->getUser()->setFlash("notification_ok", "Les scores ont été mis à jours - Nouveau score: " . $score_a . " - " . $score_b);
                $this->redirect($this->generateUrl("matchs_edit", $this->match));
            } else {
                $this->getUser()->setFlash("notification_error", "Erreur de donnée");
                $this->redirect($this->generateUrl("matchs_edit", $this->match));
            }
        } else {
            $this->getUser()->setFlash("notification_error", "Erreur de donnée");
            $this->redirect($this->generateUrl("matchs_edit", $this->match));
        }
    }

    public function executeView(sfWebRequest $request) {
        $this->match = $this->getRoute()->getObject();
    }

    public function executeLogs(sfWebRequest $request) {
        $match = $this->getRoute()->getObject();
        return $this->renderText(file_get_contents(sfConfig::get("app_log_match_admin") . "/match-" . $match->getId() . ".html"));
    }
    
}
