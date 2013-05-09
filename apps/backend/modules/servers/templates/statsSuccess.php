<h3><?php echo __("Server list"); ?></h3>
<hr/>
<div class="well well-small">
    <?php echo __("Statistics are generated on page loading, which can explain why it loads slowly!"); ?>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th><?php echo __("#ID"); ?></th>
            <th><?php echo __("IP Adress"); ?></th>
            <th><?php echo __("Matches"); ?></th>
            <th><?php echo __("Kills"); ?></th>
            <th><?php echo __("Headshots"); ?></th>
            <th><?php echo __("Headshot Rate"); ?></th>
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
                <td colspan="7"><?php echo __("No Servers registered."); ?></td>
            </tr>
        <?php endif; ?>
    </tbody>

</table>