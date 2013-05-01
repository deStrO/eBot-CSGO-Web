<h3><?php echo __("Statistics"); ?></h3>
<hr/>

<h5><?php echo image_tag("/images/icons/flag_green.png"); ?> <?php echo __("Players Statistics"); ?></h5>

<table class="table">
    <tr>
        <th width="200"><?php echo __("Number of Kills"); ?></th>
        <td><?php echo $nbKill; ?> kill</td>
    </tr>
    <tr>
        <th width="200"><?php echo __("Number of Headshots"); ?></th>
        <td><?php echo $nbHs; ?> HS</td>
    </tr>
    <tr>
        <th width="200"><?php echo __("Headshot Rate"); ?></th>
        <td><?php if ($nbKill > 0) echo round($nbHs / $nbKill, 4) * 100; else echo __("not available"); ?>%</td>
    </tr>
</table>

<h5><?php echo image_tag("/images/icons/flag_green.png"); ?> <?php echo __("Match Statistics"); ?></h5>

<table class="table">
    <tr>
        <th width="200"><?php echo __("Matches in Progress"); ?></th>
        <td><?php echo $nbInProgress; ?></td>
    </tr>
    <tr>
        <th width="200"><?php echo __("Matches not Started"); ?></th>
        <td><?php echo $nbNotStarted; ?></td>
    </tr>
    <tr>
        <th width="200"><?php echo __("Finished/Archived Matches"); ?></th>
        <td><?php echo $nbClosed; ?></td>
    </tr>
    <tr>
        <th width="200"><?php echo __("Matches Count"); ?></th>
        <td><?php echo $nbInProgress + $nbNotStarted + $nbClosed; ?></td>
    </tr>
</table>

<h5><?php echo image_tag("/images/icons/flag_green.png"); ?> <?php echo __("Server Statistics"); ?></h5>

<table class="table">
    <tr>
        <th width="200"><?php echo __("Number of Servers"); ?></th>
        <td><?php echo $nbServers; ?></td>
    </tr>
</table>
