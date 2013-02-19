<div class="well">
    <h1><?php echo __("eBot-CSGO"); ?></h1>
    <p><?php echo __("Bienvenue sur le panel d'administration de l'eBot CSGO, vous pouvez y gérer les matchs, mais aussi accéder aux différentes statistiques."); ?></p>
    <p><?php echo __("Vous pouvez commencer à utiliser le panel en accédant aux menus à gauche."); ?></p>
</div>
<div class="modal" style="position:relative; top:auto; left:auto; margin:0 auto 20px; z-index:1; width: auto; max-width:100%;">
    <div class="modal-header">
        <h4><?php echo __("News"); ?></h4>
    </div>
    <div class="modal-body" style="max-height: none;">
        <?php if (!empty($news)): ?>
            <?php echo nl2br($news); ?>
        <?php else: ?>
            <?php echo __("No news available."); ?>
        <?php endif; ?>
    </div>
</div>