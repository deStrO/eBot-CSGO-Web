<?php

/**
 * seasons actions.
 *
 * @package    PhpProject1
 * @subpackage seasons
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class seasonsActions extends sfActions {
    /**
    * Executes index action
    *
    * @param sfRequest $request A request object
    */
    public function executeIndex(sfWebRequest $request) {
        $this->current_seasons = Doctrine::getTable('Seasons')->createQuery('s')->andWhere('s.active = ?', '1')->orderBy('s.start')->execute();
        $this->inactive_seasons = Doctrine::getTable('Seasons')->createQuery('s')->andWhere('s.active = ?', '0')->orderBy('s.start DESC')->limit(5)->execute();
    }

    public function executeSelect($request) {
        $this->setFilters(array("season_id" => $request->getParameter('id')));
        if ($request->getParameter('site') == 'inprogress')
            $this->redirect('matchs_current');
        else
            $this->redirect('matchs_archived');
    }

    private function getFilters() {
		return $this->getUser()->getAttribute('matchs.filters', array(), 'admin_module');
	}

	private function setFilters($filters) {
		return $this->getUser()->setAttribute('matchs.filters', $filters, 'admin_module');
	}
}
