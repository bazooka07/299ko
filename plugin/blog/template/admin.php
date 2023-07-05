<?php
defined('ROOT') OR exit('No direct script access allowed');
include_once(ROOT . 'admin/header.php');
?>

<?php if ($mode == 'list') { ?>
    <section>
        <header>Liste des articles de blog</header>
            <a class="button" href="index.php?p=blog&action=edit">Ajouter</a>
            <a target="_blank" class="button" href="<?php echo $runPlugin->getPublicUrl(); ?>rss.html">Flux RSS</a>
        <table>
            <tr>
                <th>Titre</th>
                <th>Date</th>
                <th></th>
            </tr>
            <?php foreach ($newsManager->getItems() as $k => $v) { ?>
                <tr>
                    <td><?php echo $v->getName(); ?></td>
                    <td><?php echo util::formatDate($v->getDate(), 'en', 'fr'); ?></td>
                    <td>
                        <a href="index.php?p=blog&action=edit&id=<?php echo $v->getId(); ?>" class="button">Modifier</a>
                        <?php if ($newsManager->countComments($v->getId()) > 0) { ?><a href="index.php?p=blog&action=listcomments&id=<?php echo $v->getId(); ?>" class="button">Commentaires (<?php echo $newsManager->countComments($v->getId()); ?>)</a><?php } ?>
                        <a href="index.php?p=blog&action=del&id=<?php echo $v->getId(); ?>&token=<?php echo administrator::getToken(); ?>" onclick = "if (!confirm('Supprimer cet élément ?'))
                                            return false;" class="button alert">Supprimer</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </section>
<?php } ?>

<?php if ($mode == 'edit') { ?>
    <form method="post" action="index.php?p=blog&action=save" enctype="multipart/form-data">
        <?php show::adminTokenField(); ?>
        <input type="hidden" name="id" value="<?php echo $news->getId(); ?>" />
        <?php if ($pluginsManager->isActivePlugin('galerie')) { ?>
            <input type="hidden" name="imgId" value="<?php echo $news->getImg(); ?>" />
        <?php } ?>
        <div class='tabs-container'>
            <ul class="tabs-header">
                <li class="default-tab"><i class="fa-solid fa-file-pen"></i> Contenu</li>
                <li><i class="fa-regular fa-newspaper"></i> Introduction</li>
                <li><i class="fa-solid fa-heading"></i> Titre</li>
                <li><i class="fa-solid fa-sliders"></i> Paramètres</li>
                <?php if ($pluginsManager->isActivePlugin('galerie')) { ?>
                <li><i class="fa-regular fa-image"></i> Image à la une</li>
                <?php } ?>
            </ul>
            <ul class="tabs">
                <li class="tab">
                    <label for="content">Contenu</label><br>
                    <textarea name="content" id="content" class="editor"><?php echo $core->callHook('beforeEditEditor', $news->getContent()); ?></textarea><br>
                    <?php filemanagerDisplayManagerButton(); ?>
                </li>
                <li class="tab">
                    <label for="intro">Contenu d'introduction</label><br>
                    <textarea name="intro" id="intro" class="editor"><?php echo $core->callHook('beforeEditEditor', $news->getIntro()); ?></textarea><br>
                    <?php filemanagerDisplayManagerButton(); ?>
                </li>
                <li class='tab'>
                    <label for="name">Titre</label><br>
                    <input type="text" name="name" id="name" value="<?php echo $news->getName(); ?>" required="required" />
                    <?php if ($showDate) { ?>
                        <label for="date">Date</label><br>
                        <input placeholder="Exemple : 2017-07-06 12:28:51" type="date" name="date" id="date" value="<?php echo $news->getDate(); ?>" required="required" />
                    <?php } ?>
                </li>
                <li class='tab'>
                    <header>Paramètres de la news</header>
                    <p>
                        <input <?php if ($news->getdraft()) { ?>checked<?php } ?> type="checkbox" name="draft" id="draft"/>
                        <label for="draft">Ne pas publier (brouillon)</label>
                    </p>
                    <?php if ($runPlugin->getConfigVal('comments')) { ?>
                        <p>
                            <input <?php if ($news->getCommentsOff()) { ?>checked<?php } ?> type="checkbox" name="commentsOff" id="commentsOff"/>
                            <label for="commentsOff">Désactiver les commentaires pour cet article</label>
                        </p>
                    <?php } ?>
                </li>
                        <?php if ($pluginsManager->isActivePlugin('galerie')) { ?>
            <li class='tab'>
                <header>Image à la une</header>
                    <?php if (galerie::searchByfileName($news->getImg())) { ?><input type="checkbox" name="delImg" id="delImg" /><label for="delImg">Supprimer l'image à la une</label>
                    <?php } else { ?><label for="file">Fichier (png, jpg, jpeg, gif)</label><br><input type="file" name="file" id="file" accept="image/*" /><?php } ?>
                    <br>
                    <?php if (galerie::searchByfileName($news->getImg())) { ?><img src="<?php echo UPLOAD; ?>galerie/<?php echo $news->getImg(); ?>" alt="<?php echo $news->getImg(); ?>" /><?php } ?>
            </li>
        <?php } ?>
            </ul>
        </div>
        <p><button type="submit" class="floating button" title='Enregistrer'><i class="fa-regular fa-floppy-disk"></i></button></p>
    </form>
<?php } ?>

<?php if ($mode == 'listcomments') { ?>
    <section>
        <header>Liste des commentaires</header>
        <ul class="tabs_style">
            <li><a class="button" href="index.php?p=blog">Retour à la liste des news</a></li>
        </ul>
        <table>
            <tr>
                <th>Commentaire</th>
                <th></th>
            </tr>
            <?php foreach ($newsManager->getComments() as $k => $v) { ?>
                <tr>
                    <td>
                        <?php echo $v->getAuthor(); ?> <i><?php echo $v->getAuthorEmail(); ?></i> - <?php echo util::formatDate($v->getDate(), 'en', 'fr'); ?></b> :<br><br>
                        <form id="comment<?php echo $v->getId(); ?>" method="post" action="index.php?p=blog&action=updatecomment&id=<?php echo $_GET['id']; ?>&idcomment=<?php echo $v->getId(); ?>&token=<?php echo administrator::getToken(); ?>"><textarea name="content<?php echo $v->getId(); ?>"><?php echo $v->getContent(); ?></textarea></form>
                    </td>
                    <td>
                        <a onclick="updateComment(<?php echo $v->getId(); ?>);" href="javascript:" class="button">Enregistrer</a>
                        <a href="index.php?p=blog&action=delcomment&id=<?php echo $_GET['id']; ?>&idcomment=<?php echo $v->getId(); ?>&token=<?php echo administrator::getToken(); ?>" onclick = "if (!confirm('Supprimer cet élément ?'))
                                            return false;" class="button alert">Supprimer</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
        <script>
            function updateComment(id) {
                document.getElementById('comment' + id).submit();
            }
        </script>
    </section>
<?php } ?>

<?php include_once(ROOT . 'admin/footer.php'); ?>