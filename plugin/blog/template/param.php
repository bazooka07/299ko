<form method="post" action="index.php?p=blog&action=saveconf">
    <?php show::adminTokenField(); ?>

    <p>
        <input <?php if ($runPlugin->getConfigVal('hideContent')) { ?>checked<?php } ?> type="checkbox" name="hideContent" id="hideContent"/>
        <label for="hideContent">Masquer le contenu des articles dans la liste</label>
    </p>
    <p>
        <input <?php if ($runPlugin->getConfigVal('comments')) { ?>checked<?php } ?> type="checkbox" name="comments" id="comments" />
        <label for="comments">Autoriser les commentaires</label>
    </p>
    <p>
        <label for="label">Titre de page</label><br>
        <input type="text" name="label" id="label" value="<?php echo $runPlugin->getConfigVal('label'); ?>" />
    </p>
    <p>
        <label for="itemsByPage">Nombre d'entrées par page</label><br>
        <input type="number" name="itemsByPage" id="itemsByPage" value="<?php echo $runPlugin->getConfigVal('itemsByPage'); ?>" />
    </p>

    <p><button type="submit" class="button">Enregistrer</button></p>
</form>