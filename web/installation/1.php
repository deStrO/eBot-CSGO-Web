<?php if (!empty($_POST['address']) && !empty($_POST['port']) && !empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['database'])) {
    if ($_POST['address'] == 'localhost')
        $_POST['address'] = '127.0.0.1'; 

    $link = mysql_connect($_POST['address'].':'.$_POST['port'], $_POST['username'], $_POST['password']);
    if ($link) {
        $db_selected = mysql_select_db($_POST['database'], $link);
        if ($db_selected) {
            $error = 'connected';
        }
    } else {
        $error = mysql_error();
    }
}
?>

<h4>Configure MySQL Database</h4>
<p>Please enter your MySQL Database Information</p>
<form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>?step=1" method="POST">
    <div class="control-group">
        <label class="control-label" for="address">MySQL Address</label>
        <div class="controls">
            <input type="text" id="address" name="address" value="<?php if(!empty($_POST['address'])) echo $_POST['address']; ?>">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="port">MySQL Port</label>
        <div class="controls">
            <input type="text" id="port" name="port" value="<?php if(!empty($_POST['port'])) echo $_POST['port']; else echo '3306'; ?>">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="username">MySQL Username</label>
        <div class="controls">
            <input type="text" id="username" name="username" value="<?php if(!empty($_POST['username'])) echo $_POST['username']; ?>">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="password">MySQL Password</label>
        <div class="controls">
            <input type="text" id="password" name="password" value="<?php if(!empty($_POST['password'])) echo $_POST['password']; ?>">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="database">MySQL Database</label>
        <div class="controls">
            <input type="text" id="database" name="database" value="<?php if(!empty($_POST['database'])) echo $_POST['database']; ?>">
        </div>
    </div>
    <?php if ($error != 'connected'): ?>
        <div class="control-group">
            <div class="controls">
                <input type="submit" class="btn" value="Check Connection...">
            </div>
        </div>
    <?php endif; ?>
</form>

<?php
if (!empty($_POST['address']) && !empty($_POST['port']) && !empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['database'])) {
    if ($error == 'connected') { 
        $_SESSION['mysql_connection'] = array('address' => $_POST['address'], 'port' => $_POST['port'], 'username' => $_POST['username'], 'password' => $_POST['password'], 'database' => $_POST['database'], 'status' => 'pending');
        ?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>Success!</h4>
            <p>The connection was established successfully!</p>
        </div>
        <p>Copy the following code into the /config/databases.yml and save it.</p>
        <pre># You can find more information about this file on the symfony website:
    # http://www.symfony-project.org/reference/1_4/en/07-Databases

    all:
    doctrine:
    class: sfDoctrineDatabase
    param:
        dsn: mysql:host=<?php echo $_POST['address']; if ($_POST['port'] != '3306') echo ':'.$_POST['port']; ?>;dbname=<?php echo $_POST['database']; ?>

        username: <?php echo $_POST['username']; ?>

        password: <?php echo $_POST['password']; ?></pre>
        <button class="btn">Next Step</button>
        <?php
     } else { ?>
        <div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>Error!</h4>
            <?php echo $error; ?>
        </div>
        <?php
    }
}

?>