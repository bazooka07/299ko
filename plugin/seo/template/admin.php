<?php
defined('ROOT') OR exit('No direct script access allowed');
include_once(ROOT . 'admin/header.php');
?>

<form method="post" action="index.php?p=seo&action=save">
    <?php
    show::adminTokenField();
    $position = $runPlugin->getConfigVal('position');
    ?>
    <section>
        <header>Affichage</header>
        <p>
            <label for="position">Position du menu SEO</label><br>
            <select name="position" id="position">
                <option value="menu" <?php if ($position == 'menu') echo "selected"; ?>>Menu de navigation</option>
                <option value="footer" <?php if ($position == 'footer') echo "selected"; ?>>Haut de pied de page</option>
                <option value="endfooter" <?php if ($position == 'endfooter') echo 'selected'; ?>>Bas de pied de page</option>
                <option value="float" <?php if ($position == 'float') echo 'selected'; ?>>Flottant</option>
            </select>
        </p>
    </section>
    <section>
        <header>Google</header>
        <p>
            <label for="trackingId">Identifiant de suivi Analytics</label><br>
            <input type="text" name="trackingId" id="trackingId" value="<?php echo $runPlugin->getConfigVal('trackingId'); ?>" />
        </p>
        <p>
            <label for="wt">Meta google site verification</label><br>
            <input type="text" name="wt" id="wt" value="<?php echo $runPlugin->getConfigVal('wt'); ?>" />
        </p>
    </section>
    <section>
        <header>Liens sur les réseaux sociaux</header>
        <?php $social = seoGetSocialVars();
        foreach ($social as $k => $v) {
            ?>
            <p>
                <label for="<?php echo $v; ?>"><?php echo $k; ?></label><br>
                <input placeholder="" type="text" name="<?php echo $v; ?>" id="<?php echo $v; ?>" value="<?php echo $runPlugin->getConfigVal($v); ?>" />
            </p>
<?php } ?>
        <p>
            <button type="submit" class="button">Enregistrer</button>
        </p>
    </section>
</form>

<?php
include_once(ROOT . 'admin/footer.php');
