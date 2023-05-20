<?php
defined('ROOT') OR exit('No direct script access allowed');
include_once(ROOT . 'admin/header.php');
?>

<?php if ($mode == 'list') { ?>
    <section>
        <header>Liste des images</header>
        <ul class="tabs_style">
            <li><a class="button" href="index.php?p=galerie&action=edit">Ajouter</a></li>
            <li><a class="button showall" data-state="hidden" href="javascript:">Basculer sur l'affichage des éléments cachés</a></li>
        </ul>
        <table>
            <tr>
                <th>Aperçu</th>
                <th>Titre</th>
                <th>Catégorie</th>
                <th>Adresse</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($galerie->getItems() as $k => $v) { ?>
                <tr class="<?php if ($v->getHidden()) { ?>hidden<?php } else { ?>visible<?php } ?>">
                    <td><img width="128" src="<?php echo UPLOAD . 'galerie/' . $v->getImg(); ?>" alt="<?php echo $v->getImg(); ?>" /></td>
                    <td><?php echo $v->getTitle(); ?></td>
                    <td><?php echo $v->getCategory(); ?></td>
                    <td><input readonly="readonly" type="text" value="<?php echo $core->getConfigVal('siteUrl') . str_replace('..', '', UPLOAD) . 'galerie/' . $v->getImg(); ?>" /></td>
                    <td>
                        <a href="index.php?p=galerie&action=edit&id=<?php echo $v->getId(); ?>" class="button">Modifier</a>
                        <a href="index.php?p=galerie&action=del&id=<?php echo $v->getId(); ?>&token=<?php echo administrator::getToken(); ?>" onclick = "if (!confirm('Supprimer cet élément ?'))
                                        return false;" class="button alert">Supprimer</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </section>
<?php } ?>

<?php if ($mode == 'edit') { ?>
    <form method="post" action="index.php?p=galerie&action=save" enctype="multipart/form-data">
        <?php show::adminTokenField(); ?>
        <section>
            <input type="hidden" name="id" value="<?php echo $item->getId(); ?>" />
            <header>Paramètres</header>
            <p>
                <input <?php if ($item->getHidden()) { ?>checked<?php } ?> type="checkbox" name="hidden" id="hidden"/>
                <label for="hidden">Rendre invisible</label>
            </p>

            <p>
                <label for="category">
                    Catégorie(s) existante(s) : 
                    <?php foreach ($galerie->listCategories() as $k => $v) { ?>
                        <a class="category" href="javascript:" title="Sélectionner la catégorie '<?php echo $v; ?>'"><i class="fa-regular fa-folder-open"></i><?php echo $v; ?></a>
                    <?php } ?>
                </label><br>
                <input type="text" name="category" id="category" placeholder="Catégorie de l'image" value="<?php echo $item->getCategory(); ?>" />
            </p>
        </section>
        <section>
            <header>Contenu</header>
            <p>
                <label for="title">Titre</label><br>
                <input type="text" name="title" id="title" value="<?php echo $item->getTitle(); ?>" required="required" />
            </p>
            <p>
                <label for="date">Date</label><br>
                <input type="date" name="date" id="date" value="<?php echo $item->getDate(); ?>" /> 
            </p>

            <p>
                <label for="content">Contenu</label><br>
                <textarea name="content" id="content" class="editor"><?php echo $core->callHook('beforeEditEditor', $item->getContent()); ?></textarea><br>
                <?php filemanagerDisplayManagerButton(); ?>
            </p>
        </section>
        <section>
            <header>Image</header>
            <p>
                <label for="file">Fichier (png, jpg, jpeg, gif)</label><br>
                <input type="file" name="file" id="file" accept="image/*" <?php if ($item->getImg() == '') { ?>required="required"<?php } ?> />
                <br>
                <?php if ($item->getImg() != '') { ?><img src="<?php echo UPLOAD; ?>galerie/<?php echo $item->getImg(); ?>" alt="<?php echo $item->getImg(); ?>" /><?php } ?>
            </p>
        </section>
        <p><button type="submit" class="button">Enregistrer</button></p>
    </form>
<?php } ?>

<?php
include_once(ROOT . 'admin/footer.php');
