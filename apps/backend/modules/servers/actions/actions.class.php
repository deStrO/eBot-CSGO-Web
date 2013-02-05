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
                    $counter = 1;
                    $name = $this->form->getValue("hostname");
					if (preg_match("!^(\d+).(\d+).(\d+).(?<start>\d+)\-(?<end>\d+):(\d+)$!", $this->form->getValue("ip"), $match)) {
						for ($i = $match["start"]; $i <= $match["end"]; $i++) {
							$ip = $match[1] . "." . $match[2] . "." . $match[3] . "." . $i . ":" . $match[6];
							$datas["ip"] = $ip;
                            $datas["hostname"] = $name . " #".$counter++;
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
                                $datas["hostname"] = $name . " #".$counter++;
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
                            $datas["hostname"] = $name . " #".$counter++;
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

	/**
	 * Manage.
	 *
	 * @author Cédric Dugat <cedric@dugat.me>
	 * 
	 * @param sfWebRequest $request Request
	 */
	public function executeManage(sfWebRequest $request)
	{
		$this->server        = $this->getRoute()->getObject();
		$this->serverManager = $this->getServerManager($this->server);
	}

	/**
	 * Server action.
	 *
	 * @author Cédric Dugat <cedric@dugat.me>
	 * 
	 * @param sfWebRequest $request Request
	 */
	public function executeDoAction(sfWebRequest $request)
	{
		$server = $this->getRoute()->getObject();
		$sm     = $this->getServerManager($server);
		$action = $request->getParameter('do');

		$this->forward404Unless(in_array($action, array('changeMap', 'restart', 'runConfig')));

		$form = new ServerManagerActionForm(null, array('action' => $action));

		if ($request->isMethod('POST'))
		{
			$form->bind($request->getParameter($form->getName()));

			if ($form->isValid())
			{
				if ((bool) $sm->execFromData($request, $form))
				{
					$this->getUser()->setFlash('notification_ok', $this->__('Action effectuée.'));
				}
				else
				{
					$this->getUser()->setFlash('notification_error', $this->__('Une erreur est survenue.'));
				}
			}
			else
			{
				$this->getUser()->setFlash('notification_error', $this->__('Tous les informations nécessaires n\'ont pas été fournies.'));
			}
		}
		else
		{
			$this->getUser()->setFlash('notification_error', $this->__('Erreur.'));
		}

		$this->redirect('servers_manage', $server);
	}

	/**
	 * Get server manager.
	 *
	 * @author Cédric Dugat <cedric@dugat.me>
	 * 
	 * @param Servers
	 * 
	 * @return ServerManager
	 */
	protected function getServerManager($server)
	{
		$sm = new ServerManager($server);

		if (false === $sm || false === $sm->getServerConnection())
		{
			$this->getUser()->setFlash('notification_error', $this->__('Impossible de communiquer avec le serveur.'));
		}

		return $sm;
	}
}
