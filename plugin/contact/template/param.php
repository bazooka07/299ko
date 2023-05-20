<?php defined('ROOT') OR exit('No direct script access allowed'); ?>

<form method="post" action="index.php?p=contact&action=save&fromparam=1">
    <?php show::adminTokenField(); ?>
    <p>
        <label for="copy">Destinataire en copie</label><br>
        <input type="email" name="copy" id="copy" value="<?php echo $runPlugin->getConfigVal('copy'); ?>" />
    </p>
    <p>
        <label for="label">Titre de page</label><br>
        <input type="text" name="label" id="label" value="<?php echo $runPlugin->getConfigVal('label'); ?>" required />
    </p>
    <p>
        <label for="acceptation">Texte d'acceptation avant envoi du formulaire</label><br>
        <textarea name="acceptation" id="acceptation"><?php echo $runPlugin->getConfigVal('acceptation'); ?></textarea>
    </p>
    <p><button type="submit" class="button">Enregistrer</button></p>
</form>