<h3>Statistiques</h3>
<hr/>
<div class="well well-small">
    Vous retrouverez ici toutes les statistiques d'utilisation de l'eBot
</div>

<h5><?php echo image_tag("/images/icons/flag_green.png"); ?> Stats joueurs</h5>

<table class="table">
    <tr>
        <th width="200">Nombre de kill</th>
        <td><?php echo $nbKill; ?> kill</td>
    </tr>
    <tr>
        <th width="200">Nombre de HeadShot</th>
        <td><?php echo $nbHs; ?> HS</td>
    </tr>
    <tr>
        <th width="200">Ratio de HeadShot</th>
        <td><?php echo round($nbHs / $nbKill, 4) * 100; ?>%</td>
    </tr>
</table>

<h5><?php echo image_tag("/images/icons/flag_green.png"); ?> Stats matchs</h5>

<table class="table">
    <tr>
        <th width="200">Matchs en cours</th>
        <td><?php echo $nbInProgress; ?></td>
    </tr>
    <tr>
        <th width="200">Matchs en attente</th>
        <td><?php echo $nbNotStarted; ?></td>
    </tr>
    <tr>
        <th width="200">Matchs terminé/archivé</th>
        <td><?php echo $nbClosed; ?></td>
    </tr>
    <tr>
        <th width="200">Total de match</th>
        <td><?php echo $nbInProgress + $nbNotStarted + $nbClosed; ?></td>
    </tr>
</table>

<h5><?php echo image_tag("/images/icons/flag_green.png"); ?> Stats serveurs</h5>

<table class="table">
    <tr>
        <th width="200">Nombre de serveur</th>
        <td><?php echo $nbServers; ?></td>
    </tr>
</table>
