<h3>Listes des serveurs</h3>
<hr/>
<table class="table table-striped">
    <thead>
        <tr>
            <th>#ID</th>
            <th>Adresse IP</th>
            <th>Nom</th>
            <th>Utilisation</th>
            <th>Action</th>
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
                        <?php echo image_tag("/images/icons/flag_green.png"); ?> En cours
                    <?php else: ?>
                        <?php echo image_tag("/images/icons/flag_red.png"); ?> Non utilisé
                    <?php endif; ?>
                </td>
                <td width="100">
                    <?php if (!$used): ?>
                        <a href="<?php echo url_for("server_delete", $server); ?>"><button class="btn btn-danger">Supprimer</button></a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if ($servers->count() == 0): ?>
            <tr>
                <td colspan="5">Pas de serveur enregistré</td>
            </tr>
        <?php endif; ?>
    </tbody>

</table>