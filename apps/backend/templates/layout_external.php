<html>
    <head>
        <?php include_stylesheets() ?>
        <?php include_javascripts() ?>
        <script>
            var socketIoAddress = "<?php echo sfConfig::get("app_ebot_ip"); ?>:<?php echo sfConfig::get("app_ebot_port"); ?>";
            var socket = null;
            var socketIoLoaded = false;
            var loadingSocketIo = false;
            var callbacks = new Array();
            function initSocketIo(callback) {
                callbacks.push(callback);
                if (loadingSocketIo) {
                    return;
                }
                
                if (socketIoLoaded) {
                    if (typeof callback == "function") {
                        callback(socket);
                    }
                    return;
                }
                
                loadingSocketIo = true;
                $.getScript("http://"+socketIoAddress+"/socket.io/socket.io.js", function(){
                    socket = io.connect("http://"+socketIoAddress);
                    socket.on('connect', function(){ 
                        socketIoLoaded = true;
                        loadingSocketIo = false;
                        if (typeof callback == "function") {
                            callback(socket);
                        }
                        for (var c in callbacks) {
                            callbacks[c](socket);
                        }
                        //callbacks = new Array();
                    });
                });
            }
        </script>
    </head>
    <body style="background-color:transparent;">
        <?php echo $sf_content ?>
    </body>
</html>
