<h3><?php echo __("Server Management"); ?></h3>
<hr/>
<table class="table table-striped">
    <thead>
        <tr>
            <th><?php echo __("#ID"); ?></th>
            <th><?php echo __("IP Adress"); ?></th>
            <th><?php echo __("Name"); ?></th>
            <th><?php echo __("used?"); ?></th>
            <th><?php echo __("Action"); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($servers as $server): ?>
            <?php $used = $server->used(); ?>
            <tr>
                <td width="30"><?php echo $server->getId(); ?></td>
                <td width="200"><?php echo $server->getIp(); ?></td>
                <td><?php echo $server->getHostname(); ?></td>
                <td width="100">
                    <?php if ($used): ?>
                        <?php echo image_tag("/images/icons/flag_green.png"); ?> <?php echo __("in use"); ?>
                    <?php else: ?>
                        <?php echo image_tag("/images/icons/flag_red.png"); ?> <?php echo __("not used"); ?>
                    <?php endif; ?>
                </td>
                <td width="100">
                    <?php if (!$used): ?>
                        <a href="<?php echo url_for("server_delete", $server); ?>"><button class="btn btn-danger"><?php echo __("Delete"); ?></button></a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if ($servers->count() == 0): ?>
            <tr>
                <td colspan="5"><?php echo __("No Servers registered."); ?></td>
            </tr>
        <?php endif; ?>
    </tbody>

</table>