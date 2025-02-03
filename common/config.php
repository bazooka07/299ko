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

const VERSION = '2.0.0';
const COMMON = ROOT . 'common/';
const DATA = ROOT . 'data/';
const UPLOAD = ROOT . 'data/upload/';
const DATA_PLUGIN = ROOT . 'data/plugin/';
const THEMES = ROOT . 'theme/';
const PLUGINS = ROOT . 'plugin/';
const ADMIN_PATH = ROOT . 'admin/';
const CDN_LIB = 'https://cdnjs.cloudflare.com/ajax/libs/';
const FONTICON = CDN_LIB . 'font-awesome/6.5.1/css/all.min.css';
const FANCYCSS = CDN_LIB . 'fancyapps-ui/4.0.31/fancybox.min.css';
const FANCYJS = CDN_LIB . 'fancyapps-ui/4.0.31/fancybox.umd.min.js';
const MIN_PHP_VERSION = '7.4';

// Test PHP Version
if(version_compare(PHP_VERSION, MIN_PHP_VERSION, '<')) {
	header('Content-Type: text/plain; charset=utf-8');
?>
Minimal version of PHP required: <?= MIN_PHP_VERSION ?>
PHP version detected: <?= PHP_VERSION ?>
<?php
	die();
}

$filename = DATA . 'key.php';
if (file_exists($filename)) {
    include $filename;
}
