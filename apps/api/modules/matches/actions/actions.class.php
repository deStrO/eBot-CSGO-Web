<?php

/**
 * matchs actions.
 *
 * @package    PhpProject1
 * @subpackage matchs
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class matchesActions extends sfActions {
    public function executeIndex(sfWebRequest $request) {
        $this->forward('default', 'module');
    }
	
	public function executeList(sfWebRequest $request) {
		$matches = MatchsTable::getInstance()->createQuery()->where("status > ?", Matchs::STATUS_NOT_STARTED)->andWhere("status < ?", Matchs::STATUS_ARCHIVE)->execute();
		$result = array();
		foreach($matches as $m) {
			$logfile = file_get_contents(sfConfig::get("app_log_match") . "/match-" . $m->getId() . ".html");
			$match = array();
			preg_match("/(?P<date>\d+-\d+-\d+\s+\d+:\d+:\d+).*Launching RS/", $logfile, $match);
			$result[] = array(
				"matchId" 	=> $m->getId(),
				"date"		=> $match["date"],
				"status"	=> $m->getStatus() < Matchs::STATUS_END_MATCH ? "running" : "finished",
				"teams"		=> array(
					array(
						"id" 	=> $m->getTeamA()->exists() ? $m->getTeamA()->getId() : null,
						"name"	=> $m->getTeamA()->exists() ? $m->getTeamA()->getName() : $m->getTeamAName(),
						"alias"	=> $m->getTeamA()->exists() ? $m->getTeamA()->getShorthandle() : "",
						"score"	=> $m->getScoreA(),
					),
					array(
						"id" 	=> $m->getTeamB()->exists() ? $m->getTeamB()->getId() : null,
						"name"	=> $m->getTeamB()->exists() ? $m->getTeamB()->getName() : $m->getTeamBName(),
						"alias"	=> $m->getTeamB()->exists() ? $m->getTeamB()->getShorthandle() : "",
						"score"	=> $m->getScoreB(),
					)
				)
			);
		}
		return $this->renderAsJson($result);
	}

    public function executeView(sfWebRequest $request) {
        $this->match = $this->getRoute()->getObject();
		$result = array();
		foreach ($this->match->getMap()->getPlayer() as $p) {
			$player = array();
			$team = array();
			$stats = array();
            if ($p->getTeam() == "other") {
				continue;
			}
			
			$player['pseudo'] = $p->getPseudo();
			$player['steamid'] = $p->getSteamId();
			
			if ($p->getTeam() == "a") {
				$team['name'] = $this->match->getTeamA()->exists() ? $this->match->getTeamA()->getName() : $this->match->getTeamAName();
				$team['alias'] = $this->match->getTeamA()->exists() ? $this->match->getTeamA()->getShorthandle() : "";
			} else {
				$team['name'] = $this->match->getTeamB()->exists() ? $this->match->getTeamB()->getName() : $this->match->getTeamBName();
				$team['alias'] = $this->match->getTeamB()->exists() ? $this->match->getTeamB()->getShorthandle() : "";
			}	
			
            $stats['kill'] = $p->getNbKill();
            $stats['assist'] = $p->getAssist();
            $stats['death'] = $p->getDeath();
            $stats['hs'] = $p->getHs();
            $stats['bombe'] = $p->getBombe();
            $stats['defuse'] = $p->getDefuse();
            $stats['tk'] = $p->getTk();
            $stats['point'] = $p->getPoint();
            $stats['firstkill']= $p->getFirstkill();
            $stats['1v1'] = $p->getNb1();
            $stats['1v2'] = $p->getNb2();
            $stats['1v3'] = $p->getNb3();
            $stats['1v4'] = $p->getNb4();
            $stats['1v5'] = $p->getNb5();
            $stats['1kill'] = $p->getNb1kill();
            $stats['2kill'] = $p->getNb2kill();
            $stats['3kill'] = $p->getNb3kill();
            $stats['4kill'] = $p->getNb4kill();
            $stats['5kill'] = $p->getNb5kill();

            $clutch = 0;
            $clutch += 1 * $p->getNb1();
            $clutch += 2 * $p->getNb2();
            $clutch += 3 * $p->getNb3();
            $clutch += 4 * $p->getNb4();
            $clutch += 5 * $p->getNb5();
			$stats['clutch'] = $clutch;
			
			if ($p->getTeam() == "a") {
				$stats['win'] = $this->match->getScoreA();
				$stats['loss'] = $this->match->getScoreB();
			} else {
				$stats['win'] = $this->match->getScoreB();
				$stats['loss'] = $this->match->getScoreA();
			}
			
			$result[] = array(
				"player" 	=> $player,
				"team"		=> $team,
				"stats" 	=> $stats,
			);
		}
		return $this->renderAsJson($result);
    }
	
	private function renderAsJson($text) {
		$this->getResponse()->setContentType('application/json');
		return $this->renderText(json_encode($text));
	}
}
