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
                <div><?php echo lang::get($this->getPrefix() . 'name'); ?></div>
                <div><?php echo lang::get($this->getPrefix() . 'itemsNumber'); ?></div>
                <div><?php echo lang::get($this->getPrefix() . 'actions'); ?></div>
            </div>
            <?php
            if (empty($this->imbricatedCategories)) {
                echo '<div class="list-item-list">' . lang::get($this->getPrefix() . 'none') .'</div>';
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
<h4 id="head-add-cat"><?php echo lang::get($this->getPrefix() . 'addCategory'); ?></h4>
                    <?php show::tokenField(); ?> 
                    <div class="input-field">
                        <label class="active" for="category-add-label"><?php echo lang::get($this->getPrefix() . 'categoryName'); ?></label>
                        <input type="text" name="category-add-label" id="category-add-label" required/>
                        <label for="categoy-add-parentId"><?php echo lang::get($this->getPrefix() . 'categoryParent'); ?></label>
                        <select name="category-add-parentId" id="category-add-parentId">
                            <option value="0"><?php echo lang::get($this->getPrefix() . 'none'); ?></option>
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
                    <button class="btn" type="submit" id="list-item-add-btn"><?php echo lang::get($this->getPrefix() . 'addCategory'); ?></button>
                </form>
            </div>
        </footer>
        
        <?php
        break;
    case 'sub':
        // Categories
        echo '<div class="list-item-list ';
        if ($this->hasChildren) {
            echo 'hasChildren"><i style="left:' . ($this->depth * 15 + 5 ) . 'px;" class="fa-solid fa-chevron-up list-item-toggle" title="'.lang::get($this->getPrefix() . 'collapseExpandChildren').'"></i>';
        } else {
            echo '">';
        }
        echo '<div style="padding-left:' . ($this->depth * 15 + 10) . 'px;">' . str_repeat("-", ($this->depth * 2)) . ' ' . $this->label . '</div>';
        echo '<div>' . count($this->items) . '</div>';
        echo '<div role="group">';
        echo '<a class="button small" title="'.lang::get($this->getPrefix() . 'editCategory').'" href="' . $this->getEditUrl() . '"><i class="fa-solid fa-pencil"></i></a>';
        echo '<a class="button alert small" title="'.lang::get($this->getPrefix() . 'deleteCategory').'" href="' . $this->getDeleteUrl() . '&token=' . administrator::getToken() . '" onclick="if (!confirm(\''.lang::get('confirm.deleteItem').' \')) return false;"><i class="fa-solid fa-folder-minus"></i></a></div>';
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

    