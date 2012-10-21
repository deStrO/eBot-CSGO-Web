<h3>Listes des serveurs</h3>
<hr/>
<div class="well well-small">
    Les statistiques sont générées au chargement de la page, d'ou la lenteur probable de celle-ci !
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>#ID</th>
            <th>Adresse IP</th>
            <th>Nombre de matchs</th>
            <th>Nombre de Kill</th>
            <th>Nombre de HeadShot</th>
            <th>Ratio HeadShot</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($servers as $server): ?>
            <?php
            $stats = $server->getStats();
            ?>
        
            <tr>
                <td width="30"><?php echo $server->getId(); ?></td>
                <td width="200"><?php echo $server->getIp(); ?></td>
                <td><?php echo $server->getNbMatchs(); ?></td>
                <td><?php echo $stats["total_kill"]; ?></td>
                <td><?php echo $stats["total_hs"]; ?></td>
                <td><?php echo round($stats["total_hs"]/$stats["total_kill"],4)*100; ?> %</td>
            </tr>
        <?php endforeach; ?>
        <?php if ($servers->count() == 0): ?>
            <tr>
                <td colspan="7">Pas de serveur enregistré</td>
            </tr>
        <?php endif; ?>
    </tbody>

</table>