<h3><?php echo __("Config Management"); ?></h3>
<hr/>
<table class="table table-striped">
    <thead>
        <tr>
            <th><?php echo __("ID#"); ?></th>
            <th><?php echo __("Name"); ?></th>
            <th><?php echo __("Description"); ?></th>
            <th><?php echo __("Created"); ?></th>
            <th><?php echo __("Last Edited"); ?></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($configs as $config): ?>
            <tr>
                <td><?php echo $config->getId(); ?></td>
                <td><?php echo $config->getName(); ?></td>
                <td><?php echo $config->getDescription(); ?></td>
                <td><?php echo $config->getCreatedAt(); ?></td>
                <td><?php echo $config->getUpdatedAt(); ?></td>
                <td>
                    <a href="<?php echo url_for("config_edit", $config); ?>" class="btn btn-primary"><?php echo __("Edit"); ?></a>
                    <a href="<?php echo url_for("config_delete", $config); ?>"><button class="btn btn-danger"><?php echo __("Delete"); ?></button></a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!count($configs)): ?>
            <tr><td colspan="6"><?php echo __("There are no Configfiles available. Create a new one!"); ?></td></tr>
        <?php endif; ?>
    </tbody>

</table>