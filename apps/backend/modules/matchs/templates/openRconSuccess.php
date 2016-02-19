<script>
    function nl2br(str, is_xhtml) {
        var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br ' + '/>' : '<br>';
        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
    }
    $(function() {
        $('#myTab a').click(function(e) {
            e.preventDefault();
            $(this).tab('show');
        })
    });

    function toggleScroll(type) {
        if (autoscroll[type]) {
            autoscroll[type] = false;
            $('#' + type + '_scroll').html('<i class="icon-play"></i><a href="#" onclick="toggleScroll(\'logger\'); return false;"> resume auto-scrolling</a>');
        } else {
            autoscroll[type] = true;
            $('#' + type + '_scroll').html('<i class="icon-pause"></i><a href="#" onclick="toggleScroll(\'logger\'); return false;"> pause auto-scrolling</a>');
        }
    }

    var autoscroll = new Array();
    autoscroll['logger'] = true;
    autoscroll['rcon'] = true;
    autoscroll['chat'] = true;

    $(document).ready(function() {
        $('form').submit(function(event) {
			var command = $(this).find("input[name=data]:first").val();
			if ($(this).find("input[name=mode]").length) {
				command = $(this).find("input[name=mode]").val()+ ' "'+command+'"';
			}
		
            var message = "<?php echo $match->getId(); ?> executeCommand <?php echo $match->getIp(); ?> " + command;
            var data = Aes.Ctr.encrypt(message, "<?php echo $crypt_key; ?>", 256);
            send = JSON.stringify([data, "<?php echo $match->getIp(); ?>"]);
            $("#rcon").append("<b>Send:</b> " + $('#data').val() + "<br>");
            socket.emit("rconSend", send);
            return false;
        });

        initSocketIo(function(socket) {
            var loggerFirstMessage = true;

            socket.emit("identify", {type: "rcon", match_id: <?php echo $match->getId(); ?>});
            socket.emit("identify", {type: "logger", match_id: <?php echo $match->getId(); ?>});

            socket.on("rconHandler", function(data) {
                var message = jQuery.parseJSON(data);
                $("#rcon").append(nl2br("<b>Answer</b>: " + message['content']) + "<hr style='margin: 5px 0px;'>")
                if (autoscroll['rcon']) {
                    var height = $('#rcon')[0].scrollHeight;
                    $('#rcon').scrollTop(height);
                }
            });

            socket.on("disconnect", function() {
                $("#rcon").append('<b><font color="red">Server not available!</font></b> <a href="" onlick="location.reload();"><img src="/images/reload.png"></a>');
                $('#data_form input').attr('readonly', 'readonly');
                $("#logger").append('<b><font color="red">Server not available!</font></b> <a href="" onlick="location.reload();"><img src="/images/reload.png"></a>');
            });

            socket.on("loggerHandler", function(data) {
                if (loggerFirstMessage) {
                    loggerFirstMessage = false;
                    var oldlog = jQuery.parseJSON(data);
                    for (var i = 0; i < oldlog.length; i++) {
                        var message = jQuery.parseJSON(oldlog[i]);
                        $("#logger").append($("<span/>").text(message['content']));
                        if (autoscroll['logger']) {
                            var height = $('#logger')[0].scrollHeight;
                            $('#logger').scrollTop(height);
                        }
                        var regex = /.+"(.+)<\d+><.+><(\w+)>"\ssay\s"(.+)"/;
                        if (message['content'].match(regex)) {
                            var chatlog = message['content'].match(regex);
                            if (chatlog[2] == "CT")
                                var teamcolor = "blue";
                            else if (chatlog[2] == "TERRORIST")
                                var teamcolor = "red";
                            else
                                var teamcolor = "black";
                            $("#chatlog").append($("<span/>").html("<font color='" + teamcolor + "'>" + chatlog[1] + "</font>: " + chatlog[3] + "<br />"));
                            if (autoscroll['chat']) {
                                var height = $('#chatlog')[0].scrollHeight;
                                $('#chatlog').scrollTop(height);
                            }
                        }
                    }
                } else {
                    var message = jQuery.parseJSON(data);
                    $("#logger").append($("<span/>").text(message['content']));
                    if (autoscroll['logger']) {
                        var height = $('#logger')[0].scrollHeight;
                        $('#logger').scrollTop(height);
                    }
                    var regex = /.+"(.+)<\d+><.+><(\w+)>"\ssay\s"(.+)"/;
                    if (message['content'].match(regex)) {
                        var chatlog = message['content'].match(regex);
                        if (chatlog[2] == "CT")
                            var teamcolor = "blue";
                        else if (chatlog[2] == "TERRORIST")
                            var teamcolor = "red";
                        else
                            var teamcolor = "black";
                        $("#chatlog").append($("<span/>").html("<font color='" + teamcolor + "'>" + chatlog[1] + "</font>: " + chatlog[3] + "<br />"));
                        if (autoscroll['chat']) {
                            var height = $('#chatlog')[0].scrollHeight;
                            $('#chatlog').scrollTop(height);
                        }
                    }
                }
            });
        });
    });

    function doRequest(event, ip, id, authkey, added) {
        var data = id + " " + event + " " + ip + added;
        data = Aes.Ctr.encrypt(data, authkey, 256);
        send = JSON.stringify([data, ip]);
        socket.emit("rconSend", send);
        return false;
    }
