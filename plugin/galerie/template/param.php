<?php defined('ROOT') OR exit('No direct script access allowed'); ?>

<form method="post" action=".?p=galerie&action=saveconf">
    <?php show::tokenField(); ?>

    <p>
        <input <?php if ($runPlugin->getConfigVal('showTitles')) { ?>checked<?php } ?> type="checkbox" name="showTitles" id="showTitles" />
        <label for="showTitles">Afficher le titre des images</label>
    </p>

    <p>
        <label for="label">Titre de page</label><br>
        <input type="text" name="label" id="label" value="<?php echo $runPlugin->getConfigVal('label'); ?>" />
    </p>
    <p>
        <label for="order">Ordre des images</label><br>
        <select name="order" id="order">
            <option <?php if ($runPlugin->getConfigVal('order') == 'natural') { ?>selected<?php } ?> value="natural">Naturel</option>
            <option <?php if ($runPlugin->getConfigVal('order') == 'byName') { ?>selected<?php } ?> value="byName">Nom</option>
            <option <?php if ($runPlugin->getConfigVal('order') == 'byDate') { ?>selected<?php } ?> value="byDate">Date</option>
        </select>
    </p>
    <p>
        <label for="size">Taille des images</label><br>
        <select name="size" id="size">
            <option <?php if ($runPlugin->getConfigVal('size') == '800') { ?>selected<?php } ?> value="800">Petite</option>
            <option <?php if ($runPlugin->getConfigVal('size') == '1024') { ?>selected<?php } ?> value="1024">Grande</option>
            <option <?php if ($runPlugin->getConfigVal('size') == '1280') { ?>selected<?php } ?> value="1280">Très grande</option>
        </select>
    </p>

    <p>
        <label for="introduction">Introduction</label><br>
        <textarea class="editor" name="introduction" id="introduction"><?php echo $core->callHook('beforeEditEditor', $runPlugin->getConfigVal('introduction')); ?></textarea><br>
        <?php filemanagerDisplayManagerButton(); ?>
    </p>

    <p><button type="submit" class="button">Enregistrer</button></p>
</form>