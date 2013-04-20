<div class="span2">
    <div class="well sidebar-nav">
        <script>
            function goToMatch() {
                var id = $("#match_id_go").val();
                if (id > 0)
                    document.location.href = "<?php echo url_for("@matchs_view?id="); ?>"+id;
            };
            $(document).ready(function() {
                if ("WebSocket" in window) {
                    var alive = new WebSocket("ws://<?php echo $ebot_ip . ':' . $ebot_port; ?>/alive");
                    alive.onopen = function () {
                        $('div#websocketAlive').show();
                        $('div#websocketAlive').html('<font color="green"><b>WebSocket online</b></font>');
                    };
                    alive.onmessage = function (msg) {
                        if (msg.data == "__isAlive__") {
                            $('div#ebotAlive').show();
                            $('div#ebotAlive').html('<font color="green"><b>eBot online</b></font>');
                        }
                    };
                    alive.onclose = function (err) {
                        $('div#websocketAlive').show();
                        $('div#websocketAlive').html('<font color="red">WebSocket offline</font>');
                        $('div#ebotAlive').hide();
                    };
                }
            });
        </script>
        <ul class="nav nav-list">
            <?php if ($sf_user->hasCredential('isAdmin')): ?>
            <li class="nav-header"><?php echo __("Main Menu"); ?></li>
            <li><a href="<?php echo url_for("homepage"); ?>"><?php echo __("Home"); ?></a></li>
            <li><a href="<?php echo url_for("stats/index"); ?>"><?php echo __("Statistics"); ?></a></li>
            <li><div id="websocketAlive" style="display:none;"></div></li>
            <li><div id="ebotAlive" style="display:none;"></div></li>
            <?php endif; ?>
            <?php if ($sf_user->hasCredential('View Matches')): ?>
            <li class="nav-header"><?php echo __("Match Menu"); ?></li>
            <li><a href="<?php echo url_for("matchs_create"); ?>"><?php echo __("Create New Match"); ?></a></li>
            <li><a href="<?php echo url_for("matchs_current"); ?>"><?php echo __("Matches in Progress"); ?> <span class="badge badge-info"><?php echo $nbMatchs; ?></span></a></li>
            <li><a href="<?php echo url_for("matchs_archived"); ?>"><?php echo __("Archived Matches"); ?></a></li>
            <li>
                <div class="input-append">
                    <input class="span2" id="match_id_go" size="16" type="text">
                    <button class="btn" type="button" onclick="goToMatch();"><?php echo __("Go"); ?></button>
                </div>
            </li>
            <?php endif; ?>
            <?php if ($sf_user->hasCredential('View Gameservers')): ?>
            <li class="nav-header"><?php echo __("Server Management"); ?></li>
            <li><a href="<?php echo url_for("servers_create"); ?>"><?php echo __("Add Gameserver"); ?></a></li>
            <li><a href="<?php echo url_for("servers/index"); ?>"><?php echo __("Server Management"); ?></a></li>
            <li><a href="<?php echo url_for("servers/stats"); ?>"><?php echo __("Usage Statistics"); ?></a></li>
            <?php endif; ?>
            <?php if ($sf_user->hasCredential('View Teams')): ?>
            <li class="nav-header"><?php echo __("Team Management"); ?></li>
            <li><a href="<?php echo url_for("teams/create"); ?>"><?php echo __("Create Team"); ?></a></li>
            <li><a href="<?php echo url_for("teams/index"); ?>"><?php echo __("Team Management"); ?></a></li>
            <?php endif; ?>
            <?php if ($sf_user->hasCredential('View Season')): ?>
            <li class="nav-header"><?php echo __("Season Management"); ?></li>
            <li><a href="<?php echo url_for("seasons/create"); ?>"><?php echo __("Create Season"); ?></a></li>
            <li><a href="<?php echo url_for("seasons/index"); ?>"><?php echo __("Seasons Management"); ?></a></li>
            <?php endif; ?>
            <?php if ($sf_user->hasCredential('User Management')): ?>
            <li class="nav-header"><?php echo __("User Management"); ?></li>
            <li><a href="<?php echo url_for("users/create"); ?>"><?php echo __("Add new User"); ?></a></li>
            <li><a href="<?php echo url_for("users/index"); ?>"><?php echo __("User Management"); ?></a></li>
            <?php endif; ?>
        </ul>
    </div>
</div>