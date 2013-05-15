<script>

    $(function() {
        $("#tableTeams").dataTable({
            "sDom": "<''<'span6'l><'span6'f>r>t<''<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ <?php echo __('records per page'); ?>"
            },
            "iDisplayLength": 15,
            "aLengthMenu": [[15, 30, 60, -1], [15, 30, 60, "All"]],
            "aaSorting": [[ 0, "asc" ]]
        });

        $.extend( $.fn.dataTableExt.oStdClasses, {
            "sWrapper": "dataTables_wrapper form-inline"
        } );
    });
</script>

<h3><?php echo __("Team Managment"); ?></h3>
<hr/>
<table id="tableTeams" class="table table-striped table-condensed">
    <thead>
        <th><?php echo __("#ID"); ?></th>
        <th></th>
        <th><?php echo __("Name"); ?></th>
        <th><?php echo __("Shorthandle"); ?></th>
        <th><?php echo __("Team Link"); ?></th>
        <th></th>
    </thead>
    <tbody>
        <?php foreach ($teams as $team): ?>
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