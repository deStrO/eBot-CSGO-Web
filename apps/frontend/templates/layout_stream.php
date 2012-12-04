<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
		<?php include_http_metas() ?>
		<?php include_metas() ?>
		<?php include_title() ?>
        <link rel="shortcut icon" type="image/png" href="/favicon.png" />
		<?php include_stylesheets() ?>
		<?php include_javascripts() ?>

        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>
    <body>
        <script>
            function submitForm (id) {
                $("#"+id).submit();
            }	
        </script>

        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span12">
					<?php echo $sf_content ?>
                </div>
            </div>

			<!-- Please, don't remove the brand -->
			<footer class="footer">
				<p>&copy; <a target="_blank" href="http://www.esport-tools.net/ebot">eSport-tools</a> 2012 - <?php echo (sfConfig::get("app_version") != "") ? sfConfig::get("app_version") : "3.0 RC6"; ?> - By deStrO - Follow me on <a target="_blank" href="https://twitter.com/deStrO_BE">Twitter</a> - Propulsed by <a target="_blank" href="http://twitter.github.com/bootstrap">Bootstrap</a> & <a target="_blank" href="http://www.symfony-project.com">Symfony</a> - Follow me <a target="_blank" href="https://github.com/deStrO/eBot-CSGO">GitHub</a></p>
			</footer>
        </div>
    </body>
</html>
