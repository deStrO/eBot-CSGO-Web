<div class="span2">
    <div class="well sidebar-nav">
        <script>
            function goToMatch() {
                var id = $("#match_id_go").val();
                if (id > 0)
                    document.location.href = "<?php echo url_for("@matchs_view?id="); ?>"+id;
            };
/*            $(document).ready(function() {
                if ("WebSocket" in window) {
                    status = new WebSocket("ws://<?php echo $ebot_ip . ':' . $ebot_port; ?>/rcon");
                    status.onopen = function () {
                        $('div#websocket').show();
                        $('div#websocket').html('<font color="green">WebSocket online</font>');
                        status.send("test");
                    };

                    status.onclose = function (err) {
                        $('div#websocket').show();
                        $('div#websocket').html('<font color="red">WebSocket offline</font>');
                    };
                }
            });
*/
        </script>
        <ul class="nav nav-list">
            <li class="nav-header"><?php echo __("Menu principal"); ?></li>
            <li><a href="<?php echo url_for("homepage"); ?>"><?php echo __("Accueil"); ?></a></li>
            <li><a href="<?php echo url_for("stats/index"); ?>"><?php echo __("Statistiques"); ?></a></li>
            <li><div id="websocket" style="display:none;"></div></li>
            <?php /*<li><a href="<?php echo url_for("ebot/index"); ?>">Gestion du bot</a></li>
            <li><a href="<?php echo url_for("ebot/information"); ?>">Information du bot</a></li> */ ?>
            <li class="nav-header"><?php echo __("Menu matchs"); ?></li>
            <li><a href="<?php echo url_for("matchs_create"); ?>"><?php echo __("Création de match"); ?></a></li>
            <li><a href="<?php echo url_for("matchs_current"); ?>"><?php echo __("Matchs en cours"); ?> <span class="badge badge-info"><?php echo $nbMatchs; ?></span></a></li>
            <li><a href="<?php echo url_for("matchs_archived"); ?>"><?php echo __("Matchs archivés"); ?></a></li>
            <li>
                <div class="input-append">
                    <input class="span2" id="match_id_go" size="16" type="text">
                    <button class="btn" type="button" onclick="goToMatch();">Go!</button>
                </div>
            </li>
            <li class="nav-header"><?php echo __("Menu serveurs"); ?></li>
            <li><a href="<?php echo url_for("servers_create"); ?>"><?php echo __("Création d'un serveur"); ?></a></li>
            <li><a href="<?php echo url_for("servers/index"); ?>"><?php echo __("Gestion des serveurs"); ?></a></li>
            <li><a href="<?php echo url_for("servers/stats"); ?>"><?php echo __("Statistiques utilisations"); ?></a></li>
            <li class="nav-header"><?php echo __("Team-Verwaltung"); ?></li>
            <li><a href="<?php echo url_for("teams/create"); ?>"><?php echo __("Team erstellen"); ?></a></li>
            <li><a href="<?php echo url_for("teams/index"); ?>"><?php echo __("Teams verwalten"); ?></a></li>
            <li class="nav-header"><?php echo __("Season-Verwaltung"); ?></li>
            <li><a href="<?php echo url_for("seasons/create"); ?>"><?php echo __("Season erstellen"); ?></a></li>
            <li><a href="<?php echo url_for("seasons/index"); ?>"><?php echo __("Seasons verwalten"); ?></a></li>
            <li class="nav-header"><?php echo __("Gestion des utilisateurs"); ?></li>
            <li><a href="<?php echo url_for("users/create"); ?>"><?php echo __("Créer un utilisateur"); ?></a></li>
            <li><a href="<?php echo url_for("users/index"); ?>"><?php echo __("Gestion des comptes"); ?></a></li>
        </ul>
    </div>
</div>