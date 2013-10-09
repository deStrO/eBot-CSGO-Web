<?php

if (isset($_SESSION['mysql_connection'])) {
    $ADDRESS = $_SESSION['mysql_connection']['address'];
    $PORT = $_SESSION['mysql_connection']['port'];
    $USERNAME = $_SESSION['mysql_connection']['username'];
    $PASSWORD = $_SESSION['mysql_connection']['password'];
    $DATABASE = $_SESSION['mysql_connection']['database'];

    $link = mysql_connect($ADDRESS.':'.$PORT, $USERNAME, $PASSWORD);
    if ($link) {
        $db_selected = mysql_select_db($DATABASE, $link);
        if ($db_selected) {
            if (empty($_POST['createAdmin']) && $_SESSION['mysql_connection']['status'] == 'pending') {
                $import = file_get_contents( __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "sql" . DIRECTORY_SEPARATOR . "schema.sql");
                $import = preg_replace ("%/\*(.*)\*/%Us", '', $import);
                $import = preg_replace ("%^--(.*)\n%mU", '', $import);
                $import = preg_replace ("%^$\n%mU", '', $import);

                mysql_real_escape_string($import); 
                $import = explode (";", $import); 

                foreach ($import as $i){
                    if ($i != '' && $i != ' '){
                        mysql_query($i);
                    }
               }
               $_SESSION['mysql_connection']['status'] = 'success';
           } else {
                if (0 == strlen($_POST['password']) || 0 == strlen($_POST['password'])) {
                    $_SESSION['createAdmin']['status'] = 'error';
                } else {
                    $salt = md5(rand(100000, 999999).$_POST['username']);
                    $password = sha1($salt.$_POST['password']);
                    mysql_query("INSERT INTO `sf_guard_user` 
                        (`email_address`, `username`, `algorithm`, `salt`, `password`, `is_active`, `is_super_admin`, `created_at`, `updated_at`) 
                        VALUES 
                        ('".$_POST['email']."', '".$_POST['username']."', 'sha1', '".$salt."', '".$_POST['password']."', '1', '1', NOW(), NOW())");
                    $_SESSION['createAdmin']['status'] = 'success';
                }
           }
        }
    }
    ?>

    <h4>Create your Admin Account</h4>

    <?php if (empty($_POST['createAdmin']) || $_SESSION['createAdmin']['status'] == 'error'): ?>
        <?php if ($_SESSION['createAdmin']['status'] == 'error') echo '<p class="text-error">Please provide an username and a password.</p>' ?>
        <p>Please create your eBot Admin-Account:</p>
        <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>?step=2" method="POST">
            <div class="control-group">
                <label class="control-label" for="username">Username</label>
                <div class="controls">
                    <input type="text" id="username" name="username">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="password">Password</label>
                <div class="controls">
                    <input type="password" id="password" name="password">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="email">E-Mail</label>
                <div class="controls">
                    <input type="text" id="email" name="email">
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <input type="hidden" name="createAdmin" value="true">
                    <input type="submit" class="btn" value="Send">
                </div>
            </div>
        </form>
    <?php else: ?>        
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>Success!</h4>
            <p>Your Admin Account is now active.</p>
        </div>
        <button class="btn">Next Step</button>
    <?php endif;
} else { ?>
    <div class="alert alert-error">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <h4>Error!</h4>
        The MySQL-Connection Data was not found. Please restart the installation process.
    </div>
    <?php
}

?>