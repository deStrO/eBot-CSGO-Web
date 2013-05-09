<?php

/**
 * teams actions.
 *
 * @package    PhpProject1
 * @subpackage teams
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class seasonsActions extends sfActions {

    private function __($text, $args = array()) {
        return $this->getContext()->getI18N()->__($text, $args, 'messages');
	}

    public function executeIndex(sfWebRequest $request) {
        $this->seasons = SeasonsTable::getInstance()->findAll();
    }

    public function executeCreate(sfWebRequest $request) {
        $this->form = new SeasonsForm();
        if ($request->getMethod() == sfWebRequest::POST) {
            $this->form->bind($request->getPostParameter($this->form->getName()), $request->getFiles($this->form->getName()));
            if ($this->form->isValid()) {
                $datas = $request->getPostParameter($this->form->getName());
                $upload = $request->getFiles($this->form->getName());
                $datas["name"] = $this->form->getValue("name");
                $datas["link"] = $this->form->getValue("link");
                $datas["start"] = $this->form->getValue("start");
                $datas["end"] = $this->form->getValue("end");
                $this->form = new SeasonsForm();
                $this->form->bind($datas, $upload);
                $this->form->save();

                $this->getUser()->setFlash("notification_ok", $this->__("Season created successfully"));
                $this->redirect("seasons_create");
            } else {
                $this->getUser()->setFlash("notification_error", $this->__("Error, invalid form"));
            }
        }
    }

    public function executeEdit($request) {
        $this->season = $this->getRoute()->getObject();
        $this->form = new SeasonsForm($this->season);
        if ($request->getMethod() == sfWebRequest::POST) {
            $this->form->bind($request->getPostParameter($this->form->getName()), $request->getFiles($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->save();

                $this->getUser()->setFlash("notification_ok", $this->__("Season edited successfully."));
                $this->redirect("seasons/index");
            } else {
                $this->getUser()->setFlash("notification_error", $this->__("Error, invalid form"));
            }
        }
    }

    public function executeDelete($request) {
        $season = $this->getRoute()->getObject();
		$this->forward404Unless($season);
        $season->delete();
        $this->getUser()->setFlash("notification_ok", $this->__("Season deleted successfully"));
		$this->redirect("seasons/index");
    }

    public function executeDeactivate($request) {
        $season = $this->getRoute()->getObject();
        $this->forward404Unless($season);
        if ($season->getActive())
            $season->setActive(false);
        else
            $season->setActive(true);
        $season->save();
        $this->getUser()->setFlash("notification_ok", $this->__("Season de/activated successfully"));
		$this->redirect("seasons/index");
    }

}
