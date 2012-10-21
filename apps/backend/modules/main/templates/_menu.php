<div class="span2">
    <div class="well sidebar-nav">
        <script>
            function goToMatch() {
                var id = $("#match_id_go").val();
                if (id > 0) 
                    document.location.href = "<?php echo url_for("@matchs_view?id="); ?>"+id;
            };
        </script>
        <ul class="nav nav-list">
            <li class="nav-header">Menu principal</li>
            <li><a href="<?php echo url_for("homepage"); ?>">Accueil</a></li>
            <li><a href="<?php echo url_for("stats/index"); ?>">Statistiques</a></li>
            <?php /*<li><a href="<?php echo url_for("ebot/index"); ?>">Gestion du bot</a></li>
            <li><a href="<?php echo url_for("ebot/information"); ?>">Information du bot</a></li> */ ?>
            <li class="nav-header">Menu matchs</li>
            <li><a href="<?php echo url_for("matchs_create"); ?>">Création de match</a></li>
            <li><a href="<?php echo url_for("matchs_current"); ?>">Matchs en cours <span class="badge badge-info"><?php echo $nbMatchs; ?></span></a></li>
            <li><a href="<?php echo url_for("matchs_archived"); ?>">Matchs archivés</a></li>
            <li>
                <div class="input-append">
                    <input class="span2" id="match_id_go" size="16" type="text">
                    <button class="btn" type="button" onclick="goToMatch();">Go!</button>
                </div>
            </li>
            <li class="nav-header">Menu serveurs</li>
            <li><a href="<?php echo url_for("servers_create"); ?>">Création d'un serveur</a></li>
            <li><a href="<?php echo url_for("servers/index"); ?>">Gestion des serveurs</a></li>
            <li><a href="<?php echo url_for("servers/stats"); ?>">Statistiques utilisations</a></li>
            <li class="nav-header">Gestion des utilisateurs</li>
            <li><a href="<?php echo url_for("users/create"); ?>">Créer un utilisateur</a></li>
            <li><a href="<?php echo url_for("sfGuardUser/index"); ?>">Gestion des comptes</a></li>
        </ul>
    </div>
</div>