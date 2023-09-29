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
defined('ROOT') or exit('No direct script access allowed');

class core
{

    private static $instance = null;
    private $config;
    private $hooks;
    private $themes;
    private $pluginToCall;
    private $js;
    private $css;
    private $locale;

    /**
     * Metas are used by plugins to display metas property or other in <head> HTML
     */
    private $metas = [];

    /**
     * Logger is a resource file
     * @var \resource
     */
    private $logger;

    ## Constructeur

    public function __construct()
    {

        if (!is_dir(DATA))
            mkdir(DATA);
        $this->createLogger();

        // Timezone
        date_default_timezone_set(date_default_timezone_get());
        // Construction du tableau de configuration
        // Exemple : array('siteName' => 'val', 'siteUrl' => 'val2')
        $this->config = util::readJsonFile(DATA . 'config.json', true);
        // Réglage de l'error reporting suivant le paramètre debug
        if ($this->config && $this->config['debug']) {
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
        } else
            error_reporting(E_ERROR | E_PARSE);
        // Liste des thèmes
        $temp = util::scanDir(THEMES);
        foreach ($temp['dir'] as $k => $v) {
            $this->themes[$v] = util::readJsonFile(THEMES . $v . '/infos.json', true);
        }
        // On détermine le plugin que l'on doit executer suivant le mode (public ou admin)
        if (isset($_GET['p'])) {
            define('ISHOMEPAGE', false);
            $this->pluginToCall = $_GET['p'];
        } else {
            $parts = explode('/', trim(router::getInstance()->getCleanURI(), '/'));
            if (ADMIN_MODE) {
                if (isset($parts[1]) && $parts[1] !== '') {
                    define('ISHOMEPAGE', false);
                    $this->pluginToCall = $parts[1];
                } else {
                    $this->pluginToCall = $this->getConfigVal('defaultAdminPlugin');
                    define('ISHOMEPAGE', true);
                }
            } else {
                if (isset($parts[0]) && $parts[0] !== '') {
                    define('ISHOMEPAGE', false);
                    $this->pluginToCall = $parts[0];
                } else {
                    $this->pluginToCall = $this->getConfigVal('defaultPlugin');
                    define('ISHOMEPAGE', true);
                }
            }
        }
        $this->locale = $this->getConfigVal('siteLang');
        if ($this->locale === false) {
            if (isset($_GET['lang'])) {
                $this->locale = $_GET['lang'];
            } else {
                $navLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
                if (file_exists(COMMON . 'langs/' . $navLang . '.ini')) {
                    $this->locale = $navLang;
                } else {
                    $this->locale = 'fr';
                }
            }
        }
        lang::setLocale($this->locale);
        lang::loadLanguageFile(COMMON . 'langs/');
        $this->css[] = FONTICON;
        $this->css[] = FANCYCSS;
        $this->js[] = FANCYJS;
    }

    /**
     * Return Core Instance
     * 
     * @return \self
     */
    public static function getInstance()
    {
        if (is_null(self::$instance))
            self::$instance = new core();
        return self::$instance;
    }

    ## Retourne la liste des thèmes

    public function getThemes()
    {
        return $this->themes;
    }

    ## Retourne la configuration complète

    public function getconfig()
    {
        return $this->config;
    }

    ## Retourne une valeur de configuration

    public function getConfigVal($k)
    {
        if (isset($this->config[$k]))
            return $this->config[$k];
        else
            return false;
    }

    /**
     * Set up a config val.
     * This setting will not be saved
     * 
     * @param string $key
     * @param string $value
     */
    public function setConfigVal($key, $value)
    {
        $this->config[$key] = $value;
    }

    ## Retourne les infos du thème ciblé

    public function getThemeInfo($k)
    {
        if (isset($this->themes[$this->getConfigVal('theme')]))
            return $this->themes[$this->getConfigVal('theme')][$k];
        else
            return false;
    }

    ## Retourne l'identifiant du plugin solicité

    public function getPluginToCall()
    {
        return $this->pluginToCall;
    }

    ## Retourne le tableau de ressources JS de base

    public function getJs()
    {
        return $this->js;
    }

    ## Retourne le tableau de ressources CSS de base

    public function getCss()
    {
        return $this->css;
    }

    public function addMeta(string $meta)
    {
        $this->metas[] = $meta;
    }

    public function getMetas()
    {
        return $this->metas;
    }

    ## Détermine si 299ko est installé

    public function isInstalled()
    {
        if (!file_exists(DATA . 'config.json'))
            return false;
        else
            return true;
    }

    ## Génère l'URL du site

    public function makeSiteUrl()
    {
        $siteUrl = str_replace(array('install.php', '/admin', '/index.php'), array('', '', ''), $_SERVER['SCRIPT_NAME']);
        $isSecure = false;
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
            $isSecure = true;
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on')
            $isSecure = true;
        $REQUEST_PROTOCOL = $isSecure ? 'https' : 'http';
        $siteUrl = $REQUEST_PROTOCOL . '://' . $_SERVER['HTTP_HOST'] . $siteUrl;
        $pos = mb_strlen($siteUrl) - 1;
        if ($siteUrl[$pos] == '/')
            $siteUrl = substr($siteUrl, 0, -1);
        return $siteUrl;
    }

    ## Alimente le tableau des hooks

    public function addHook($name, $function)
    {
        $this->hooks[$name][] = $function;
    }

