<h3><?php echo __("Listes des utilisateurs"); ?></h3>
<hr/>
<table class="table table-striped">
    <thead>
        <tr>
            <th><?php echo __("Nom d'utilisateur"); ?></th>
            <th><?php echo __("Date de création"); ?></th>
            <th><?php echo __("Date de mise à jours"); ?></th>
            <th><?php echo __("Dernier login"); ?></th>
            <th><?php echo __("Action"); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user->getUsername(); ?></td>
                <td><?php echo $user->getCreatedAt(); ?></td>
                <td><?php echo $user->getUpdatedAt(); ?></td>
                <td><?php echo $user->getLastLogin(); ?></td>
                <td>
                    <a href="<?php echo url_for("users_edit", $user); ?>" class="btn btn-primary"><?php echo __("Editer"); ?></a>
                    <a href="<?php echo url_for("users_delete", $user); ?>"><button class="btn btn-danger"><?php echo __("Supprimer"); ?></button></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>

</table>