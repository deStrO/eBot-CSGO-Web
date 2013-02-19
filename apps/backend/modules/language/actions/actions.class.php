<?php

/**
 * language actions.
 *
 * @package    tirex-admin
 * @subpackage language
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class languageActions extends sfActions {

	/**
	 * Executes index action
	 *
	 * @param sfRequest $request A request object
	 */
	public function executeSwitch(sfWebRequest $request) {
		$this->getUser()->setCulture($request->getParameter('langage'));

		if ($request->isMethod('POST') && $request->getReferer()) {
			$this->redirect($request->getReferer());
		} else {
			$this->redirect('@homepage');
		}
	}

}
