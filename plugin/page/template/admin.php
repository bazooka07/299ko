<?php
defined('ROOT') OR exit('No direct script access allowed');
include_once(ROOT . 'admin/header.php');

if ($mode == 'list') {
    ?>
    <section>
        <header>Liste des pages</header>
        <a class="button" href=".?p=page&amp;action=edit">Ajouter une page</a>
        <a class="button" href=".?p=page&amp;action=edit&parent=1">Ajouter un item parent</a>
        <a class="button" href=".?p=page&amp;action=edit&link=1">Ajouter un lien externe</a>
        <?php if ($lost != '') { ?>
            <p>Des pages "fantômes" pouvant engendrer des dysfonctionnements ont été trouvées. <a href=".?p=page&amp;action=maintenance&id=<?php echo $lost; ?>&token=<?php echo administrator::getToken(); ?>">Cliquez ici</a> pour exécuter le script de maintenance.</p>
        <?php } ?>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Adresse</th>
                    <th>Position</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($page->getItems() as $k => $pageItem)
                    if ((int) $pageItem->getParent() == 0 && ($pageItem->targetIs() != 'plugin' || ($pageItem->targetIs() == 'plugin' && $pluginsManager->isActivePlugin($pageItem->getTarget())))) {
                        ?>
                        <tr>
                            <td><?php echo $pageItem->getName(); ?></td>
                            <td><?php if ($pageItem->targetIs() != 'parent') { ?><input readonly="readonly" type="text" value="<?php echo $page->makeUrl($pageItem); ?>" /><?php } ?></td>
                            <td>
                                <a class="up" href=".?p=page&action=up&id=<?php echo $pageItem->getId(); ?>&token=<?php echo administrator::getToken(); ?>"><i class="fa-regular fa-circle-up" title="Monter l'élément"></i></a>
                                <a class="down" href=".?p=page&action=down&id=<?php echo $pageItem->getId(); ?>&token=<?php echo administrator::getToken(); ?>"><i class="fa-regular fa-circle-down" title="Descendre l'élément"></i></a>
                            </td>
                            <td>
                                <div role="group">
                                    <a class="button" href=".?p=page&amp;action=edit&amp;id=<?php echo $pageItem->getId(); ?>">Modifier</a> 
                                    <?php if (!$pageItem->getIsHomepage() && $pageItem->targetIs() != 'plugin') { ?><a class="button alert" href=".?p=page&amp;action=del&amp;id=<?php echo $pageItem->getId() . '&amp;token=' . administrator::getToken(); ?>" onclick = "if (!confirm('Supprimer cet élément ?'))
                                                                return false;">Supprimer</a><?php } ?>	
                                </div>
                            </td>
                        </tr>
                        <?php
                        foreach ($page->getItems() as $k => $pageItemChild)
                            if ($pageItemChild->getParent() == $pageItem->getId() && ($pageItemChild->targetIs() != 'plugin' || ($pageItemChild->targetIs() == 'plugin' && $pluginsManager->isActivePlugin($pageItemChild->getTarget())))) {
                                ?>
                                <tr>
                                    <td>▸ <?php echo $pageItemChild->getName(); ?></td>
                                    <td><input readonly="readonly" type="text" value="<?php echo $page->makeUrl($pageItemChild); ?>" /></td>
                                    <td>
                                        <a class="up" href=".?p=page&action=up&id=<?php echo $pageItemChild->getId(); ?>&token=<?php echo administrator::getToken(); ?>"><i class="fa-regular fa-circle-up" title="Monter l'élément"></i></a>
                                        <a class="down" href=".?p=page&action=down&id=<?php echo $pageItemChild->getId(); ?>&token=<?php echo administrator::getToken(); ?>"><i class="fa-regular fa-circle-down" title="Descendre l'élément"></i></a>
                                    </td>
                                    <td>
                                        <div role="group">
                                            <a class="button" href=".?p=page&amp;action=edit&amp;id=<?php echo $pageItemChild->getId(); ?>">Modifier</a> 
                                            <?php if (!$pageItemChild->getIsHomepage() && $pageItemChild->targetIs() != 'plugin') { ?><a class="button alert" href=".?p=page&amp;action=del&amp;id=<?php echo $pageItemChild->getId() . '&amp;token=' . administrator::getToken(); ?>" onclick = "if (!confirm('Supprimer cet élément ?'))
                                                                            return false;">Supprimer</a><?php } ?>	
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                    }
                ?>
            </tbody>
        </table>
    </section>
<?php } ?>

<?php if ($mode == 'edit' && !$isLink && !$isParent && $pageItem->targetIs() != 'plugin') { ?>
    <form method="post" action=".?p=page&amp;action=save" enctype="multipart/form-data">
        <?php show::adminTokenField(); ?>
        <section>
            <input type="hidden" name="id" value="<?php echo $pageItem->getId(); ?>" />
            <?php if ($pluginsManager->isActivePlugin('galerie')) { ?>
                <input type="hidden" name="imgId" value="<?php echo $pageItem->getImg(); ?>" />
            <?php } ?>

            <header>Paramètres</header>
            <p>
                <input <?php if ($pageItem->getIsHomepage()) { ?>checked<?php } ?> type="checkbox" name="isHomepage" id="isHomepage" />
                <label for="isHomepage">Page d'accueil</label>
            </p>
            <p>
                <input <?php if ($pageItem->getIsHidden()) { ?>checked<?php } ?> type="checkbox" name="isHidden" id="isHidden" /> 
                <label for="isHidden">Ne pas afficher dans le menu</label>
            </p>
            <p>
                <label for="parent">Item parent</label><br>
                <select name="parent" id="parent">
                    <option value="">Aucun</option>
                    <?php
                    foreach ($page->getItems() as $k => $v)
                        if ($v->targetIs() == 'parent') {
                            ?>
                            <option <?php if ($v->getId() == $pageItem->getParent()) { ?>selected<?php } ?> value="<?php echo $v->getId(); ?>"><?php echo $v->getName(); ?></option>
                        <?php } ?>
                </select>
            </p>
            <p>
                <label for="cssClass">Classe CSS</label>
                <input type="text" name="cssClass" id="cssClass" value="<?php echo $pageItem->getCssClass(); ?>" />
            </p>
            <p>
                <label for="position">Position</label>
                <input type="number" name="position" id="position" value="<?php echo $pageItem->getPosition(); ?>" />
            </p>
            <p>
                <label for="_password">Restreindre l'accès avec un mot de passe</label>
                <input type="password" name="_password" id="_password" value="" />
            </p>
            <?php if ($pageItem->getPassword() != '') { ?>
                <p>
                    <input type="checkbox" name="resetPassword" id="resetPassword" /> 
                    <label for="resetPassword">Retirer la restriction par mot de passe</label>
                </p>
            <?php } ?>
        </section>
        <section>
            <header>SEO</header>
            <p>
                <input <?php if ($pageItem->getNoIndex()) { ?>checked<?php } ?> type="checkbox" name="noIndex" id="noIndex"/>
                <label for="noIndex">Interdire l'indexation</label>
            </p>
            <p>
                <label for="metaTitleTag">Meta title</label>
                <input type="text" name="metaTitleTag" id="metaTitleTag" value="<?php echo $pageItem->getMetaTitleTag(); ?>" />
            </p>
            <p>
                <label for="metaDescriptionTag">Meta description</label>
                <input type="text" name="metaDescriptionTag" id="metaDescriptionTag" value="<?php echo $pageItem->getMetaDescriptionTag(); ?>" />
            </p>
        </section>
        <section>
            <header>Contenu</header>
            <p>
                <label for="name">Nom</label><br>
                <input type="text" name="name" id="name" value="<?php echo $pageItem->getName(); ?>" required="required" />
            </p>
            <p>
                <label for="mainTitle">Titre de page</label><br>
                <input type="text" name="mainTitle" id="mainTitle" value="<?php echo $pageItem->getMainTitle(); ?>" />
            </p>
            <p>
                <label for="file">Inclure un fichier .tpl au lieu du contenu
                    <select name="file" id="file">
                        <option value="">--</option>
                        <?php foreach ($page->listTemplates() as $file) { ?>
                            <option <?php if ($file == $pageItem->getFile()) { ?>selected<?php } ?> value="<?php echo $file; ?>"><?php echo $file; ?></option>
                        <?php } ?>
                    </select>
            </p>
            <p>
                <label for="content">Contenu</label>
                <textarea name="content" id="content" class="editor"><?php echo $core->callHook('beforeEditEditor', $pageItem->getContent()); ?></textarea><br>
                <?php filemanagerDisplayManagerButton(); ?>
            </p>
        </section>

        <?php if ($pluginsManager->isActivePlugin('galerie')) { ?>
            <section>
                <header>Image à la une</header>
                <p>
                    <?php if (galerie::searchByfileName($pageItem->getImg())) { ?><input type="checkbox" name="delImg" id="delImg" />
                        <label for="delImg">Supprimer l'image à la une</label>
                    <?php } else { ?><label for="file">Fichier (png, jpg, jpeg, gif)</label><br><input type="file" name="file" id="file" accept="image/*" /><?php } ?>
                    <br><br>
                    <?php if (galerie::searchByfileName($pageItem->getImg())) { ?><img src="<?php echo $pageItem->getImgUrl(); ?>" alt="<?php echo $pageItem->getImg(); ?>" /><?php } ?>
                </p>
            </section>
        <?php } ?>
        <p>
            <button type="submit" class="button success">Enregistrer</button>
        </p>
    </form>
<?php } ?>

<?php if ($mode == 'edit' && ($isLink || $pageItem->targetIs() == 'plugin')) { ?>
    <section>
        <header>Modifier le lien</header>
        <form method="post" action=".?p=page&amp;action=save">
            <?php show::adminTokenField(); ?>
            <input type="hidden" name="id" value="<?php echo $pageItem->getId(); ?>" />
            <!--<input type="hidden" name="position" value="<?php echo $pageItem->getPosition(); ?>" />-->
            <p>
                <input <?php if ($pageItem->getIsHidden()) { ?>checked<?php } ?> type="checkbox" name="isHidden" id="isHidden" />
                <label for="isHidden">Ne pas afficher dans le menu</label>
            </p>
            <p>
                <label for="parent">Item parent</label><br>
                <select name="parent" id="parent">
                    <option value="">Aucun</option>
                    <?php
                    foreach ($page->getItems() as $k => $v)
                        if ($v->targetIs() == 'parent') {
                            ?>
                            <option <?php if ($v->getId() == $pageItem->getParent()) { ?>selected<?php } ?> value="<?php echo $v->getId(); ?>"><?php echo $v->getName(); ?></option>
                        <?php } ?>
                </select>
            </p>
            <p>
                <label for="name">Nom</label><br>
                <input type="text" name="name" id="name" value="<?php echo $pageItem->getName(); ?>" required="required" />
            </p>
            <?php if ($pageItem->targetIs() == 'plugin') { ?>
                <p>
                    <label for="target">Cible : <?php echo $pageItem->getTarget(); ?></label>
                    <input style="display:none;" type="text" name="target" id="target" value="<?php echo $pageItem->getTarget(); ?>" />
                </p>
            <?php } else { ?>
                <p>
                    <label for="target">Cible</label><br>
                    <input placeholder="Example : http://www.google.com" <?php if ($pageItem->targetIs() == 'plugin') { ?>readonly<?php } ?> type="url" name="target" id="target" value="<?php echo $pageItem->getTarget(); ?>" required="required" />
                </p>
            <?php } ?>
            <p>
                <label for="targetAttr">Ouverture</label><br>
                <select name="targetAttr" id="targetAttr">
                    <option value="_self" <?php if ($pageItem->getTargetAttr() == '_self') { ?>selected<?php } ?>>Même fenêtre</option>
                    <option value="_blank" <?php if ($pageItem->getTargetAttr() == '_blank') { ?>selected<?php } ?>>Nouvelle fenêtre</option>
                </select>
            </p>
            <p>
                <label for="cssClass">Classe CSS</label>
                <input type="text" name="cssClass" id="cssClass" value="<?php echo $pageItem->getCssClass(); ?>" />
            </p>
            <p>
                <label for="position">Position</label>
                <input type="number" name="position" id="position" value="<?php echo $pageItem->getPosition(); ?>" />
            </p>
            <p>
                <button type="submit" class="button success radius">Enregistrer</button>
            </p>
        </form>
    </section>
<?php } ?>

<?php if ($mode == 'edit' && $isParent) { ?>
    <section>
        <header>Modifier le parent</header>
        <form method="post" action=".?p=page&amp;action=save">
            <?php show::adminTokenField(); ?>
            <input type="hidden" name="id" value="<?php echo $pageItem->getId(); ?>" />
            <!--<input type="hidden" name="position" value="<?php echo $pageItem->getPosition(); ?>" />-->
            <input type="hidden" name="target" value="javascript:" />
            <p>
                <input <?php if ($pageItem->getIsHidden()) { ?>checked<?php } ?> type="checkbox" name="isHidden" id="isHidden"/> <label for="isHidden">Ne pas afficher dans le menu</label>
            </p>
            <p>
                <label for="name">Nom</label><br>
                <input type="text" name="name" id="name" value="<?php echo $pageItem->getName(); ?>" required="required" />
            </p>
            <p>
                <label for="cssClass">Classe CSS</label>
                <input type="text" name="cssClass" id="cssClass" value="<?php echo $pageItem->getCssClass(); ?>" />
            </p>
            <p>
                <label for="position">Position</label>
                <input type="number" name="position" id="position" value="<?php echo $pageItem->getPosition(); ?>" />
            </p>
            <p>
                <button type="submit" class="button success radius">Enregistrer</button>
            </p>
        </form>
    </section>
<?php } ?>

<?php include_once(ROOT . 'admin/footer.php'); ?>