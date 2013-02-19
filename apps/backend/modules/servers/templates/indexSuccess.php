<h3><?php echo __("Listes des serveurs"); ?></h3>
<hr/>
<table class="table table-striped">
    <thead>
        <tr>
            <th><?php echo __("#ID"); ?></th>
            <th><?php echo __("Adresse IP"); ?></th>
            <th><?php echo __("Nom"); ?></th>
            <th><?php echo __("Utilisation"); ?></th>
            <th><?php echo __("Action"); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($servers as $server): ?>
            <?php $used = $server->used(); ?>
            <tr>
                <td width="30"><?php echo $server->getId(); ?></td>
                <td width="200"><?php echo $server->getIp(); ?></td>
                <td><?php echo $server->getHostname() ?></td>
                <td width="100">
                    <?php if ($used): ?>
                        <?php echo image_tag("/images/icons/flag_green.png"); ?> <?php echo __("En cours"); ?>
                    <?php else: ?>
                        <?php echo image_tag("/images/icons/flag_red.png"); ?> <?php echo __("Non utilisé"); ?>
                    <?php endif; ?>
                </td>
                <td width="200">
                    <?php if (sfConfig::get('app_servers_management')): ?>
                        <a href="<?php echo url_for('servers_manage', $server) ?>" class="btn">
                            <?php echo __('Administrer') ?>
                        </a>
                    <?php endif ?>

                    <?php if (!$used): ?>
                        <a href="<?php echo url_for("server_delete", $server); ?>"><button class="btn btn-danger"><?php echo __("Supprimer"); ?></button></a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if ($servers->count() == 0): ?>
            <tr>
                <td colspan="5"><?php echo __("Pas de serveur enregistré"); ?></td>
            </tr>
        <?php endif; ?>
    </tbody>

</table>