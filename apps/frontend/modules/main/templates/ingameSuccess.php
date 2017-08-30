<h3><?php echo __("InGame Help"); ?></h3>

<div class="well">
    <p>
        <?php echo __("eBot includes a whole series of commands which can be used via the chat, at various moments of the match. With these commands you can ask for your personal stats, the score match, you can restart the match, etc ..."); ?>
    </p>
</div>
<hr/>

<h5><?php echo __("Commands usable at any moment"); ?></h5>

<div class="well">
    <ul class="unstyled">
        <li><b>!help</b> : <?php echo __("displays the available commands"); ?></li>
        <li><b>!stats</b> : <?php echo __("sends the statistics of the match (number of kills, K/D ratio, HD ratio, ...)"); ?></li>
        <li><b>!morestats</b> : <?php echo __("sends the detailed statistics, such as the number of First Kill, 1vX, kill per round, etc ..."); ?></li>
        <li><b>!status</b> : <?php echo __("displays the match status"); ?></li>
        <li><b>!score</b> : <?php echo __("sends the match scores"); ?></li>
    </ul>
</div>

<h5><?php echo __("Commands usable during warmup"); ?></h5>

<div class="well">
    <ul class="unstyled">
        <li><b>!ready</b> : <?php echo __("telling the bot that the team is ready. When the 2 teams are ready, the match is launched"); ?></li>
        <li><b>!notready</b> : <?php echo __("telling the bot that the team is not ready anymore."); ?></li>
    </ul>
</div>

<h5><?php echo __("Commands usable during knife round"); ?></h5>

<div class="well">
    <ul class="unstyled">
        <li><b>!restart</b> : <?php echo __("tells that your team wants the knife round to be restarted. The restart of the knife round is only possible if the other team agrees."); ?></li>
    </ul>
</div>

<h5><?php echo __("Commands usable at the end of the knife round"); ?></h5>

<div class="well">
    <p>
        <?php echo __("At the end of the knife round, the winning team &lt;b&gt;WILL HAVE TO&lt;/b&gt; tell the eBot what they decide to do (stay or switch). The eBot will automatically switch the players and reverse the team."); ?>
    </p>
    <ul class="unstyled">
        <li><b>!stay</b> : <?php echo __("no team switch"); ?></li>
        <li><b>!switch</b> : <?php echo __("switches the teams"); ?></li>
    </ul>
</div>


<h5><?php echo __("Commands usable during the match"); ?></h5>

<div class="well">
    <ul class="unstyled">
        <li><b>!stop</b> : <?php echo __("tells the other team that your team wants to stop the match. The match stop is only possible if the other team agrees. The other team will also have to write !stop to stop the match. The match stop makes the match return to the previous state (warmup)."); ?></li>
        <li><b>!continue</b> : <?php echo __("tells the other team your team wants to continue the match (if stopped)"); ?></li>
        <li><b>!pause</b> : <?php echo __("tells the other team you want to pause"); ?></li>
        <li><b>!unpause</b> : <?php echo __("tells the other team you want to unpause"); ?></li>
    </ul>
</div>
