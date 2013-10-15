<?php

/**
 * teams actions.
 *
 * @package    PhpProject1
 * @subpackage teams
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class teamsActions extends sfActions {

    private function __($text, $args = array()) {
        return $this->getContext()->getI18N()->__($text, $args, 'messages');
    }

    public function executeIndex(sfWebRequest $request) {
        $table = TeamsTable::getInstance();
        $this->pager = null;
        $this->pager = new sfDoctrinePager('Teams', 12);
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();

        $this->url = "@teams_index";
    }

    public function executeCreate(sfWebRequest $request) {
        $this->form = new TeamsForm();
        $this->seasons = SeasonsTable::getInstance()->findAll();

        if ($request->getMethod() == sfWebRequest::POST) {
            $this->form->bind($request->getPostParameter($this->form->getName()));
            $seasons = $request->getPostParameter('seasons_list');

            if ($this->form->isValid()) {
                $team = $this->form->save();

                foreach ($seasons as $season) {
                    $teamsInSeasons = new TeamsInSeasons();
                    $teamsInSeasons->setSeasonId($season);
                    $teamsInSeasons->setTeams($team);
                    $teamsInSeasons->save();
                }

                $this->getUser()->setFlash("notification_ok", $this->__("Team created successfully."));
                $this->redirect("teams_create");
            } else {
                $this->getUser()->setFlash("notification_error", $this->__("Error, invalid form"));
            }

        }
    }

    public function executeEdit($request) {
        $this->team = $this->getRoute()->getObject();
        $this->form = new TeamsForm($this->team);
        $this->seasons = SeasonsTable::getInstance()->findAll();
        $this->currentSeasons = TeamsInSeasonsTable::getInstance()->createQuery('s')->select('s.season_id')->where('s.team_id = ?', $this->team->getId())->fetchArray();

        if ($request->getMethod() == sfWebRequest::POST) {
            $this->form->bind($request->getPostParameter($this->form->getName()));
            $seasons = $request->getPostParameter('seasons_list');

            if ($this->form->isValid()) {
                $team = $this->form->save();

                $deleted = TeamsInSeasonsTable::getInstance()->createQuery('s')->delete()->where('s.team_id = ?', $this->team->getId())->execute();
                foreach ($seasons as $season) {
                    $teamsInSeasons = new TeamsInSeasons();
                    $teamsInSeasons->setSeasonId($season);
                    $teamsInSeasons->setTeams($this->team);
                    $teamsInSeasons->save();
                }

                $this->getUser()->setFlash("notification_ok", $this->__("Team edited successfully."));
                $this->redirect("teams/index");
            } else {
                $this->getUser()->setFlash("notification_error", $this->__("Error, invalid form"));
            }
        }
    }

    public function executeDelete($request) {
        $team = $this->getRoute()->getObject();
        $this->forward404Unless($team);
        $team->delete();
        $this->getUser()->setFlash("notification_ok", $this->__("Team deleted successfully."));
        $this->redirect("teams/index");
    }

    public function executeView(sfWebRequest $request) {
        $this->team = $this->getRoute()->getObject();
        $this->forward404Unless($this->team);
    }

    public function executeTeamsInSeasons(sfWebRequest $request) {
        if ($request->getMethod() == sfWebRequest::POST) {
            if ($request->getPostParameter("season_id")) {
                $season_id = $request->getPostParameter("season_id");
                $getTeams = TeamsInSeasonsTable::getInstance()->createQuery('s')->select('s.team_id')->where('s.season_id = ?', $season_id)->fetchArray();
                $teams = array();
                for ($i=0;$i<count($getTeams);$i++) {
                    $teams['id'][] = $getTeams[$i]['team_id'];
                    $name = TeamsTable::getInstance()->createQuery('t')->select('t.name, t.flag')->where('t.id = ?', $getTeams[$i]['team_id'])->fetchArray();
                    $teams['name'][] = $name[0]['name'];
                    $teams['flag'][] = $name[0]['flag'];
                }
                echo json_encode($teams);
            }
        }
        return sfView::NONE;
    }

}
