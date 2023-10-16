<?php
/**
 * @copyright (C) 2022, 299Ko
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 * @author Maxence Cauderlier <mx.koder@gmail.com>
 * 
 * @package 299Ko https://github.com/299Ko/299ko
 */
defined('ROOT') OR exit('No direct script access allowed');

switch ($catDisplay) {
    case 'root':
        // Categories Container
        ?>
        <div class="list-item-container">
            <div class="list-item-list">
                <div>Nom</div>
                <div>Nombre d'éléments</div>
                <div>Actions</div>
            </div>
            <?php
            if (empty($this->imbricatedCategories)) {
                echo '<div class="list-item-list">Aucune catégorie.</div>';
            } else {
                foreach ($this->imbricatedCategories as $cat) {
                    $cat->outputAsList();
                }
            }
            ?>
        </div>
        <footer id="list-item-endlist">
            <div id="categorie-add-form-container" class="list-item-list">
                <form id="categorie-add-form" name="categorie-add-form" method="post" action="<?php echo $this->getAddCategoryUrl(); ?>" >
<h4 id="head-add-cat">Ajouter une catégorie</h4>
                    <?php show::adminTokenField(); ?> 
                    <div class="input-field">
                        <label class="active" for="category-add-label">Nom de la catégorie</label>
                        <input type="text" name="category-add-label" id="category-add-label" required/>
                        <label for="categoy-add-parentId">Catégorie parente</label>
                        <select name="category-add-parentId" id="category-add-parentId">
                            <option value="0">Aucune</option>
                            <?php
                            if (!empty($this->imbricatedCategories)) {
                                foreach($this->imbricatedCategories as $cat) {
                                    $cat->outputAsSelectOne(0);
                                    ?>
                                    
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <button class="btn" type="submit" id="list-item-add-btn">Ajouter une catégorie</button>
                </form>
            </div>
        </footer>
        
        <?php
        break;
    case 'sub':
        // Categories
        echo '<div class="list-item-list ';
        if ($this->hasChildren) {
            echo 'hasChildren"><i style="left:' . ($this->depth * 15 + 5 ) . 'px;" class="fa-solid fa-chevron-up list-item-toggle" title="Replier/Déplier les éléments enfants"></i>';
        } else {
            echo '">';
        }
        echo '<div style="padding-left:' . ($this->depth * 15 + 10) . 'px;">' . str_repeat("-", ($this->depth * 2)) . ' ' . $this->label . '</div>';
        echo '<div>' . count($this->items) . '</div>';
        echo '<div role="group">';
        echo '<a class="button small" title="Editer la catégorie" href="' . $this->getEditUrl() . '"><i class="fa-solid fa-pencil"></i></a>';
        echo '<a class="button alert small" title="Supprimer la catégorie" href="' . $this->getDeleteUrl() . '&token=' . administrator::getToken() . '" onclick="if (!confirm(\'Supprimer cet élément ?\')) return false;"><i class="fa-solid fa-folder-minus"></i></a></div>';
        echo '</div>';
        if ($this->hasChildren) {
            echo '<div class="toggle">';
            foreach ($this->children as $child) {
                $child->outputAsList();
            }
            echo '</div>';
        }
        break;
}

    