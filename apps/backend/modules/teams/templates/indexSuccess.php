<h3><?php echo __("Team Managment"); ?></h3>
<hr/>
<table class="table table-striped">
    <thead>
        <tr>
            <th><?php echo __("#ID"); ?></th>
            <th></th>
            <th><?php echo __("Name"); ?></th>
            <th><?php echo __("Shorthandle"); ?></th>
            <th><?php echo __("Team Link"); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pager->getResults() as $team): ?>
            <tr>
                <td width="30" style="vertical-align:middle;"><?php echo $team->getId(); ?></td>
                <td width="20" style="vertical-align:middle;"><i class="flag flag-<?php echo strtolower($team->getFlag()); ?>"></i></td>
                <td width="250" style="vertical-align:middle;"><?php echo $team->getName(); ?></td>
                <td width="200" style="vertical-align:middle;"><?php echo $team->getShorthandle(); ?></td>
                <?php if ($team->getLink() != ""): ?>
                    <td width="200" style="vertical-align:middle;"><a href="<?php echo $team->getLink(); ?>" target="_blank"><?php echo __("Link"); ?></a></td>
                <?php else: ?>
                    <td width="200"></td>
                <?php endif; ?>
                <td style="text-align:right;">
                    <a href="<?php echo url_for("teams_edit", $team); ?>"><button class="btn btn-inverse"><?php echo __("Edit"); ?></button></a>
                    <a href="<?php echo url_for("teams_delete", $team); ?>"><button class="btn btn-danger"><?php echo __("Delete"); ?></button></a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div class="modal-footer" style="text-align:left;">
    <a href="<?php echo url_for("teams_create"); ?>"><button class="btn btn-primary" style="text-align:left;"><?php echo __("Create new Team"); ?></button></a>
</div>
<div class="pagination pagination-centered">
    <?php
    use_helper("TablePagination");
    tablePagination($pager, $url);
    ?>
</div>