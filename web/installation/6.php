<?php
    function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir") 
                        rrmdir($dir."/".$object); 
                    else 
                        unlink($dir."/".$object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }
 ?>

<?php if ($_SESSION['mysql_connection']['status'] != 'success' || $_SESSION['createAdmin']['status'] != 'success' || $_SESSION['ebot_web_configuration']['status'] != 'success' || $_SESSION['ebot_server_configuration']['status'] != 'success') : ?>
    <div class="alert alert-error">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <h4>Error!</h4>
        Error missing data. Please restart the installation process.
    </div>
<?php else: ?>
    <?php
        session_destroy();
        rrmdir('../../cache/frontend');
        rrmdir('../../cache/backend');
    ?>
    <h4>Installation finished</h4>
    <p class="text-error"><b>Please delete the whole installation folder at /web/installation before proceeding!</b></p>
    <p>The installation is now finished, your eBot should be ready!<br>
    Just start the eBot Server and goto <a href="<?php echo $_SERVER['PHP_SELF'] ?>/../../admin.php" target="_blank">your eBot Installation</a>, login to your account and create a new Match<br><br>
    If you need additional help, please state your problems at the <a href="http://forum.esport-tools.net/index.php" target="_blank">esport-tools.net forums</a>.</p>
<?php endif; ?>