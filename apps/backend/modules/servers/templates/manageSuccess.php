<h3><?php echo __('%hostname% - Administration', array('%hostname%' => $server->getHostname())); ?></h3>

<hr />

<h2><?php echo __('Etat') ?></h2>

<?php if ($serverManager->getServerConnection()): ?>
    <pre><?php echo $serverManager->getStatus() ?></pre>
<?php endif ?>

<hr />

<h2><?php echo __('Actions') ?></h2>

<div class="row-fluid">
    <div class="span4 pagination-centered">
        <form method="post" action="<?php echo url_for('servers_action', array('sf_subject' => $server, 'do' => 'restart')) ?>" class="action">
            <ul class="unstyled">
                <?php echo new ServerManagerActionForm(null, array('action' => 'restart')) ?>
            </ul>

            <div class="form-actions">
                <input type="submit" value="<?php echo __('Restart') ?>" class="btn btn-primary" />
            </div>
        </form>
    </div>

    <div class="span4 pagination-centered">
        <form method="post" action="<?php echo url_for('servers_action', array('sf_subject' => $server, 'do' => 'changeMap')) ?>" class="action">
            <ul class="unstyled">
                <?php echo new ServerManagerActionForm(null, array('action' => 'changeMap')) ?>
            </ul>

            <div class="form-actions pagination-centered">
                <input type="submit" value="<?php echo __('Change map') ?>" class="btn btn-primary" />
            </div>
        </form>
    </div>

    <div class="span4 pagination-centered">
        <form method="post" action="<?php echo url_for('servers_action', array('sf_subject' => $server, 'do' => 'runConfig')) ?>" class="action">
            <ul class="unstyled">
                <?php echo new ServerManagerActionForm(null, array('action' => 'runConfig')) ?>
            </ul>

            <div class="form-actions pagination-centered">
                <input type="submit" value="<?php echo __('Run') ?>" class="btn btn-primary" />
            </div>
        </form>
    </div>
</div>
