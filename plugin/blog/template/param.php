<form method="post" action="index.php?p=blog&action=saveconf">
    <?php show::adminTokenField(); ?>
    <script>
        function onCheckAuthor() {
            if (document.getElementById("displayAuthor").checked) {
                document.getElementById("author-fields").style.display = 'block';
            } else {
                document.getElementById("author-fields").style.display = 'none';
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            onCheckAuthor();
            document.getElementById("displayAuthor").addEventListener("click", function () {
                onCheckAuthor();
            });
        });
    </script>
    <div class='form'>
        <input <?php if ($runPlugin->getConfigVal('hideContent')) { ?>checked<?php } ?> type="checkbox" name="hideContent" id="hideContent" aria-describedby='hideContentDesc' />
        <label for="hideContent">Masquer le contenu des articles dans la liste</label>
        <div class='tooltip'>
            <span id='hideContentDesc'>Si cette case est cochée, n'affiche que le titre dans la liste des articles.</span>
        </div>
    </div>
    <div class='form'>
        <input <?php if ($runPlugin->getConfigVal('comments')) { ?>checked<?php } ?> type="checkbox" name="comments" id="comments" />
        <label for="comments">Autoriser les commentaires</label>
    </div>
    <div class='form'>
        <label for="label">Titre de page</label><br>
        <input type="text" name="label" id="label" value="<?php echo $runPlugin->getConfigVal('label'); ?>" />
    </div>
    <div class='form'>
        <label for="itemsByPage">Nombre d'entrées par page</label><br>
        <input type="number" name="itemsByPage" id="itemsByPage" value="<?php echo $runPlugin->getConfigVal('itemsByPage'); ?>" />
    </div>
    <div class='form'>
        <input <?php if ($runPlugin->getConfigVal('displayAuthor')) { ?>checked<?php } ?> type="checkbox" name="displayAuthor" id="displayAuthor" />
        <label for="displayAuthor">Afficher le bloc 'auteur'</label>
    </div>
    <div id="author-fields">
        <div class='form'>
            <label for="authorName">Nom de l'auteur</label><br>
            <input type="text" name="authorName" id="authorName" value="<?php echo $runPlugin->getConfigVal('authorName'); ?>" />
        </div>
        <div class='form'>
            <label for="authorAvatar">Image de l'auteur</label><br>
            <input type="url" name="authorAvatar" id="authorAvatar" value="<?php echo $runPlugin->getConfigVal('authorAvatar'); ?>" />
            <?php filemanagerDisplayManagerButton(); ?>
        </div>
        <div class='form'>
            <label for="authorBio">Biographie</label><br>
            <textarea name="authorBio" id="authorBio" class="editor"><?php echo $core->callHook('beforeEditEditor', $runPlugin->getConfigVal('authorBio')); ?></textarea><br>
        </div>
    </div>

    <div class='form'><button type="submit" class="button">Enregistrer</button></div>
</form>