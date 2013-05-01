<?php

/**
 * serverMessages actions.
 *
 * @package    PhpProject1
 * @subpackage serverMessages
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class advertisingActions extends sfActions {

    private function __($text, $args = array()) {
        return $this->getContext()->getI18N()->__($text, $args, 'messages');
	}

    public function executeIndex(sfWebRequest $request) {
        $this->advertising = AdvertisingTable::getInstance()->findAll();
    }

    public function executeCreate(sfWebRequest $request) {
        $this->form = new AdvertisingForm();
        if ($request->getMethod() == sfWebRequest::POST) {
            $this->form->bind($request->getPostParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->save();

                $this->getUser()->setFlash("notification_ok", $this->__("Advert added successfully"));
                $this->redirect("advertising/index");
            } else {
                $this->getUser()->setFlash("notification_error", $this->__("Error, invalid form"));
            }
        }
    }

    public function executeEdit($request) {
        $this->advertising = $this->getRoute()->getObject();
        $this->form = new AdvertisingForm($this->advertising);
        if ($request->getMethod() == sfWebRequest::POST) {
            $this->form->bind($request->getPostParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->save();

                $this->getUser()->setFlash("notification_ok", $this->__("Advert edited successfully."));
                $this->redirect("advertising/index");
            } else {
                $this->getUser()->setFlash("notification_error", $this->__("Error, invalid form"));
            }
        }
    }

    public function executeDelete($request) {
        $advert = $this->getRoute()->getObject();
		$this->forward404Unless($advert);
        $advert->delete();
        $this->getUser()->setFlash("notification_ok", $this->__("Advert deleted successfully"));
		$this->redirect("advertising/index");
    }

    public function executeDeactivate($request) {
        $advert = $this->getRoute()->getObject();
        $this->forward404Unless($advert);
        if ($advert->getActive())
            $advert->setActive(false);
        else
            $advert->setActive(true);
        $advert->save();
        $this->getUser()->setFlash("notification_ok", $this->__("Advert de/activated successfully"));
		$this->redirect("advertising/index");
    }


}
