<h3><?php echo __("Aide InGame"); ?></h3>

<div class="well">
    <p>
        <?php echo __("L'eBot possède tout une série de commande qui peuvent être appelée à différent moment dans le jeu via le tchat. Ces commandes permettent de demande par exemple ses stats, le score du match, démarrer le match, etc ..."); ?>
    </p>
</div>
<hr/>

<h5><?php echo __("Les commandes utilisables à tout moment"); ?></h5>

<div class="well">
    <ul class="unstyled">
        <li><b>!help</b> : <?php echo __("affiche les commandes disponibles"); ?></li>
        <li><b>!stats</b> : <?php echo __("envoie les statistiques du matchs (nombre de kill, ratio K/D, ratio HS, ...)"); ?></li>
        <li><b>!morestats</b> : <?php echo __("envoie les statistiques détaillées, tels que le nombre de First Kill, 1vX, kill par round, etc ..."); ?></li>
        <li><b>!status</b> : <?php echo __("affiche le statut du match"); ?></li>
        <li><b>!score</b> : <?php echo __("envoie les scores du matchs"); ?></li>
    </ul>
</div>

<h5><?php echo __("Les commandes utilisables durant le warmup"); ?></h5>

<div class="well">
    <ul class="unstyled">
        <li><b>!ready</b> : <?php echo __("signaler au bot que l'équipe est ready. Lorsque les 2 équipes sont ready, le match est lancé"); ?></li>
        <li><b>!notready</b> : <?php echo __("signaler au bot que l'équipe n'est plus ready."); ?></li>
    </ul>
</div>

<h5><?php echo __("Les commandes utilisables durant le knife round"); ?></h5>

<div class="well">
    <ul class="unstyled">
        <li><b>!restart</b> : <?php echo __("signal que votre équipe veut un redémarrage du knife round. Le redémarrage du knife round n'est possible que si l'autre équipe est d'accord."); ?></li>
    </ul>
</div>

<h5><?php echo __("Les commandes utilisables à la fin du knife round"); ?></h5>

<div class="well">
    <p>
        <?php echo __("A la fin du knife round, l'équipe gagnante <b>DEVRA</b> signaler à l'eBot ce qu'il décide (stay ou switch). L'eBot switchera automatiquement les joueurs et inversera les équipes après."); ?>
    </p>
    <ul class="unstyled">
        <li><b>!stay</b> : <?php echo __("pas de changement"); ?></li>
        <li><b>!switch</b> : <?php echo __("switch les équipes"); ?></li>
    </ul>
</div>


<h5><?php echo __("Les commandes utilisables durant le match"); ?></h5>

<div class="well">
    <ul class="unstyled">
        <li><b>!stop</b> : <?php echo __("signal que votre équipe veut un arrêt du match. L'arrêt du match n'est possible que si l'autre équipe est d'accord. Elle devra écrire à son tour !stop pour arrêter le round en cours."); ?></li>
        <li><b>!continue</b> : <?php echo __("signal que votre équipe est prête pour reprendre le match (dans le cas d'un stop)"); ?></li>
        <li><b>!pause</b> : <?php echo __("signal que votre équipe veut une pause"); ?></li>
        <li><b>!unpause</b> : <?php echo __("signal que votre équipe veut retirer la pause"); ?></li>
    </ul>
</div>