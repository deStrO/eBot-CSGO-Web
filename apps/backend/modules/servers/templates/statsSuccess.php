<h3><?php echo __("Listes des serveurs"); ?></h3>
<hr/>
<div class="well well-small">
    <?php echo __("Les statistiques sont générées au chargement de la page, d'ou la lenteur probable de celle-ci !"); ?>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th><?php echo __("#ID"); ?></th>
            <th><?php echo __("Adresse IP"); ?></th>
            <th><?php echo __("Nombre de matchs"); ?></th>
            <th><?php echo __("Nombre de Kill"); ?></th>
            <th><?php echo __("Nombre de HeadShot"); ?></th>
            <th><?php echo __("Ratio HeadShot"); ?></th>
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
                <td colspan="7"><?php echo __("Pas de serveur enregistré"); ?></td>
            </tr>
        <?php endif; ?>
    </tbody>

</table>