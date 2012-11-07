<?php

/**
 * servers actions.
 *
 * @package    PhpProject1
 * @subpackage servers
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class serversActions extends sfActions {

	private function __($text, $args = array()) {
		return $this->getContext()->getI18N()->__($text, $args, 'messages');
	}

	/**
	 * Executes index action
	 *
	 * @param sfRequest $request A request object
	 */
	public function executeIndex(sfWebRequest $request) {
		$this->servers = ServersTable::getInstance()->findAll();
	}

	public function executeStats(sfWebRequest $request) {
		$this->servers = ServersTable::getInstance()->findAll();
	}

	public function executeDelete(sfWebRequest $request) {
		$server = $this->getRoute()->getObject();
		$this->forward404Unless($server);

		if ($server->used()) {
			$this->getUser()->setFlash("notification_error", $this->__("Le serveur est en cours d'utilisation !"));
		} else {
			$server->delete();
			$this->getUser()->setFlash("notification_ok", $this->__("Le serveur a été supprimé"));
		}
		$this->redirect("servers/index");
	}

	public function executeCreate(sfWebRequest $request) {
		$this->form = new ServersForm();

		if ($request->getMethod() == sfWebRequest::POST) {
			$this->form->bind($request->getPostParameter($this->form->getName()));
			if ($this->form->isValid()) {
				if (!preg_match("!^\d+.\d+.\d+.\d+:\d+$!", $this->form->getValue("ip"))) {
					$datas = $request->getPostParameter($this->form->getName());
					$added = array();
					if (preg_match("!^(\d+).(\d+).(\d+).(?<start>\d+)\-(?<end>\d+):(\d+)$!", $this->form->getValue("ip"), $match)) {
						for ($i = $match["start"]; $i <= $match["end"]; $i++) {
							$ip = $match[1] . "." . $match[2] . "." . $match[3] . "." . $i . ":" . $match[6];
							$datas["ip"] = $ip;
							$this->form = new ServersForm();
							$this->form->bind($datas);
							$this->form->save();
							$added[] = $ip;
						}
					} elseif (preg_match("!^(\d+).(\d+).(\d+).(?<start>\d+)\-(?<end>\d+):(?<port>[0-9\-]+)$!", $this->form->getValue("ip"), $match)) {
						$ports = explode("-", $match["port"]);
						for ($i = $match["start"]; $i <= $match["end"]; $i++) {
							foreach ($ports as $port) {
								$ip = $match[1] . "." . $match[2] . "." . $match[3] . "." . $i . ":" . $port;
								$datas["ip"] = $ip;
								$this->form = new ServersForm();
								$this->form->bind($datas);
								$this->form->save();
								$added[] = $ip;
							}
						}
					} elseif (preg_match("!^(\d+).(\d+).(\d+).(\d+):(?<port>[0-9\-]+)$!", $this->form->getValue("ip"), $match)) {
						$ports = explode("-", $match["port"]);
						foreach ($ports as $port) {
							$ip = $match[1] . "." . $match[2] . "." . $match[3] . "." . $match[4] . ":" . $port;
							$datas["ip"] = $ip;
							$this->form = new ServersForm();
							$this->form->bind($datas);
							$this->form->save();
							$added[] = $ip;
						}
					} else {
						$this->getUser()->setFlash("notification_error", $this->__("Format non reconnu"));
					}

					if (count($added) > 0) {
						$this->getUser()->setFlash("notification_ok", count($added) . $this->__(" serveurs ont été ajoutés (") . implode(", ", $added) . ")");
						$this->redirect("servers_create");
					}
				} else {
					$server = $this->form->save();

					$this->getUser()->setFlash("notification_ok", $this->__("1 serveur a été ajouté (") . $server->getIp() . ")");
					$this->redirect("servers_create");
				}
			}
		}
	}

}
