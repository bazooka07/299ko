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

$action = (isset($_GET['action'])) ? $_GET['action'] : '';
if ($action === 'saveconf') {
    if ($administrator->isAuthorized()) {
        if ($_POST['captcha'] === 'useRecaptcha') {
            // Use ReCaptcha
            if (!isset($_POST['recaptchaPublicKey']) || !isset($_POST['recaptchaSecretKey']) ||
                    trim($_POST['recaptchaPublicKey']) == '' || trim($_POST['recaptchaSecretKey']) == '') {
                // Empty keys
                show::msg("Les clés de ReCaptcha ne peuvent pas être vides", 'error');
                header('location:index.php?p=antispam');
                die();
            }
            // Save ReCaptcha
            $runPlugin->setConfigVal('recaptchaPublicKey', trim($_POST['recaptchaPublicKey']));
            $runPlugin->setConfigVal('recaptchaSecretKey', trim($_POST['recaptchaSecretKey']));
        }
        // Save Type
        $runPlugin->setConfigVal('type', trim($_POST['captcha']));
        $pluginsManager->savePluginConfig($runPlugin);

        show::msg("Les modifications ont été enregistrées", 'success');

        header('location:index.php?p=antispam');
        die();
    }
}