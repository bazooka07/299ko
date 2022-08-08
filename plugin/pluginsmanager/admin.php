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

$action = (isset($_GET['action'])) ? urldecode($_GET['action']) : '';

switch ($action) {
    case '':
        $priority = array(
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 5,
            6 => 6,
            7 => 7,
            8 => 8,
            9 => 9,
        );
        $nbPlugins = count($pluginsManager->getPlugins());
        break;
    case 'save':
        if ($administrator->isAuthorized()) {
            foreach ($pluginsManager->getPlugins() as $k => $v) {
                if (isset($_POST['activate'][$v->getName()])) {
                    if (!$v->isInstalled())
                        $pluginsManager->installPlugin($v->getName(), true);
                    else
                        $v->setConfigVal('activate', 1);
                } else
                    $v->setConfigVal('activate', 0);
                if ($v->isInstalled()) {
                    $v->setConfigVal('priority', intval($_POST['priority'][$v->getName()]));
                    if (!$pluginsManager->savePluginConfig($v)) {
                        show::msg("Une erreur est survenue", 'error');
                    } else {
                        show::msg("Les modifications ont été enregistrées", 'success');
                    }
                }
            }
        }
        header('location:index.php?p=pluginsmanager');
        die();
        break;
    case 'maintenance':
        if ($administrator->isAuthorized())
            $pluginsManager->installPlugin($_GET['plugin'], true);
        header('location:index.php?p=pluginsmanager');
        die();
        break;
}