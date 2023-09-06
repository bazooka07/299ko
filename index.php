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
define('ROOT', './');
define('BASE_PATH', substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT'])));
include_once(ROOT . 'common/common.php');
$administrator = new administrator($core->getConfigVal('adminEmail'), $core->getConfigVal('adminPwd'));
define('IS_ADMIN', $administrator->isLogged());
Template::addGlobal('IS_ADMIN', IS_ADMIN);

$match = $router->match();

if (!is_array($match)) {
    // no route matching
    if (ADMIN_MODE) {
        require_once(ROOT . 'admin/index.php');
        die();
    }
}
if (is_array($match)) {
    $runPlugin->loadControllers();
    list($controller, $action) = explode('#', $match['target']);
    if (method_exists($controller, $action)) {
        $obj = new $controller();
        $response = call_user_func_array(array($obj,$action), $match['params']);
        echo $response->output();
        die();
    } else {
        // unreachable target
        $core->error404();
    }
}

$core->callHook('beforeRunPlugin');
if (!$runPlugin || $runPlugin->getConfigVal('activate') < 1) {
    $core->error404();
} elseif ($runPlugin->getPublicFile()) {
    if (util::getFileExtension($runPlugin->getPublicTemplate()) === 'tpl' && file_exists(THEMES . $core->getConfigVal('theme') . '/layout.tpl')) {
        $layout = new Template(THEMES . $core->getConfigVal('theme') . '/layout.tpl');
        $tpl = new Template($runPlugin->getPublicTemplate());
        include($runPlugin->getPublicFile());
        $layout->set('CONTENT', $tpl->output());
        echo $layout->output();
    } else {
        include($runPlugin->getPublicFile());
        include($runPlugin->getPublicTemplate());
    }
}