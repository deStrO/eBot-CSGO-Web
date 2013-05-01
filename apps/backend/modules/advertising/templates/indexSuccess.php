<h3><?php echo __("Advertising Management"); ?></h3>
<hr/>
<table class="table table-striped">
    <thead>
        <tr>
            <th><?php echo __("#ID"); ?></th>
            <th><?php echo __("Message"); ?></th>
            <th><?php echo __("Season Name"); ?></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($advertising as $message): ?>
            <tr>
                <td width="30"><?php echo $message->getId(); ?></td>
                <td><?php echo $message->getMessage(); ?></td>
                <td width="250"><?php echo $message->getSeason()->getName(); ?></td>
                <td><?php echo image_tag("/images/icons/flag_" . ($message->getActive() ? "green" : "red") . ".png"); ?></td>
                <td width="230">
                    <a href="<?php echo url_for("advertising_deactivate", $message); ?>"><button class="btn"><?php echo __("De/Activate"); ?></button></a>
                    <a href="<?php echo url_for("advertising_edit", $message); ?>"><button class="btn btn-inverse"><?php echo __("Edit"); ?></button></a>
                    <a href="<?php echo url_for("advertising_delete", $message); ?>"><button class="btn btn-danger"><?php echo __("Delete"); ?></button></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div class="modal-footer" style="text-align:left;">
    <a href="<?php echo url_for("advertising_create"); ?>"><button class="btn btn-primary" style="text-align:left;"><?php echo __("Add new Advert"); ?></button></a>
</div>