<?php if ($_SESSION['mysql_connection']['status'] != 'success' || $_SESSION['createAdmin']['status'] != 'success') : ?>
    <div class="alert alert-error">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <h4>Error!</h4>
        Error missing data. Please restart the installation process.
    </div>
<?php else: ?>
    <h4>Configure your eBot Installation</h4>
    <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>?step=3" method="POST">
        <legend>Log-Paths</legend>
        <div class="control-group">
            <label class="control-label" for="log">Full Match-Log Path</label>
            <div class="controls">
                <input type="text" id="log" name="log" value="<?php if(!empty($_POST['log'])) echo $_POST['log']; ?>" placeholder="e.g. /home/ebot/eBot-CSGO/logs/log_match" class="input-xxlarge">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="admin_log">Full Admin-Match-Log Path</label>
            <div class="controls">
                <input type="text" id="admin_log" name="admin_log" value="<?php if(!empty($_POST['admin_log'])) echo $_POST['admin_log']; ?>" placeholder="e.g. /home/ebot/eBot-CSGO/logs/log_match_admin" class="input-xxlarge">
            </div>
        </div>
        <legend>Demos</legend>
        <div class="control-group">
            <label class="control-label" for="demo">Full Demo Path</label>
            <div class="controls">
                <input type="text" id="demo" name="demo" value="<?php if(!empty($_POST['demo'])) echo $_POST['demo']; ?>" placeholder="e.g. /home/ebot/eBot-CSGO/demos" class="input-xxlarge">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="demoDownload">Make Demos downloadable?</label>
            <div class="controls">
                <input type="checkbox" id="demoDownload" value="true" name="demoDownload" <?php if(!empty($_POST['demoDownload'])) echo 'checked="checked"'; ?>>
            </div>
        </div>
        <legend>Server Configuration</legend>
        <div class="control-group">
            <label class="control-label">IP and Port</label>
            <div class="controls">
                <input type="text" id="ip" name="ip" value="<?php if(!empty($_POST['ip'])) echo $_POST['ip']; ?>" placeholder="e.g. 192.168.0.1" class="input-xlarge">&nbsp;&#58;&nbsp;
                <input type="text" id="port" name="port" value="<?php if(!empty($_POST['port'])) echo $_POST['port']; ?>" placeholder="e.g. 12360">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">LAN or NET</label>
            <div class="controls">
                <select name="mode" class="input-small"><option value="lan" <?php if (!empty($_POST['mode']) && $_POST['mode'] == 'lan') echo 'selected'; ?>>LAN</option><option value="net" <?php if (!empty($_POST['mode']) && $_POST['mode'] == 'net') echo 'selected'; ?>>NET</option></select>
                <span class="help-inline">If LAN, SERVER-IP and GOTV-IP will be shown public. Hidden on NET.</span>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <input type="hidden" name="createConfig" value="true">
                <input type="submit" class="btn" value="Create Config">
            </div>
        </div>
    </form>

    <?php if (!empty($_POST['log']) && !empty($_POST['admin_log']) && !empty($_POST['demo']) && !empty($_POST['ip']) && !empty($_POST['port']) && !empty($_POST['mode'])): ?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>Success!</h4>
            <p>Configuration was created successfully!</p>
        </div>
        <p>Copy the following code into the /config/app_user.yml and save it.</p>
        <pre>log_match: <?php echo $_POST['log']; ?>

log_match_admin: <?php echo $_POST['admin_log']; ?>

demo_path: <?php echo $_POST['demo']; ?>


# true or false, whether demos will be downloaded by the ebot server
# the demos can be downloaded at the matchpage, if it's true
demo_download: <?php echo ($_POST['demoDownload'] == true ? 'true' : 'false') ?>


ebot_ip: <?php echo $_POST['ip']; ?>

ebot_port: <?php echo $_POST['port']; ?>


# lan or net, it's to display the server IP or the GO TV IP
# net mode display only started match on home page
mode: <?php echo $_POST['mode']; ?></pre>
        <?php $_SESSION['ebot_web_configuration']['status'] = 'success'; ?>
        <?php $_SESSION['ebot_web_configuration']['ip'] = $_POST['ip']; ?>
        <?php $_SESSION['ebot_web_configuration']['port'] = $_POST['port']; ?>
        <button class="btn">Next Step</button>
    <?php elseif (isset($_POST['createConfig'])): ?>
        <div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>Error!</h4>
            <p>Please fill in all fields.</p>
        </div>
    <?php endif; ?>
<?php endif; ?>
