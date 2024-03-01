<?php
defined('ROOT') OR exit('No direct script access allowed');
include_once(ROOT . 'admin/header.php');
?>

<form id="configForm" method="post" action=".?p=configmanager&action=save" autocomplete="off">
    <?php show::tokenField(); ?>
    <section>
        <header>Paramètres du site</header>
        <p>
            <input <?php if ($core->getConfigVal('hideTitles')) { ?>checked<?php } ?> type="checkbox" name="hideTitles" id="hideTitles" /> <label for="hideTitles">Masquer le titre des pages</label>
        </p>
        <p>
            <label>Plugin par défaut (public)</label><br>
            <select name="defaultPlugin">
                <?php
                foreach ($pluginsManager->getPlugins() as $plugin)
                    if ($plugin->getAdminFile() && $plugin->getConfigVal('activate') && ($plugin->getPublicFile() || $plugin->getIsCallableOnPublic())) {
                        ?>
                        <option <?php if ($plugin->getIsDefaultPlugin()) { ?>selected<?php } ?> value="<?php echo $plugin->getName(); ?>"><?php echo $plugin->getInfoVal('name'); ?></option>
                    <?php } ?>
            </select>
        </p>
        <p>
            <label>Plugin par défaut (admin)</label><br>
            <select name="defaultAdminPlugin">
                <?php
                foreach ($pluginsManager->getPlugins() as $k => $v)
                    if ($v->getConfigVal('activate') && $v->getAdminFile()) {
                        ?>
                        <option <?php if ($v->getName() == $v->getIsDefaultAdminPlugin()) { ?>selected<?php } ?> value="<?php echo $v->getName(); ?>"><?php echo $v->getInfoVal('name'); ?></option>
                    <?php } ?>
            </select>
        </p>
        <p>
            <label for="siteName">Nom du site</label><br>
            <input type="text" name="siteName" id="siteName" value="<?php echo $core->getConfigVal('siteName'); ?>" required />
        </p>
        <p>
            <label for="siteDesc">Description du site</label><br>
            <input type="text" name="siteDesc" value="<?php echo $core->getConfigVal('siteDesc'); ?>"/>
        </p>
        <p>
            <label for="siteLang">Langue du site</label><br>
            <select name="siteLang">
                <?php
                foreach (lang::$availablesLocales as $k => $v) { ?>
                <option <?php if ($k == lang::getLocale()) { ?>selected<?php } ?> value="<?php echo $k; ?>"><?php echo $v; ?></option>
                <?php } ?>
            </select>
        </p>
        <p>
            <label>Thème</label><br>
            <select name="theme">
                <?php foreach ($core->getThemes() as $k => $v) { ?>
                    <option <?php if ($k == $core->getConfigVal('theme')) { ?>selected<?php } ?> value="<?php echo $k; ?>"><?php echo $v['name']; ?></option>
                <?php } ?>
            </select>
        </p>
    </section>
    <section>
        <header>Administrateur</header>
        <p>
            <label>Email admin</label><br>
            <input type="email" name="adminEmail" value="<?php echo $core->getConfigVal('adminEmail'); ?>" />
        </p>
        <p>
            <label>Mot de passe admin</label><br>
            <input type="password" name="adminPwd" value="" autocomplete="off" style="display: none;" />
            <input type="password" name="_adminPwd" value="" autocomplete="new-password" />
        </p>
        <p>
            <label>Confirmation</label><br>
            <input type="password" name="_adminPwd2" value="" autocomplete="off" />
        </p>
    </section>
    <section>
        <header>Configuration avancée</header>
        <p>
            <label id='delCacheDesc'>Supprimer le cache du site peut être utile si vous n'arrivez pas à mettre à jour 299Ko :</label><br>
            <a aria-describedby="delCacheDesc" class="button" href=".?p=configmanager&action=delcache&token=<?php echo administrator::getToken() ?>">Supprimer le cache</a>
        </p>
        <p>
            <input <?php if ($core->getConfigVal('debug')) { ?>checked<?php } ?> type="checkbox" name="debug" /> <label for="debug">Mode débogage</label> 
        </p>
        <p>
            <label>URL du site (sans slash final)</label><br>
            <input type="text" name="siteUrl" value="<?php echo $core->getConfigVal('siteUrl'); ?>" />
        </p>
        <p>
            <label>.htaccess</label><br>
            <textarea id="htaccess" name="htaccess"><?php echo $core->getHtaccess(); ?></textarea>
        </p>
    </section>
    <p>

        <button type="submit" class="button success radius">Enregistrer</button></p>
</form>

<?php include_once(ROOT . 'admin/footer.php'); ?>