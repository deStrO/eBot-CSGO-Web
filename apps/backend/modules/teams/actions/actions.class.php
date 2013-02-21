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
        $this->teams = TeamsTable::getInstance()->findAll();
    }

    public function executeCreate(sfWebRequest $request) {
        $this->form = new TeamsForm();
        if ($request->getMethod() == sfWebRequest::POST) {
            $this->form->bind($request->getPostParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $datas = $request->getPostParameter($this->form->getName());
                $datas["name"] = $this->form->getValue("name");
                $datas["shorthandle"] = $this->form->getValue("shorthandle");
                $datas["flag"] = $this->form->getValue("flag");
                $datas["link"] = $this->form->getValue("link");
                $this->form = new TeamsForm();
                $this->form->bind($datas);
                $this->form->save();

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
        if ($request->getMethod() == sfWebRequest::POST) {
            $this->form->bind($request->getPostParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->save();

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

}
