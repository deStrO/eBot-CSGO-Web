<h3><?php echo __("Statistiques"); ?></h3>
<hr/>
<div class="well well-small">
    <?php echo __("Vous retrouverez ici toutes les statistiques d'utilisation de l'eBot"); ?>
</div>

<h5><?php echo image_tag("/images/icons/flag_green.png"); ?> <?php echo __("Stats joueurs"); ?></h5>

<table class="table">
    <tr>
        <th width="200"><?php echo __("Nombre de kill"); ?></th>
        <td><?php echo $nbKill; ?> kill</td>
    </tr>
    <tr>
        <th width="200"><?php echo __("Nombre de HeadShot"); ?></th>
        <td><?php echo $nbHs; ?> HS</td>
    </tr>
    <tr>
        <th width="200"<?php echo __(">Ratio de HeadShot"); ?></th>
        <td><?php if ($nbKill > 0) echo round($nbHs / $nbKill, 4) * 100; else echo "NaN"; ?>%</td>
    </tr>
</table>

<h5><?php echo image_tag("/images/icons/flag_green.png"); ?> <?php echo __("Stats matchs"); ?></h5>

<table class="table">
    <tr>
        <th width="200"><?php echo __("Matchs en cours"); ?></th>
        <td><?php echo $nbInProgress; ?></td>
    </tr>
    <tr>
        <th width="200"><?php echo __("Matchs en attente"); ?></th>
        <td><?php echo $nbNotStarted; ?></td>
    </tr>
    <tr>
        <th width="200"><?php echo __("Matchs terminé/archivé"); ?></th>
        <td><?php echo $nbClosed; ?></td>
    </tr>
    <tr>
        <th width="200">T<?php echo __("otal de match"); ?></th>
        <td><?php echo $nbInProgress + $nbNotStarted + $nbClosed; ?></td>
    </tr>
</table>

<h5><?php echo image_tag("/images/icons/flag_green.png"); ?> <?php echo __("Stats serveurs"); ?></h5>

<table class="table">
    <tr>
        <th width="200"><?php echo __("Nombre de serveur"); ?></th>
        <td><?php echo $nbServers; ?></td>
    </tr>
</table>
