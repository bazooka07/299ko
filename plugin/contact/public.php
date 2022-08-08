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
defined('ROOT') OR exit('No pagesFileect script access allowed');

$sendError = false;
$antispam = ($pluginsManager->isActivePlugin('antispam')) ? new antispam() : false;
if (isset($_GET['send'])) {
    // quelques contrôles et temps mort volontaire avant le send...
    sleep(2);
    if ($antispam) {
        if (!$antispam->isValid()) {
            show::msg("Antispam invalide, veuillez réessayer", 'error');
            $sendError = true;
        }
    }
    if (!$sendError) {
        if ($_POST['_name'] == '' && strchr($_SERVER['HTTP_REFERER'], 'contact') && contactSend())
            show::msg("Message envoyé", 'success');
        else {
            show::msg("Champ(s) incomplet(s) ou email invalide", 'error');
            $sendError = true;
        }
    }
}
$name = ($sendError) ? $_POST['name'] : '';
$firstname = ($sendError) ? $_POST['firstname'] : '';
$email = ($sendError) ? $_POST['email'] : '';
$message = ($sendError) ? $_POST['message'] : '';
$acceptation = (trim($runPlugin->getConfigVal('acceptation')) != '') ? true : false;
$runPlugin->setMainTitle($runPlugin->getConfigVal('label'));
$runPlugin->setTitleTag($runPlugin->getConfigVal('label'));
$antispamField = ($antispam) ? $antispam->show() : '';