<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
		<?php include_http_metas() ?>
		<?php include_metas() ?>
		<?php include_title() ?>
        <link rel="shortcut icon" type="image/png" href="/favicon.png" />
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'/>

		<?php include_stylesheets() ?>
		<?php include_javascripts() ?>
        
        <style type="text/css">
            body {
                background: none transparent;
                background-color: transparent;
                font-family: 'Open Sans', sans-serif;
                font-size: 12px;
            }
            
            .table th, .table td {
                line-height: 12px;
            }
            
        </style>


        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span12">
					<?php echo $sf_content ?>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span12 text-right">
                    Powered by <a href="http://www.esport-tools.net/ebot" target="_blank"><img src="http://www.esport-tools.net/images/ebot.png" style="height: 30px; vertical-align: middle;"/></a>
                </div>
            </div>
        </div>
    </body>
</html>
