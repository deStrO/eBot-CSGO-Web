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
                $this->getUser()->setFlash("notification_ok", "L'utilisateur a été créé");
                $this->redirect("users/create");
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
                $this->getUser()->setFlash("notification_ok", "L'utilisateur a été édité");
                $this->redirect("users/index");
            }
        }
    }

}