    /**
     * Permet d'appeler un hook
     * Si un paramètre est fourni, celui-ci sera passé de fonction en fonction Hook de filtre).
     * Sinon, la valeur de retour sera concaténé à chaque fonction (Hook d'action).
     * 
     * @param   string  Nom du hook
     * @param   mixed   Paramètres
     * @return  mixed
     */
    public function callHook($name, $params = null)
    {
        if ($params === null) {
            // Action
            $return = '';
            if (isset($this->hooks[$name])) {
                foreach ($this->hooks[$name] as $function) {
                    $return .= call_user_func($function);
                }
            }
            return $return;
        }
        // Filter
        if (isset($this->hooks[$name])) {
            foreach ($this->hooks[$name] as $function) {
                $params = call_user_func($function, $params);
            }
        }
        return $params;
    }

    ## Detecte le mode de l'administration

    public function detectAdminMode()
    {
        $mode = '';
        if (isset($_GET['action']) && $_GET['action'] == 'login')
            return 'login';
        elseif (isset($_GET['action']) && $_GET['action'] == 'logout')
            return 'logout';
        elseif (isset($_GET['action']) && $_GET['action'] == 'lostpwd')
            return 'lostpwd';
        elseif (!isset($_GET['p']))
            return 'plugin';
        elseif (isset($_GET['p']))
            return 'plugin';
    }

    public function detectAjaxRequest()
    {
        $ajaxGet = $_GET['request'] ?? false;
        $ajaxPost = $_POST['request'] ?? false;
        return ($ajaxGet === 'ajax' || $ajaxPost === 'ajax');
    }

    ## Renvoi une page 404

    public function error404($mainTitle = '404')
    {
        $core = $this;
        global $runPlugin;
        if ($runPlugin)
            $runPlugin->setMainTitle('Error 404 :(');
        header("HTTP/1.1 404 Not Found");
        header("Status: 404 Not Found");
        include_once(THEMES . $this->getConfigVal('theme') . '/header.php');
        include_once(THEMES . $this->getConfigVal('theme') . '/404.php');
        include_once(THEMES . $this->getConfigVal('theme') . '/footer.php');
        die();
    }

    ## Update le fichier de configuration

    public function saveConfig($val, $append = array())
    {
        $config = util::readJsonFile(DATA . 'config.json', true);
        $config = array_merge($config, $append);
        foreach ($config as $k => $v)
            if (isset($val[$k])) {
                $config[$k] = $val[$k];
            }
        if (util::writeJsonFile(DATA . 'config.json', $config)) {
            $this->config = util::readJsonFile(DATA . 'config.json', true);
            return true;
        } else
            return false;
    }

    ## Installation de 299ko

    public function install()
    {
        $install = true;
        @chmod(ROOT . '.htaccess', 0604);
        if (!is_dir(DATA) && (!@mkdir(DATA) || !@chmod(DATA, 0755)))
            $install = false;
        if ($install) {
            if (!file_exists(DATA . '.htaccess')) {
                if (!@file_put_contents(DATA . '.htaccess', "Require all denied", 0604))
                    $install = false;
            }
            if (!is_dir(DATA_PLUGIN) && (!@mkdir(DATA_PLUGIN) || !@chmod(DATA_PLUGIN, 0755)))
                $install = false;
            if (!is_dir(UPLOAD) && (!@mkdir(UPLOAD) || !@chmod(UPLOAD, 0755)))
                $install = false;
            if (!file_exists(UPLOAD . '.htaccess')) {
                if (!@file_put_contents(UPLOAD . '.htaccess', "Require all granted", 0604))
                    $install = false;
            }
            if (!file_exists(__FILE__) || !@chmod(__FILE__, 0644))
                $install = false;
            $key = uniqid(true);
            if (
                !file_exists(DATA . 'key.php') && !@file_put_contents(DATA
                    . 'key.php', "<?php\ndefined('ROOT') OR exit"
                    . "('No direct script access allowed');"
                    . "\ndefine('KEY', '$key'); ?>", 0604)
            )
                $install = false;
        }
        return $install;
    }

    ## Retourne le contenu du fichier htaccess

    public function getHtaccess()
    {
        return @file_get_contents(ROOT . '.htaccess');
    }

    ## Update le contenu du fichier htaccess

    public function saveHtaccess($content)
    {
        $content = str_replace("&amp;", "&", $content);
        @file_put_contents(ROOT . '.htaccess', $content);
    }

    protected function createLogger()
    {
        if (is_dir(DATA)) {
            $this->logger = fopen(DATA . 'logs.txt', 'a+');
        }

    }

    /**
     * Add a log into log file
     * 
     * @param string Message
     * @param string Severity
     * Can be 'INFO', 'DEBUG', 'WARNING', 'ERROR'
     */
    public function log($message, $severity = 'INFO')
    {
        $date = date('Y-m-d H:i:s');
        if ($this->logger) {
            fwrite($this->logger, "[$date] [$severity] : $message\n");
        }
    }

    function __destruct()
    {
        if ($this->logger) {
            fclose($this->logger);
        }
    }
}

/**
 * Add a log into log file
 * @see \core->log()
 * 
 * @param string Message
 * @param string Severity
 * Can be 'INFO', 'DEBUG', 'WARNING', 'ERROR'
 */
function logg($message, $severity = 'INFO')
{
    core::getInstance()->log($message, $severity);
}