<?php session_start(); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
       <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="title" content="eBot :: Your ingame bot manager for CS:GO" />
        <title>eBot :: Your ingame bot manager for CS:GO</title>
        <link rel="shortcut icon" type="image/png" href="/favicon.png" />
        <link rel="stylesheet" type="text/css" media="screen" href="/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="/css/bootstrap-responsive.min.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="/css/tipsy.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="/css/datatable.bootstrap.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="/css/flags.css" />
        
        <script type="text/javascript" src="/js/jquery-1.8.2.min.js"></script>
        <script type="text/javascript" src="/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/js/jquery.validate.min.js"></script>
        <script type="text/javascript" src="/js/jquery.tipsy.js"></script>
        <script type="text/javascript" src="/js/jquery.tablesorter.min.js"></script>
        <script type="text/javascript" src="/js/jquery.datatable.min.js"></script>
        <script type="text/javascript" src="/js/datatable.bootstrap.js"></script>
        <script type="text/javascript" src="/js/heatmap.js"></script>
        <script type="text/javascript" src="/js/raphael-min.js"></script>
        <script type="text/javascript" src="/js/jquery.iphone-switch.js"></script>
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
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container-fluid">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="<?php echo $_SERVER['PHP_SELF'] ?>">eBot-CSGO</a>
                    <div class="nav-collapse collapse">
                        <div style="line-height: 35px; float: right;  margin-right: 10px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php $step = (isset($_GET['step']) ? $_GET['step'] : '0'); ?>

        <script>
            $(document).ready(function() {
                $('.btn').click(function(){
                    window.location.href = window.location.pathname + '?step=<?php echo $step+1; ?>';
                })
            })
        </script>
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span2">
                    <div class="well sidebar-nav">
                        <div style="text-align:center;">
                            <img src="/images/ebot.png" style="margin: 25px 15px; width: 200px;">
                        </div>
                        <ul class="nav nav-list">
                            <li <?php if ($step == "0") echo "class='active'"; ?>><a href="<?php echo $_SERVER['PHP_SELF'] ?>">Start</a></li>
                            <li <?php if ($step == "1") echo "class='active'"; ?>><a href="<?php echo $_SERVER['PHP_SELF']; ?>?step=1">Configure Database</a></li>
                            <li <?php if ($step == "2") echo "class='active'"; ?>><a href="<?php echo $_SERVER['PHP_SELF']; ?>?step=2">Create Account</a></li>
                            <li <?php if ($step == "3") echo "class='active'"; ?>><a href="<?php echo $_SERVER['PHP_SELF']; ?>?step=3">Configure eBot</a></li>
                            <li <?php if ($step == "4") echo "class='active'"; ?>><a href="<?php echo $_SERVER['PHP_SELF']; ?>?step=4">Configure eBot-Server</a></li>
                            <li <?php if ($step == "5") echo "class='active'"; ?>><a href="<?php echo $_SERVER['PHP_SELF']; ?>?step=5">Manual Steps</a></li>
                            <li <?php if ($step == "6") echo "class='active'"; ?>><a href="<?php echo $_SERVER['PHP_SELF']; ?>?step=6">Finish</a></li>
                        </ul>
                    </div>
                </div>
                <div class="span10">
                    <div class="well">
                        <h3 style="margin-bottom: 0px">Install and Configure the eBot Webinterface</h3>
                        <hr style="margin-top: 5px;">
                        <?php 
                            $invalide = array('\\','/','/\/',':','.');
                            $step = str_replace($invalide,' ',$step);
                            if(!file_exists($step.".php"))
                                $step = '0';
                            include($step.".php");
                        ?>
                    </div>
                </div>
            </div>

            <!-- Please, don't remove the brand -->
            <footer class="footer">
                <p>&copy; <a target="_blank" href="http://www.esport-tools.net/ebot">eSport-tools</a> 2012-2013 - By deStrO &amp; Basert - Follow <a target="_blank" href="https://twitter.com/deStrO_BE">deStrO</a> &amp; <a target="_blank" href="https://twitter.com/justbasert">Basert</a> on Twitter - Propulsed by <a target="_blank" href="http://twitter.github.com/bootstrap">Bootstrap</a> &amp; <a target="_blank" href="http://www.symfony-project.com">Symfony</a> - Follow eBot on <a target="_blank" href="https://github.com/deStrO/eBot-CSGO">GitHub</a></p>
            </footer>
        </div>
    </body>
</html>
