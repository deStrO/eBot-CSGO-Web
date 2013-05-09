<h3><?php echo __("User Management"); ?></h3>
<hr/>
<table class="table table-striped">
    <thead>
        <tr>
            <th><?php echo __("Username"); ?></th>
            <th><?php echo __("Created"); ?></th>
            <th><?php echo __("Updated"); ?></th>
            <th><?php echo __("Last Loging"); ?></th>
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
                    <a href="<?php echo url_for("users_edit", $user); ?>" class="btn btn-primary"><?php echo __("Edit"); ?></a>
                    <a href="<?php echo url_for("users_delete", $user); ?>"><button class="btn btn-danger"><?php echo __("Delete"); ?></button></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>

</table>