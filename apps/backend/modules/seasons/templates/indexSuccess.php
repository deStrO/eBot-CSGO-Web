<h3><?php echo __("Seasons Management"); ?></h3>
<hr/>
<table class="table table-striped">
    <thead>
        <tr>
            <th><?php echo __("#ID"); ?></th>
            <th><?php echo __("Name"); ?></th>
            <th><?php echo __("Start"); ?></th>
            <th><?php echo __("End"); ?></th>
            <th><?php echo __("Link"); ?></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($seasons as $season): ?>
            <tr>
                <td width="30"><?php echo $season->getId(); ?></td>
                <td width="250"><?php echo $season->getName(); ?></td>
                <td width="50"><?php echo $season->getDateTimeObject('start')->format('d.m.Y'); ?></td>
                <td width="50"><?php echo $season->getDateTimeObject('end')->format('d.m.Y'); ?></td>
                <?php if ($season->getLink() != ""): ?>
                    <td><a href="<?php echo $season->getLink(); ?>" target="_blank"><?php echo $season->getLink(); ?></a></td>
                <?php else: ?>
                    <td></td>
                <?php endif; ?>
                <td><?php echo image_tag("/images/icons/flag_" . ($season->getActive() ? "green" : "red") . ".png"); ?></td>
                <td width="230">
                    <a href="<?php echo url_for("season_deactivate", $season); ?>"><button class="btn"><?php echo __("De/Activate"); ?></button></a>
                    <a href="<?php echo url_for("seasons_edit", $season); ?>"><button class="btn btn-inverse"><?php echo __("Edit"); ?></button></a>
                    <a href="<?php echo url_for("seasons_delete", $season); ?>"><button class="btn btn-danger"><?php echo __("Delete"); ?></button></a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div class="modal-footer" style="text-align:left;">
    <a href="<?php echo url_for("seasons_create"); ?>"><button class="btn btn-primary" style="text-align:left;"><?php echo __("Create Season"); ?></button></a>
</div>