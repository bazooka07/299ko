<?php
/**
 * @copyright (C) 2024, 299Ko, based on code (2010-2021) 99ko https://github.com/99kocms/
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
include_once(ROOT . 'common/config.php');
include_once(ROOT . 'common/common.php');
if (file_exists(DATA . 'config.json'))
    die('Un fichier de configuration existe déjà !');
$core = core::getInstance();
$administrator = new administrator();
$pluginsManager = pluginsManager::getInstance();
$url = core::getInstance()->makeSiteUrl() . '/install.php';

// ----------------- Begin tests
// Test PHP Version
$minPHPVersion = 7.4;
$errorPHP = !((float) substr(phpversion(), 0, 3) >= $minPHPVersion);

// Test mod_rewrite
if (function_exists('apache_get_modules')) {
    // PHP is installed as an Apache module
    $errorRewrite = !in_array('mod_rewrite', apache_get_modules()) ? true : false;
} else {
    // PHP is installed as a CGI
    $errorRewrite = 'CGI';
}

// Test writable
if (!is_dir(DATA)) {
    @mkdir(DATA);
}
$errorDataWrite = !is_writable(DATA);

$availablesLocales = Lang::$availablesLocales;

if (count($_POST) > 0) {
	if ($core->install()) {
		$plugins = $pluginsManager->getPlugins();
		if ($plugins != false) {
			foreach ($plugins as $plugin) {
				if ($plugin->getLibFile()) {
					include_once($plugin->getLibFile());
					$plugin->loadLangFile();
					if (!$plugin->isInstalled())
						$pluginsManager->installPlugin($plugin->getName(), true);
					$plugin->setConfigVal('activate', '1');
					$pluginsManager->savePluginConfig($plugin);
				}
			}
		}
	}
	include(DATA . 'key.php');
    $adminPwd = UsersManager::encrypt($_POST['adminPwd']);
    $adminEmail = $_POST['adminEmail'];
    $config = array(
        'siteName' => "SiteName",
        'siteDesc' => "Description",
        'siteUrl' => $core->makeSiteUrl(),
        'theme' => 'default',
        'hideTitles' => '0',
        'defaultPlugin' => 'page',
        'debug' => '0',
        'defaultAdminPlugin' => 'page',
        'lang' => $_POST['lang'],
    );
    if (!@file_put_contents(DATA . 'config.json', json_encode($config)) || !@chmod(DATA . 'config.json', 0600)) {
        show::msg(Lang::get('install-problem-during-install'), 'error');
    } else {
        $_SESSION['installOk'] = true;
        $user = new User();
        $user->email = $adminEmail;
        $user->pwd = $adminPwd;
        $user->save();
        show::msg(Lang::get('install-successfull'), 'success');
        header('location:admin/');
        die();
    }
}
?>

<!doctype html>
<html lang="fr">
    <head>
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=5">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>299ko - <?php echo Lang::get('install-installation'); ?></title>	
        <link rel="stylesheet" href="admin/styles.css" media="all">
        <link rel="stylesheet" href="<?php echo FONTICON; ?>" />
    </head>

    <body class="login">
        <div id="alert-msg">
            <?php show::displayMsg(); ?>
        </div>
        <section id="install">
            <header>
                <h1 class="text-center"><?php echo Lang::get('install-installation'); ?></h1>
            </header>
            <?php
            if ($errorPHP) {
                echo '<div class="msg error">';
                echo Lang::get('install-php-version-error', (float) substr(phpversion(), 0, 3), $minPHPVersion);
                echo '</div>';
            } else {
                echo '<div class="msg success">';
                echo Lang::get('install-php-version-ok', $minPHPVersion);
                echo '</div>';
            }

            if ($errorRewrite === 'CGI') {
                echo '<div class="msg warning">';
                echo Lang::get('install-php-rewrite-cgi');
                echo '</div>';
            } elseif ($errorRewrite) {
                echo '<div class="msg error">';
                echo Lang::get('install-php-rewrite-error');
                echo '</div>';
            } else {
                echo '<div class="msg success">';
                echo Lang::get('install-php-rewrite-ok');
                echo '</div>';
            }

            if ($errorDataWrite) {
                echo '<div class="msg error">';
                echo Lang::get('install-php-data-write-error');
                echo '</div>';
            } else {
                echo '<div class="msg success">';
                echo Lang::get('install-php-data-write-ok');
                echo '</div>';
            }
            if ($errorDataWrite || $errorPHP || $errorRewrite === true) {
                echo Lang::get('install-please-check-errors');
            } else {
                ?>
                <form method="post" action="">   
                    echo '<h3>'.lang::get('install-please-fill-fields').'</h3>';
                    ?>          
                    <p><label for="lang-select"><?php echo Lang::get('install-lang-choice'); ?></label>
                    <select name="lang" id="lang-select" onchange="langChange()">
                        <?php
                        foreach (Lang::$availablesLocales as $k => $v) {
                            if (Lang::getLocale() === $k) {
                                echo '<option value="' . $k . '" selected>' . $v . '</option>';
                            } else {
                                echo '<option value="' . $k . '">' . $v . '</option>';
                            }
                        }
                        ?>
                    </select>
                    </p>
                    <p>
                        <label for="adminEmail"><?php echo Lang::get('email'); ?></label><br>
                        <input type="email" name="adminEmail" required="required">
                    </p>
                    <p>
                        <label for="adminPwd"><?php echo Lang::get('password'); ?></label><br>
                        <input type="password" name="adminPwd" id="adminPwd" required="required">
                    </p>
                    <p>
                        <a id="showPassword" href="javascript:showPassword()" class="button success"><?php echo Lang::get('install-show-password'); ?></a>
                        <button type="submit" class="button success"><?php echo Lang::get('submit'); ?></button>
                    </p>
                    </form>
                    <footer><a target="_blank" href="https://github.com/299ko/"><?php echo Lang::get('site-just-using', VERSION); ?></a>
                    </footer>
                
                <?php
            }
            ?>
        </section>
        <script type="text/javascript">
            function showPassword() {
                document.getElementById("adminPwd").setAttribute("type", "text");
                document.getElementById("showPassword").style.display = 'none';
            }
            function langChange() {
                window.location.href = '<?php echo $url; ?>?lang=' + document.getElementById("lang-select").value;
            }
        </script>
    </body>
</html>