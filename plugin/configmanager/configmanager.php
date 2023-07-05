<?php

/**
 * @copyright (C) 2022, 299Ko, based on code (2010-2021) 99ko https://github.com/99kocms/
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 * @author Jonathan Coulet <j.coulet@gmail.com>
 * @author Maxence Cauderlier <mx.koder@gmail.com>
 * @author Frédéric Kaplon <frederic.kaplon@me.com>
 * @author Florent Fortat <florent.fortat@maxgun.fr>
 * 
 * @package 299Ko https://github.com/299Ko/299ko
 */
defined('ROOT') OR exit('No direct script access allowed');

require_once PLUGINS . 'configmanager/lib/UpdaterManager.php';

## Fonction d'installation

function configmanagerInstall() {
    
}

## Hooks
## Code relatif au plugin

function configManagerDisplayInstallFile() {
    if (file_exists(ROOT . 'install.php')) {
        echo "<div class='msg warning'>
                <p>Le fichier install.php est toujours présent. Pour plus de sécurité, il est conseillé de le supprimer.<br/>
                Si l'installation de 299ko s'est déroulée correctement, cliquez sur le bouton ci-dessous pour le supprimer</p>
                <div style='text-align:center'><a class='button' href='index.php?p=configmanager&action=del_install&token=" . administrator::getToken() . "'>Supprimer le fichier install</a></div>"
        . "<a href='#' class='msg-button-close'><i class='fa-solid fa-xmark'></i></a></div>";
    }
}

function configManagerCheckNewVersion() {
    $cachedInfos = util::readJsonFile(DATA_PLUGIN . 'configmanager/cache.json');
    if ($cachedInfos !== false) {
        // Cached infos
        $lastVersion = $cachedInfos['lastVersion'];
        if ($lastVersion === VERSION) {
            // No local update, check if cache is fresh
            $lastCheckUpdate = (int) $cachedInfos['lastCheckUpdate'];
            if ($lastCheckUpdate + 86400 < time()) {
                // Expired cache, try to retrieve new version
                $nextVersion = configmanagerGetNewVersion();
            } else {
                // Cache ok, actual version is the lastest
                $nextVersion = false;
            }
        } else {
            // Newer version exist in cache
            $nextVersion = $lastVersion;
        }
    } else {
        // No cache
        $nextVersion = configmanagerGetNewVersion();
    }
    if ($nextVersion) {
        configmanagerDisplayNewVersion($nextVersion);
    }
}

function configmanagerDisplayNewVersion($nextVersion) {
    show::msg("<p>Une nouvelle version est disponible.<br/>
            Cliquez ci-dessous pour mettre à jour votre site en version " . $nextVersion . "</p>
        <p>N'oubliez pas de faire une sauvegarde de votre site avant d'effectuer cette mise à jour.</p>
        <p>Vous pouvez consulter le <a href='https://github.com/299Ko/299ko/blob/master/changelog.md'
                                       target='_blank'>changelog des versions de 299Ko ici</a>.</p>
        <div style='text-align:center'><a class='button alert' href='index.php?p=configmanager&action=update&token=" . administrator::getToken() . "'>Mettre à jour le site</a></div>");
}

function configmanagerGetNewVersion() {
    $updaterManager = new UpdaterManager();
    if ($updaterManager) {
        $nextVersion = $updaterManager->getNextVersion();
    } else {
        $nextVersion = false;
    }
    $file = DATA_PLUGIN . 'configmanager/cache.json';
    $cachedInfos = util::readJsonFile($file);
    if ($cachedInfos === false) {
        $cachedInfos = [];
    }
    $cachedInfos['lastVersion'] = $updaterManager->lastVersion;
    $cachedInfos['lastCheckUpdate'] = time();
    util::writeJsonFile($file, $cachedInfos);
    if ($nextVersion) {
        logg('Nouvelle version trouvée : ' . $nextVersion, 'INFO');
    }
    return $nextVersion;
}
