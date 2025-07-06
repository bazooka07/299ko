<?php

/**
 * @copyright (C) 2025, 299Ko
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 * @author Maxence Cauderlier <mx.koder@gmail.com>
 *
 * @package 299Ko https://github.com/299Ko/299ko
 */
defined('ROOT') or exit('Access denied!');

/**
 * The CacheManager class provides advanced cache management features.
 */
class CacheManager
{
    protected Cache $cache;
    protected array $config;

    public function __construct() {
        $this->cache = new Cache();
        $this->config = core::getInstance()->getconfig();
    }

    /**
     * Get cache statistics
     * 
     * @return array
     */
    public function getStats(): array {
        $cacheDir = CACHE;
        $files = glob($cacheDir . '*.cache.php');
        $totalSize = 0;
        $fileCount = count($files);
        
        foreach ($files as $file) {
            $totalSize += filesize($file);
        }

        return [
            'files_count' => $fileCount,
            'total_size' => $totalSize,
            'total_size_formatted' => $this->formatBytes($totalSize)
        ];
    }

    /**
     * Clear all cache files
     * 
     * @return bool
     */
    public function clearCache(): bool {
        $cacheDir = CACHE;
        $files = glob($cacheDir . '*.cache.php');
        $success = true;
        
        foreach ($files as $file) {
            if (!unlink($file)) {
                $success = false;
            }
        }



        return $success;
    }

    /**
     * Add lazy loading to images and iframes
     * 
     * @param string $content
     * @return string
     */
    public function addLazyLoading(string $content): string {
        // Protect script and style content
        $scripts = [];
        $content = preg_replace_callback('/<(script|style)[^>]*>.*?<\/\1>/is', function($matches) use (&$scripts) {
            $key = '%%%PROTECTED_SCRIPT_' . count($scripts) . '%%%';
            $scripts[$key] = $matches[0];
            return $key;
        }, $content);

        // Add lazy loading to images
        $content = preg_replace_callback('/<img([^>]*)>/i', function($matches) {
            $attributes = $matches[1];
            
            // Check if loading="lazy" already exists
            if (preg_match('/\bloading\s*=\s*["\']lazy["\']/i', $attributes)) {
                return $matches[0]; // Don't change if already present
            }
            
            // Check if alt exists, otherwise add it
            if (!preg_match('/\balt\s*=/i', $attributes)) {
                $attributes .= ' alt=""';
            }
            
            // Add loading="lazy"
            $attributes .= ' loading="lazy"';
            
            return '<img' . $attributes . '>';
        }, $content);

        // Add lazy loading to iframes
        $content = preg_replace_callback('/<iframe([^>]*)>/i', function($matches) {
            $attributes = $matches[1];
            
            // Check if loading="lazy" already exists
            if (preg_match('/\bloading\s*=\s*["\']lazy["\']/i', $attributes)) {
                return $matches[0]; // Don't change if already present
            }
            
            // Add loading="lazy"
            $attributes .= ' loading="lazy"';
            
            return '<iframe' . $attributes . '>';
        }, $content);

        // Restore protected scripts/styles
        if (!empty($scripts)) {
            $content = str_replace(array_keys($scripts), array_values($scripts), $content);
        }

        return $content;
    }

    /**
     * Minify HTML content
     * 
     * @param string $content
     * @return string
     */
    public function minifyHtml(string $content): string {
        // Ensure proper UTF-8 encoding
        if (!mb_check_encoding($content, 'UTF-8')) {
            $content = mb_convert_encoding($content, 'UTF-8', 'auto');
        }
        
        // Protect script and style content first
        $scripts = [];
        $content = preg_replace_callback('/<(script|style)[^>]*>.*?<\/\1>/is', function($matches) use (&$scripts) {
            $key = '%%%PROTECTED_SCRIPT_' . count($scripts) . '%%%';
            $scripts[$key] = $matches[0];
            return $key;
        }, $content);

        // Remove HTML comments (except conditional IE comments)
        $content = preg_replace('/<!--(?!\s*\[if [^]]+]|<!|>)(?:(?!-->).)*-->/s', '', $content);

        // Remove unnecessary whitespace between tags, but preserve some spacing
        $content = preg_replace('/>\s*\n\s*</', '><', $content);
        $content = preg_replace('/>\s{2,}</', '> <', $content);

        // Remove multiple spaces, but preserve single spaces
        $content = preg_replace('/[ \t]+/', ' ', $content);

        // Remove leading/trailing whitespace from lines
        $content = preg_replace('/^[ \t]+/m', '', $content);
        $content = preg_replace('/[ \t]+$/m', '', $content);

        // Clean start and end
        $content = trim($content);

        // Restore protected scripts/styles
        if (!empty($scripts)) {
            $content = str_replace(array_keys($scripts), array_values($scripts), $content);
        }

        return $content;
    }

    /**
     * Process content with cache and minification
     * 
     * @param string $key
     * @param string $content
     * @param int $duration
     * @param array $tags
     * @param array $files
     * @return string
     */
    public function processContent(string $key, string $content, int $duration = 3600, array $tags = [], array $files = []): string {
        // Check if cache is enabled
        if ($this->isCacheEnabled()) {
            // Utiliser la durée passée en paramètre, sinon la valeur de config, sinon 3600
            $finalDuration = $duration > 0 ? $duration : ($this->getCacheDuration() ?: 3600);
            
            // Vérifier si le contenu existe déjà en cache
            $cachedContent = $this->cache->get($key);
            if ($cachedContent !== false) {
                return $cachedContent;
            }
        }

        // Check if lazy loading is enabled
        if ($this->isLazyLoadingEnabled()) {
            $content = $this->addLazyLoading($content);
        }

        // Check if minification is enabled
        if ($this->isMinifyEnabled()) {
            $content = $this->minifyHtml($content);
        }

        // Store in cache if enabled
        if ($this->isCacheEnabled()) {
            $this->cache->set($key, $content, $finalDuration, $tags, $files);
        }

        return $content;
    }

    /**
     * Check if cache is enabled
     * 
     * @return bool
     */
    public function isCacheEnabled(): bool {
        // Toujours récupérer la configuration actuelle de core
        return core::getInstance()->getConfigVal('cache_enabled') ?: true;
    }

    /**
     * Check if minification is enabled
     * 
     * @return bool
     */
    public function isMinifyEnabled(): bool {
        // Toujours récupérer la configuration actuelle de core
        return core::getInstance()->getConfigVal('cache_minify') ?: false;
    }

    /**
     * Check if lazy loading is enabled
     * 
     * @return bool
     */
    public function isLazyLoadingEnabled(): bool {
        // Toujours récupérer la configuration actuelle de core
        return core::getInstance()->getConfigVal('cache_lazy_loading') ?: false;
    }

    /**
     * Get cache duration
     * 
     * @return int
     */
    public function getCacheDuration(): int {
        // Toujours récupérer la configuration actuelle de core
        return core::getInstance()->getConfigVal('cache_duration') ?: 3600;
    }

    /**
     * Format bytes to human readable format
     * 
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    protected function formatBytes(int $bytes, int $precision = 2): string {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }


} 