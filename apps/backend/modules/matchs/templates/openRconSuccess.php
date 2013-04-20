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

    $(document).ready(function() {
        $('form').submit(function(event) {
            var message = "<?php echo $match->getId(); ?> executeCommand <?php echo $match->getIp(); ?> " + $('#data').val();
            var data = Aes.Ctr.encrypt(message, "<?php echo $crypt_key; ?>", 256);
            send = JSON.stringify([data, "<?php echo $match->getIp(); ?>"]);
            $("#rcon").append("<b>Send:</b> " + $('#data').val() + "<br>");
            rcon.send(send);
            return false;
        });

        if ("WebSocket" in window) {
            rcon = new WebSocket("ws://<?php echo $ebot_ip . ':' . ($ebot_port); ?>/rcon");
            rcon.onopen = function() {
                rcon.send('registerMatch_<?php echo $match->getId(); ?>');
            };
            rcon.onmessage = function(msg) {
                console.log(msg.data);
                var message = jQuery.parseJSON(msg.data);
                $("#rcon").append(nl2br("<b>Answer</b>: " + message['content']) + "<hr style='margin: 5px 0px;'>")
                var height = $('#rcon')[0].scrollHeight;
                $('#rcon').scrollTop(height);
            };
            rcon.onclose = function(err) {
                $("#rcon").append('<b><font color="red">Server not available!</font></b> <a href="" onlick="location.reload();"><img src="/images/reload.png"></a>');
                $('#data_form input').attr('readonly', 'readonly');
            };

            logger = new WebSocket("ws://<?php echo $ebot_ip . ':' . ($ebot_port); ?>/logger");
            logger.onopen = function() {
                logger.send('registerMatch_<?php echo $match->getId(); ?>');
            }
            logger.onmessage = function(msg) {
                var message = jQuery.parseJSON(msg.data);
                $("#logger").append($("<span/>").text(message['content']));
                var height = $('#logger')[0].scrollHeight;
                $('#logger').scrollTop(height);
                var regex = /.+"(.+)<\d+><.+><(\w+)>"\ssay\s"(.+)"/;
                if (message['content'].match(regex)) {
                    var chatlog = message['content'].match(regex);
                    if (chatlog[2] == "CT")
                        var teamcolor = "blue";
                    else if (chatlog[2] == "TERRORIST")
                        var teamcolor = "red";
                    else
                        var teamcolor = "black";
                    $("#chatlog").append($("<span/>").html("<font color='"+teamcolor+"'>"+chatlog[1]+"</font>: "+chatlog[3]+"<br />"));
                    var height = $('#chatlog')[0].scrollHeight;
                    $('#chatlog').scrollTop(height);
                }
            };
            logger.onclose = function(err) {
                $("#logger").append('<b><font color="red">Server not available!</font></b> <a href="" onlick="location.reload();"><img src="/images/reload.png"></a>');
            };
        }
    });

    function doRequest(event, ip, id, authkey, added) {
        var data = id + " " + event + " " + ip + added;
        data = Aes.Ctr.encrypt(data, authkey, 256);
        send = JSON.stringify([data, ip]);
        ws.send(send);
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
                        <input type="text" name="data" id="data" style="width: 500px;"><br><input type="submit" class="btn btn-primary" name="Send" value="<?php echo __("Send"); ?>">
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