<?php

/**
 * @copyright (C) 2024, 299Ko
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 * @author Maxence Cauderlier <mx.koder@gmail.com>
 * 
 * @package 299Ko https://github.com/299Ko/299ko
 */
defined('ROOT') or exit('Access denied!');

class ConfigManagerAdminController extends AdminController {
    
    public function home() {
        if (is_dir(ROOT . 'update')) {
            show::msg(lang::get('configmanager-update-dir-found') . '<br/><a class="button" href="' . $this->router->generate('configmanager-manual-update', ['token' => $this->user->token]) . '">' . lang::get('configmanager-update') . '</a>', 'true');
        }

        $response = new AdminResponse();
        $tpl = $response->createPluginTemplate('configmanager', 'config');

        $tpl->set('link', $this->router->generate('configmanager-admin-save'));
        $tpl->set('cacheClearLink', $this->router->generate('configmanager-admin-cache-clear', ['token' => $this->user->token]));
        $tpl->set('cacheStatsLink', $this->router->generate('configmanager-admin-cache-stats', ['token' => $this->user->token]));

        // Get cache statistics
        $cacheManager = new CacheManager();
        $tpl->set('cacheStats', $cacheManager->getStats());

        $response->addTemplate($tpl);
        return $response;
    }

    public function save() {
        if (!$this->user->isAuthorized()) {
            return $this->home();
        }
        if (array_key_exists($_POST['siteLang'], lang::$availablesLocales)) {
            $lang = $_POST['siteLang'];
        } else {
            $lang = lang::getLocale();
        }
        $config = [
            'siteName' => (trim($_POST['siteName']) != '') ? trim($_POST['siteName']) : 'Demo',
            'siteDesc' => (trim($_POST['siteDesc']) != '') ? trim($_POST['siteDesc']) : '',
            'siteLang' => $lang,
            'siteUrl' => (trim($_POST['siteUrl']) != '') ? rtrim(trim($_POST['siteUrl']), '/') : $this->core->getConfigVal('siteUrl'),
            'theme' => $_POST['theme'],
            'defaultPlugin' => $_POST['defaultPlugin'],
            'hideTitles' => (isset($_POST['hideTitles'])) ? true : false,
            'debug' => (isset($_POST['debug'])) ? true : false,
            'defaultAdminPlugin' => $_POST['defaultAdminPlugin'],
            'cache_enabled' => (isset($_POST['cache_enabled'])) ? true : false,
            'cache_duration' => (int)$_POST['cache_duration'],
            'cache_minify' => (isset($_POST['cache_minify'])) ? true : false,
            'cache_lazy_loading' => (isset($_POST['cache_lazy_loading'])) ? true : false
        ];

        if (!$this->core->saveConfig($config, $config)) {
            show::msg(lang::get("core-changes-not-saved"), 'error');
        } else {
            // Invalidate cache if needed
            $this->invalidateCacheIfNeeded($config);
            show::msg(lang::get("core-changes-saved"), 'success');
        }
        $this->core->saveHtaccess($_POST['htaccess']);
        $this->core->redirect($this->router->generate('configmanager-admin'));
    }

    public function deleteInstall($token) {
        if (!$this->user->isAuthorized()) {
            return $this->home();
        }
        $del = unlink(ROOT . 'install.php');
        if ($del) {
            show::msg(lang::get('configmanager-deleted-install'), 'success');
        } else {
            show::msg(lang::get('configmanager-error-deleting-install'), 'error');
        }
        return $this->home();
    }

    public function clearCache($token) {
        if (!$this->user->isAuthorized()) {
            return $this->home();
        }
        $cacheManager = new CacheManager();
        if ($cacheManager->clearCache()) {
            show::msg(lang::get('configmanager-cache-clear-success'), 'success');
        } else {
            show::msg(lang::get('configmanager-cache-clear-error'), 'error');
        }
        return $this->home();
    }

    public function cacheStats($token) {
        if (!$this->user->isAuthorized()) {
            return $this->home();
        }
        $cacheManager = new CacheManager();
        $stats = $cacheManager->getStats();
        show::msg(lang::get('configmanager-cache-stats-updated'), 'success');
        return $this->home();
    }

    /**
     * Invalidate cache when configuration changes
     * 
     * @param array $newConfig
     * @return void
     */
    protected function invalidateCacheIfNeeded(array $newConfig): void
    {
        $oldConfig = $this->core->getconfig();
        
        // If theme has changed
        if (isset($newConfig['theme']) && $newConfig['theme'] !== ($oldConfig['theme'] ?? '')) {
            $this->core->invalidateThemeCache($oldConfig['theme'] ?? '');
            $this->core->invalidateThemeCache($newConfig['theme']);
        }
        
        // Always invalidate all theme caches to ensure consistency
        // This handles the case where cache was created with a different theme than the config
        $this->core->invalidateAllThemeCaches();
        
        // If language has changed
        if (isset($newConfig['siteLang']) && $newConfig['siteLang'] !== ($oldConfig['siteLang'] ?? '')) {
            $this->core->invalidateLanguageCache($oldConfig['siteLang'] ?? '');
            $this->core->invalidateLanguageCache($newConfig['siteLang']);
        }
        
        // If cache settings have changed
        $cacheSettingsChanged = false;
        $cacheSettings = ['cache_enabled', 'cache_duration', 'cache_minify', 'cache_lazy_loading'];
        
        foreach ($cacheSettings as $setting) {
            if (isset($newConfig[$setting]) && $newConfig[$setting] !== ($oldConfig[$setting] ?? false)) {
                $cacheSettingsChanged = true;
                break;
            }
        }
        
        if ($cacheSettingsChanged) {
            // Invalidate all cache because cache parameters have changed
            $this->core->invalidateAllCache();
        }
    }
}