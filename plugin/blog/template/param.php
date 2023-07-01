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
    <p>
        <input <?php if ($runPlugin->getConfigVal('displayAuthor')) { ?>checked<?php } ?> type="checkbox" name="displayAuthor" id="displayAuthor" />
        <label for="displayAuthor">Afficher l'auteur</label>
    </p>
    <p>
        <label for="authorName">Nom de l'auteur</label><br>
        <input type="text" name="authorName" id="authorName" value="<?php echo $runPlugin->getConfigVal('authorName'); ?>" />
    </p>
    <p>
        <label for="authorAvatar">Image de l'auteur</label><br>
        <input type="url" name="authorAvatar" id="authorAvatar" value="<?php echo $runPlugin->getConfigVal('authorAvatar'); ?>" />
        <?php filemanagerDisplayManagerButton(); ?>
    </p>
    <p>
        <label for="authorBio">Biographie</label><br>
        <textarea name="authorBio" id="authorBio" class="editor"><?php echo $core->callHook('beforeEditEditor', $runPlugin->getConfigVal('authorBio')); ?></textarea><br>
    </p>

    <p><button type="submit" class="button">Enregistrer</button></p>
</form>