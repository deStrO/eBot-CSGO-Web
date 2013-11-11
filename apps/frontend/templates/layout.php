<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <?php include_http_metas() ?>
        <?php include_metas() ?>
        <?php include_title() ?>
        <link rel="shortcut icon" type="image/png" href="/favicon.png" />
        <?php include_stylesheets() ?>
        <?php include_javascripts() ?>
        <style type="text/css">
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
            .sidebar-nav {
                padding: 9px 0;
            }
        </style>
        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>
    <body>
        <script>
            function submitForm(id) {
                $("#" + id).submit();
            }

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
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container-fluid">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="<?php echo url_for("homepage"); ?>">eBot-CSGO</a>
                    <div class="nav-collapse collapse">
                        <div style="line-height: 35px; float: right;  margin-right: 10px;">
                            <form style="display:inline; margin-left: 5px; cursor: pointer" action="<?php echo url_for('@switch_lang?langage=fr') ?>" method="POST" id="langFr"><input type="hidden" name="referer" value="<?php echo $sf_request->getPathInfo() ?>" /><?php echo image_tag('/images/fr.png', array("onclick" => "javascript:submitForm('langFr')")); ?></form>
                            <form style="display:inline; margin-left: 5px; cursor: pointer" action="<?php echo url_for('@switch_lang?langage=en') ?>" method="POST" id="langEn"><input type="hidden" name="referer" value="<?php echo $sf_request->getPathInfo() ?>" /><?php echo image_tag('/images/en.png', array("onclick" => "javascript:submitForm('langEn')")); ?></form>
                            <form style="display:inline; margin-left: 5px; cursor: pointer" action="<?php echo url_for('@switch_lang?langage=de') ?>" method="POST" id="langDe"><input type="hidden" name="referer" value="<?php echo $sf_request->getPathInfo() ?>" /><?php echo image_tag('/images/de.png', array("onclick" => "javascript:submitForm('langDe')")); ?></form>
                            <form style="display:inline; margin-left: 5px; cursor: pointer" action="<?php echo url_for('@switch_lang?langage=cn') ?>" method="POST" id="langCn"><input type="hidden" name="referer" value="<?php echo $sf_request->getPathInfo() ?>" /><?php echo image_tag('/images/cn.gif', array("onclick" => "javascript:submitForm('langCn')")); ?></form>
                        </div>
                        <?php if ($sf_user->isAuthenticated()): ?>

                            <p class="navbar-text pull-right">
                                Logged in as <a href="#" class="navbar-link"><?php echo $sf_user->getGuarduser()->getUsername(); ?></a>
                            </p>
                            <ul class="nav">
                                <li class="active"><a href="<?php echo url_for("homepage"); ?>"><?php echo __("Home"); ?></a></li>
                                <li><a href="/admin.php"><?php echo __("Admin"); ?></a></li>
                                <li><a href="http://www.esport-tools.net/ebot"><?php echo __("Help"); ?></a></li>
                                <li><a href="http://www.esport-tools.net/about"><?php echo __("About"); ?></a></li>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row-fluid">
                <?php include_component("main", "menu"); ?>

                <div class="span10">
                    <?php if ($sf_user->hasFlash("notification_error")): ?>
                        <div class="alert alert-error">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <h4><?php echo __("Error"); ?> !</h4>
                            <?php echo $sf_user->getFlash("notification_error"); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($sf_user->hasFlash("notification_ok")): ?>
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <h4><?php echo __("Information"); ?></h4>
                            <?php echo $sf_user->getFlash("notification_ok"); ?>
                        </div>
                    <?php endif; ?>

                    <?php echo $sf_content ?>
                </div>
            </div>

            <!-- Please, don't remove the brand -->
            <footer class="footer">
                <p>&copy; <a target="_blank" href="http://www.esport-tools.net/ebot">eSport-tools</a> 2012-2013 - <?php echo (sfConfig::get("app_version") != "") ? sfConfig::get("app_version") : "3.0 RC6"; ?> - By deStrO &amp; Basert - Follow <a target="_blank" href="https://twitter.com/deStrO_BE">deStrO</a> &amp; <a target="_blank" href="https://twitter.com/justbasert">Basert</a> on Twitter - Propulsed by <a target="_blank" href="http://twitter.github.com/bootstrap">Bootstrap</a> & <a target="_blank" href="http://www.symfony-project.com">Symfony</a> - Follow eBot on <a target="_blank" href="https://github.com/deStrO/eBot-CSGO">GitHub</a></p>
            </footer>
        </div>
    </body>
</html>