</script>

<div class="layer" style="display:inline;">
    <div class="modal" style="position:relative; top:auto; left:auto; margin:0 auto 20px; z-index:1; width: auto; max-width:100%;">
        <div class="modal-header">
            <h4><?php echo __("Match"); ?> #<?php echo $match->getId(); ?> - <?php echo $match->getTeamA()->exists() ? $match->getTeamA() : $match->getTeamAName(); ?> vs <?php echo $match->getTeamB()->exists() ? $match->getTeamB() : $match->getTeamBName(); ?></h4>
        </div>
        <ul class="nav nav-tabs" id="myTab">
            <li class="active"><a href="#home"><?php echo __("RCON"); ?></a></li>
            <li><a href="#server-log"><?php echo __("Server-LOG"); ?></a></li>
            <li><a href="#chat-log"><?php echo __("Chat-LOG"); ?></a></li>
            <li><a href="#backup"><?php echo __("Backup System"); ?></a></li>
        </ul>
        <div class="tab-content" style="padding-bottom: 10px; margin-bottom: 20px;">
            <div class="tab-pane active" id="home">

                <div class="modal-body" style="max-height: 0%;"><h4><?php echo __("RCON-Log"); ?>:</h4>
                    <div class="modal" style="position:relative; top:auto; left:auto; margin:0 auto 20px; z-index:1; width: auto; max-width:100%;">
                        <div class="modal-body" style="max-height: 0%;">
                            <div id="rcon" style="overflow:auto; padding:5px; height:440px; min-height:400px; max-height:440px"></div>
                        </div>
                    </div>
                    <h4><?php echo __("Send"); ?>:</h4>
                    <form method="POST" id="data_form" name="data_form">
                        <input type="text" name="data" id="data" style="width: 500px;">
						<input type="submit" class="btn btn-primary" name="Send" value="<?php echo __("Send"); ?>">
                    </form>
					<h4><?php echo __("Match Brand (empty will clear it)"); ?>:</h4>
					<form method="POST" id="data_form" name="data_form">
						<input type="hidden" name="mode" value="mp_teammatchstat_txt"/>
                        <input type="text" name="data" id="data" style="width: 500px;">
						<input type="submit" class="btn btn-primary" name="Send" value="<?php echo __("Send"); ?>">
                    </form>
					<h4><?php echo __("Match Stats Team 1 (empty will clear it)"); ?>:</h4>
					<form method="POST" id="data_form" name="data_form">
						<input type="hidden" name="mode" value="mp_teammatchstat_1"/>
                        <input type="text" name="data" id="data" style="width: 500px;">
						<input type="submit" class="btn btn-primary" name="Send" value="<?php echo __("Send"); ?>">
                    </form>
					<h4><?php echo __("Match Stats Team 2 (empty will clear it)"); ?>:</h4>
					<form method="POST" id="data_form" name="data_form">
						<input type="hidden" name="mode" value="mp_teammatchstat_2"/>
                        <input type="text" name="data" id="data" style="width: 500px;">
						<input type="submit" class="btn btn-primary" name="Send" value="<?php echo __("Send"); ?>">
                    </form>
                </div>
            </div>
            <div class="tab-pane" id="server-log">
                <?php include_partial("matchs/server_log", array("match" => $match)); ?>
            </div>
            <div class="tab-pane" id="chat-log">
                <?php include_partial("matchs/chat_log", array("match" => $match)); ?>
            </div>
            <div class="tab-pane" id="backup">
                <?php include_partial("matchs/backup", array("match" => $match)); ?>
            </div>
        </div>
    </div>
</div>