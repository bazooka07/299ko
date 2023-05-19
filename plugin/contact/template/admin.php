<?php
defined('ROOT') OR exit('No direct script access allowed');
include_once(ROOT . 'admin/header.php');
?>

<section>
    <header>Contenu</header>
    <form method="post" action="index.php?p=contact&action=save">
        <?php show::adminTokenField(); ?>
        <p>
            <label>Avant le formulaire
                <textarea class="editor" name="content1"><?php echo $core->callHook('beforeEditEditor', $runPlugin->getConfigVal('content1')); ?></textarea><br>
                <?php filemanagerDisplayManagerButton(); ?>
            </label>
        </p>
        <p>
            <label>Après le formulaire</label><br>
            <textarea class="editor" name="content2"><?php echo $core->callHook('beforeEditEditor', $runPlugin->getConfigVal('content2')); ?></textarea><br>
            <?php filemanagerDisplayManagerButton(); ?>
        </p>
        <button type="submit" class="button">Enregistrer</button>
    </form>
</section>
<section>
    <header>Adresses email récoltées</header>
    <p>
        <label for='savedMails'>Adresses Email récoltées par le formulaire</label>
        <textarea readonly="readonly" id='savedMails'><?php echo $emails; ?></textarea>
    </p>

    <a href="index.php?p=contact&action=emptymails&token=<?php echo administrator::getToken(); ?>" class="button alert"
       onclick="return(confirm('Êtes-vous sûr de vouloir vider la base des adresses mail collectées ?'));">Supprimer la base</a> 

</section>

<?php include_once(ROOT . 'admin/footer.php'); ?>