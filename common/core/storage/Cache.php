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
 * The Cache class provides methods to manage cache files.
 */

class Cache
{

    protected string $cache_dir;
    protected string $ext;
    protected array $buffer = [];

    protected bool $enabled = true;

    /**
     * Cache constructor.
     *
     * Initializes the cache directory, file extension, and enables or disables caching.
     * Ensures the cache directory exists.
     *
     * @param string $cache_dir The directory path where cache files are stored.
     * @param string $ext The file extension for cache files.
     * @param bool $enabled Whether caching is enabled.
     */
    public function __construct(string $cache_dir = CACHE, string $ext = '.cache.php', bool $enabled = true) {
        $this->cache_dir = rtrim($cache_dir, '/') . '/';
        $this->ext = $ext;
        $this->enabled = $enabled;
        $this->ensureCacheDir();
    }

    /**
     * Stores data in the cache.
     *
     * @param string $key The unique key for the cache item.
     * @param mixed $data The data to be cached.
     * @param int $duration The duration in seconds for which the cache is valid. Default is 3600 seconds.
     * @param array $tags An array of tags associated with the cache item.
     * @param array $files An array of file paths. The cache will be invalidated if any of the files are modified.
     * 
     * @return void
     */
    public function set(string $key, $data, int $duration = 3600, array $tags = [], array $files = []): void {
        if (!$this->enabled)
            return;
        $file = $this->getCacheFile($key);
        $cache_data = [
            'expire' => time() + $duration,
            'content' => serialize($data),
            'tags' => $tags,
            'files' => array_map('filemtime', array_filter($files, 'file_exists'))
        ];
        file_put_contents($file, serialize($cache_data));
    }

    /**
     * Retrieves data from the cache by key.
     *
     * This method checks if caching is enabled, then attempts to retrieve the 
     * cached data associated with the given key. If the cache file does not exist, 
     * or if the cached data has expired or is invalid, it returns false. It also 
     * checks if any associated files have been modified since caching, in which 
     * case the cache is considered invalid and also returns false.
     *
     * @param string $key The unique key for the cache item.
     *
     * @return mixed The cached data associated with the key, or false if the cache 
     * is not found, expired, or invalid.
     */
    public function get(string $key) {
        if (!$this->enabled)
            return false;
        $file = $this->getCacheFile($key);
        if (!file_exists($file))
            return false;

        $cache_data = @unserialize(file_get_contents($file));
        if (!is_array($cache_data)) {
            unlink($file);
            return false;
        }

        if ($cache_data['expire'] < time()) {
            unlink($file);
            return false;
        }

        foreach ($cache_data['files'] ?? [] as $filepath => $stored_time) {
            if (!file_exists($filepath) || filemtime($filepath) > $stored_time) {
                unlink($file);
                return false;
            }
        }

        return unserialize($cache_data['content']);
    }

    /**
     * Deletes a cache item by key.
     *
     * @param string $key The unique key for the cache item.
     *
     * @return void
     */
    public function delete(string $key): void {
        $file = $this->getCacheFile($key);
        if (file_exists($file))
            unlink($file);
    }

    /**
     * Deletes all cache items associated with the given tag.
     *
     * @param string $tag The tag to search for.
     *
     * @return void
     */
    public function deleteByTag(string $tag): void {
        foreach (glob($this->cache_dir . '*' . $this->ext) as $file) {
            $data = @unserialize(file_get_contents($file));
            if (in_array($tag, $data['tags'] ?? [])) {
                unlink($file);
            }
        }
    }

    /**
     * Cleans expired or invalid cache files.
     *
     * Iterates through all cache files in the cache directory and deletes
     * those that are either expired or contain invalid data.
     *
     * @return void
     */
    public function clean(): void {
        foreach (glob($this->cache_dir . '*' . $this->ext) as $file) {
            $data = @unserialize(file_get_contents($file));
            if (!is_array($data) || ($data['expire'] ?? 0) < time()) {
                unlink($file);
            }
        }
    }

    /**
     * Starts output buffering and returns the cached content if it exists.
     * If the cache doesn't exist, it will be generated and saved when
     * Cache::end() is called.
     *
     * @param string $key The unique key for the cache item.
     * @param int $duration The duration in seconds for which the cache is valid. Default is 3600 seconds.
     * @param array $tags An array of tags associated with the cache item.
     * @param array $files An array of file paths. The cache will be invalidated if any of the files are modified.
     *
     * @return string|null The cached content if it exists, or null if it doesn't.
     */
    public function start(string $key, int $duration = 3600, array $tags = [], array $files = []) {
        if (!$this->enabled)
            return true;
        $content = $this->get($key);
        if ($content !== false) {
            return $content;
        }

        ob_start();
        ob_implicit_flush(false);
        $this->buffer = compact('key', 'duration', 'tags', 'files');
        return null;
    }

    /**
     * Ends output buffering and stores the output in the cache if it doesn't exist
     * already. If the cache does exist, the stored content is returned instead.
     *
     * @return string The cached content if it exists, or the generated content if it doesn't.
     */
    public function end(): string {
        if (!$this->enabled) {
            return ob_get_clean();
        }
        $buffered = ob_get_clean();
        extract($this->buffer);
        $this->set($key, $buffered, $duration, $tags, $files);
        return $buffered;
    }

    /**
     * Generates the full path for a cache file based on the given key.
     *
     * This method uses the cache directory, a hashed version of the key, and the
     * file extension to create a unique path for cache storage.
     *
     * @param string $key The unique key for the cache item.
     * 
     * @return string The full path to the cache file.
     */
    protected function getCacheFile(string $key): string {
        return $this->cache_dir . md5($key) . $this->ext;
    }

    protected function ensureCacheDir(): void {
        if (!is_dir($this->cache_dir)) {
            mkdir($this->cache_dir, 0755, true);
        }
    }
}