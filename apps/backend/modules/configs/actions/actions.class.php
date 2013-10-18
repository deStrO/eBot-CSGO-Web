<?php

/**
 * users actions.
 *
 * @package    PhpProject1
 * @subpackage users
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class configsActions extends sfActions {

    private function __($text, $args = array()) {
		return $this->getContext()->getI18N()->__($text, $args, 'messages');
	}

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {
        $this->configs = configsTable::getInstance()->findAll();
    }

    public function executeCreate(sfWebRequest $request) {
        $this->form = new configsForm();

        if ($request->getMethod() == sfWebRequest::POST) {
            $this->form->bind($request->getPostParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $config = $this->form->save();
                $config->setContent($request->getPostParameter('config'));
                $config->save();
                $this->getUser()->setFlash("notification_ok", $this->__("Config created successfully."));
                $this->redirect("configs/create");
            }
        }
    }

    public function executeEdit(sfWebRequest $request) {
        $this->config = $this->getRoute()->getObject();
        $this->form = new configsForm($this->config);

        if ($request->getMethod() == sfWebRequest::POST) {
            $this->form->bind($request->getPostParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $config = $this->form->save();
                $config->setContent($request->getPostParameter('config'));
                $config->save();
                $this->getUser()->setFlash("notification_ok", $this->__("Config edited successfully."));
                $this->redirect("configs/index");
            }
        }
    }

    public function executeDelete($request) {
        $config = $this->getRoute()->getObject();
		$this->forward404Unless($config);
        $config->delete();
        $this->getUser()->setFlash("notification_ok", $this->__("Config deleted successfully."));
		$this->redirect("configs/index");
    }

}
