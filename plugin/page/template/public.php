<?php
defined('ROOT') OR exit('No direct script access allowed');
include_once(THEMES . $core->getConfigVal('theme') . '/header.php');
if ($page->isUnlocked($pageItem)) {
    ?>
    <section>
        <?php
        if ($pageItem->getFile())
            include_once(THEMES . $core->getConfigVal('theme') . '/' . $pageItem->getFile());
        else {
            if ($pluginsManager->isActivePlugin('galerie') && galerie::searchByfileName($pageItem->getImg()))
                echo '<header><img class="featured" src="' . UPLOAD . 'galerie/' . $pageItem->getImg() . '" alt="' . $pageItem->getName() . '" /></header>';
            echo $pageItem->getContent();
        }
        ?>
    </section>
    <?php
} else {
    ?>
    <section>
        <header>
            <div class="item-head">
                <p>Cette page est protégée par un mot de passe.</p>
            </div>
        </header>
        <form method="post" action="">
            <input type="hidden" name="unlock" value="<?php echo $pageItem->getId(); ?>" />
            <p>
                <label>Mot de passe</label><br>
                <input style="display:none;" type="text" name="_password" value="" />
                <input required="required" type="password" name="password" value="" />
            </p>
            <p>
                <input type="submit" value="Envoyer" />
            </p>
        </form>
    </section>
    <?php
}
include_once(THEMES . $core->getConfigVal('theme') . '/footer.php');
