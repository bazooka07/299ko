<?php
defined('ROOT') OR exit('No direct script access allowed');
include_once(ROOT . 'admin/header.php');
?>

<form method="post" action="index.php?p=seo&action=save">
    <?php show::adminTokenField();
    $position = $runPlugin->getConfigVal('position');
    ?>
    <h3>Affichage</h3>
    <p>
        <label>Position du menu SEO</label><br>
        <select name="position">
            <option value="menu" <?php if ($position == 'menu') echo "selected"; ?>>Menu de navigation</option>
            <option value="footer" <?php if ($position == 'footer') echo "selected"; ?>>Haut de pied de page</option>
            <option value="endfooter" <?php if ($position == 'endfooter') echo 'selected'; ?>>Bas de pied de page</option>
            <option value="float" <?php if ($position == 'float') echo 'selected'; ?>>Flottant</option>
        </select>
    </p>
    <h3>Google</h3>
    <p>
        <label>Identifiant de suivi Analytics</label><br>
        <input type="text" name="trackingId" value="<?php echo $runPlugin->getConfigVal('trackingId'); ?>" />
    </p>
    <p>
        <label>Meta google site verification</label><br>
        <input type="text" name="wt" value="<?php echo $runPlugin->getConfigVal('wt'); ?>" />
    </p>
    <h3>Liens sur les réseaux sociaux</h3>
    <?php $social = seoGetSocialVars();foreach ($social as $k => $v) { ?>
    <p>
        <label><?php echo $k; ?></label><br>
        <input placeholder="" type="text" name="<?php echo $v; ?>" value="<?php echo $runPlugin->getConfigVal($v); ?>" />
    </p>
    <?php } ?>
    <p>
        <button type="submit" class="button">Enregistrer</button>
    </p>
</form>

<?php include_once(ROOT . 'admin/footer.php');