<?php if ($_SESSION['mysql_connection']['status'] != 'success' || $_SESSION['createAdmin']['status'] != 'success' || $_SESSION['ebot_web_configuration']['status'] != 'success') : ?>
    <div class="alert alert-error">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <h4>Error!</h4>
        Error missing data. Please restart the installation process.
    </div>
<?php else: ?>
    <h4>Configure your eBot-Server</h4>
    <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>?step=4" method="POST">
        <div class="control-group">
            <label class="control-label" for="pause">Pause Method</label>
            <div class="controls">
                <select name="pause"><option value="nextRound">nextRound</option><option value="instantConfirm">instantConfirm</option><option value="instantNoConfirm">instantNoConfirm</option></select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="gotv">GOTV Record</label>
            <div class="controls">
                <select name="gotv"><option value="knifestart">Record at Kniferound</option><option value="matchstart">Record at Matchstart</option></select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="demo">Demo Download</label>
            <div class="controls">
                <input type="checkbox" id="demo" value="true" name="demo" <?php if(!empty($_POST['demo'])) echo 'checked="checked"'; ?>>
                <span class="help-inline">nextRound or instantConfirm or instantNoConfirm</span>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="delay">Delay Ready Countdown</label>
            <div class="controls">
                <input type="checkbox" id="delay" value="true" name="delay" <?php if(!empty($_POST['delay'])) echo 'checked="checked"'; ?>>
                <span class="help-inline">If checked, players have to ability to abort the matchstart at a 10 second countdown</span>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <input type="hidden" name="createServerConfig" value="true">
                <input type="submit" class="btn" value="Create Server-Config">
            </div>
        </div>
    </form>

    <?php if (!empty($_POST['pause']) && !empty($_POST['gotv'])): ?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>Success!</h4>
            <p>Configuration was created successfully!</p>
        </div>
        <p>Copy the following code into the eBot-CSGO/config/config.ini and save it.</p>
        <pre>; eBot - A bot for match management for CS:GO
; @license     http://creativecommons.org/licenses/by/3.0/ Creative Commons 3.0
; @author      Julien Pardons <julien.pardons@esport-tools.net>
; @version     3.0
; @date        21/10/2012

[BDD]
MYSQL_IP = "<?php echo $_SESSION['mysql_connection']['address']; ?>"
MYSQL_PORT = "<?php echo $_SESSION['mysql_connection']['port']; ?>"
MYSQL_USER = "<?php echo $_SESSION['mysql_connection']['username']; ?>"
MYSQL_PASS = "<?php echo $_SESSION['mysql_connection']['password']; ?>"
MYSQL_BASE = "<?php echo $_SESSION['mysql_connection']['database']; ?>"

[Config]
BOT_IP = "<?php echo $_SESSION['ebot_web_configuration']['ip']; ?>"
BOT_PORT = <?php echo $_SESSION['ebot_web_configuration']['port']; ?>

MANAGE_PLAYER = 1
DELAY_BUSY_SERVER = 120
NB_MAX_MATCHS = 0
PAUSE_METHOD = "<?php echo $_POST['pause']; ?>" ; nextRound or instantConfirm or instantNoConfirm

[Match]
LO3_METHOD = "csay" ; restart or csay or esl
KO3_METHOD = "csay" ; restart or csay or esl
DEMO_DOWNLOAD = <?php echo ($_POST['demo'] == true ? 'true' : 'false') ?> ; true or false :: whether gotv demos will be downloaded from the gameserver after matchend or not

[MAPS]
MAP[] = "de_dust2_se"
MAP[] = "de_nuke_se"
MAP[] = "de_inferno_se"
MAP[] = "de_mirage_ce"
MAP[] = "de_train_se"
MAP[] = "de_cache"
MAP[] = "de_season"

[WORKSHOP IDs]
WORKSHOP["de_dust2_se"] = "125488374"
WORKSHOP["de_nuke_se"] = "125498553"
WORKSHOP["de_inferno_se"] = "125499116"
WORKSHOP["de_mirage_ce"] = "123769103"
WORKSHOP["de_train_se"] = "125498231"
WORKSHOP["de_cache"] = "125670041"
WORKSHOP["de_season"] = "125689191"

[Settings]
COMMAND_STOP_DISABLED = false
RECORD_METHOD = "<?php echo $_POST['gotv']; ?>" ; matchstart or knifestart
DELAY_READY = <?php echo ($_POST['delay'] == true ? 'true' : 'false') ?></pre>
        <?php $_SESSION['ebot_server_configuration']['status'] = 'success'; ?>
        <button class="btn">Next Step</button>
    <?php elseif (!empty($_POST['createServerConfig'])): ?>
        <div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>Error!</h4>
            <p>Please fill in all fields.</p>
        </div>
    <?php endif; ?>
<?php endif; ?>