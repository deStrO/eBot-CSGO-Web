<?php

require_once sfConfig::get('sf_root_dir').'/vendor/koraktor/steam-condenser/lib/steam-condenser.php';

/**
 * Server manager.
 *
 * @author Cédric Dugat <cedric@dugat.me>
 */
class ServerManager
{
    /**
     * @var Servers
     */
    private $server;

    /**
     * @var SourceServer
     */
    private $serverConnection;

    /**
     * Constructor.
     * 
     * @param Servers $server Server
     */
    public function __construct(Servers $server)
    {
        try {
            /**
             * Ignore SteamCondenser abusive notices.
             * Useful from dev environment.
             */
            error_reporting(0);

            $this->serverConnection = new SourceServer($server->getIpOnly(), $server->getPort());
            $this->serverConnection->rconAuth($server->getRcon());
        } catch (RCONNoAuthException $e) {
            $this->serverConnection = false;
        }
    }

    /**
     * Get server.
     *
     * @return Servers
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * Get server connection.
     *
     * @return SourceServer|false
     */
    public function getServerConnection()
    {
        return $this->serverConnection;
    }

    /**
     * Exec.
     * 
     * @param string $command Command
     * 
     * @return string
     */
    public function exec($command)
    {
        if (false === $this->serverConnection)
        {
            return sprintf('Not connected to %s server', (string) $this->server);
        }

        return $this->getServerConnection()->rconExec($command);
    }

    /**
     * Exec from data.
     * 
     * @param sfRequest $request Request
     * @param sfForm    $form    Form
     * 
     * @return string
     */
    public function execFromData(sfRequest $request, sfForm $form)
    {
        $data = $form->getValues();

        if (false === $request->hasParameter('do'))
        {
            return false;
        }

        switch ($request->getParameter('do'))
        {
            case 'changeMap':
                $maps = sfConfig::get('app_maps');

                $command = sprintf('changelevel %s', $maps[$data['map']]);

                break;

            case 'restart':
                $command = sprintf('mp_restartgame %s', $data['time']);

                break;

            case 'runConfig':
                $command = sprintf('exec %s', $data['config']);

                break;
        }

        return $this->exec($command);
    }

    /**
     * Get status.
     * 
     * @return string
     */
    public function getStatus()
    {
        return $this->exec('status');
    }
}
