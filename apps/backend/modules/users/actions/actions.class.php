<?php

/**
 * users actions.
 *
 * @package    PhpProject1
 * @subpackage users
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class usersActions extends sfActions {

    private function __($text, $args = array()) {
		return $this->getContext()->getI18N()->__($text, $args, 'messages');
	}

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {
        $this->users = sfGuardUserTable::getInstance()->findAll();
    }

    public function executeCreate(sfWebRequest $request) {
        $this->form = new sfGuardUserAdminForm();

        if ($request->getMethod() == sfWebRequest::POST) {
            $this->form->bind($request->getPostParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->save();
                $this->getUser()->setFlash("notification_ok", $this->__("User created successfully"));
                $this->redirect("users/create");
            }
            else {
                $this->getUser()->setFlash("notification_error", $this->__("User could not be added"));
            }
        }
    }

    public function executeEdit(sfWebRequest $request) {
        $this->user = $this->getRoute()->getObject();
        $this->form = new sfGuardUserAdminForm($this->user);

        if ($request->getMethod() == sfWebRequest::POST) {
            $this->form->bind($request->getPostParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->save();
                $this->getUser()->setFlash("notification_ok", $this->__("User edited successfully"));
                $this->redirect("users/index");
            }
        }
    }

    public function executeDelete($request) {
        $user = $this->getRoute()->getObject();
		$this->forward404Unless($user);
        $user->delete();
        $this->getUser()->setFlash("notification_ok", $this->__("User deleted successfully"));
		$this->redirect("users/index");
    }

}
