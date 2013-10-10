<?php if ($_SESSION['mysql_connection']['status'] != 'success' || $_SESSION['createAdmin']['status'] != 'success' || $_SESSION['ebot_web_configuration']['status'] != 'success' || $_SESSION['ebot_server_configuration']['status'] != 'success'): ?>
    <div class="alert alert-error">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <h4>Error!</h4>
        Error missing data. Please restart the installation process.
    </div>
<?php else: ?>
    <h4>Manual Steps</h4>
    <p>To complete the installation process, you have to enter some steps manually over your ssh or console. Just follow the steps, you can copy &amp; paste the commands.</p><hr>
    <ul>
        <li>
            <b>Install nodeJS</b>
            <p>Get the latest version of nodeJS and follow the install instructions on-site: <a href="http://nodejs.org/" target="_blank">nodejs.org</a></p>
        </li>
        <li>
            <b>Install nodeJS depencies</b>
            <p>Switch to your eBot-CSGO directory and execute the following code:<br>
            <code>npm install socket.io archiver formidable</code></p>
        </li>
        <li>
            <b>composer.phar</b>
            <p>Download the latest version of the <a href="http://getcomposer.org/download/" target="_blank">composer</a>.<br>
            Install the package and requirements:</p>
            <ul>
                <li>Windows: <code>composer install</code></li>
                <li>Linux: <code>php composer.phar install</code></li>
            </ul>
        </li>
        <li>
            <b>Windows only:</b>
            <p>Open and edit the "websocket_server.bat" and enter the correct BOT_IP and BOT_PORT.</p>
        </li>
        <li>
            <b>Final Step:</b>
            <p>Start the eBot</p>
            <code>php bootstrap.php</code>
        </li>
    </ul>
    <button class="btn">Next Step</button>
<?php endif; ?>