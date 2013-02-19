<h3><?php echo __("Gestion des équipes"); ?></h3>
<hr/>
<table class="table table-striped">
    <thead>
        <tr>
            <th><?php echo __("#ID"); ?></th>
            <th><?php echo __("Nom de l'équipe"); ?></th>
            <th><?php echo __("Tag"); ?></th>
            <th><?php echo __("Lien de l'équipe"); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($teams as $team): ?>
            <tr>
                <td width="30"><?php echo $team->getId(); ?></td>
                <td width="250"><?php echo $team->getName(); ?></td>
                <td width="200"><?php echo $team->getShorthandle(); ?></td>
                <?php if ($team->getLink() != ""): ?>
                    <td width="200"><a href="<?php echo $team->getLink(); ?>" target="_blank">Team-Link</a></td>
                <?php else: ?>
                    <td width="200"></td>
                <?php endif; ?>
                <td style="text-align:right;">
                    <a href="<?php echo url_for("teams_edit", $team); ?>"><button class="btn btn-inverse"><?php echo __("Editer"); ?></button></a>
                    <a href="<?php echo url_for("teams_delete", $team); ?>"><button class="btn btn-danger"><?php echo __("Supprimer"); ?></button></a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div class="modal-footer" style="text-align:left;">
    <a href="<?php echo url_for("teams_create"); ?>"><button class="btn btn-primary" style="text-align:left;"><?php echo __("Créer une équipe"); ?></button></a>
</div>