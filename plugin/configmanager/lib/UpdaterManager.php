<?php

/**
 * @copyright (C) 2023, 299Ko
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 * @author Maxence Cauderlier <mx.koder@gmail.com>
 * 
 * @package 299Ko https://github.com/299Ko/299ko
 */
defined('ROOT') OR exit('No direct script access allowed');

class UpdaterManager {

    /**
     * Official last version, not future version
     * @var string
     */
    public $lastVersion;
    
    /**
     * Future version ready to install, depending actual version
     * @var string
     */
    public $nextVersion;
    
    /**
     * File `versions/main/core/versions.json` 
     * @var array
     */
    protected $metaDatas;

    /**
     * REMOTE is raw Github URL
     */
    const REMOTE = 'https://raw.githubusercontent.com/299Ko/';

    public function __construct() {
        if (!ini_get('allow_url_fopen')) {
            logg("Can't get remotes files", 'INFO');
            return false;
        }

        $fileContent = $this->getRemoteFileContent(self::REMOTE . 'versions/main/core/versions.json');

        if (!$fileContent) {
            return false;
        }

        $file = json_decode($fileContent, true);
        $this->lastVersion = $file['last_version'];

        $this->metaDatas = $file;
    }

    /**
     * Get the next version ready to install
     * @return mixed
     */
    public function getNextVersion() {
        if ($this->lastVersion > VERSION) {
            if (key_exists(VERSION, $this->metaDatas)) {
                return $this->metaDatas[VERSION];
            }
        }
        return false;
    }

    public function update() {
        $nextVersion = $this->getNextVersion();
        if ($nextVersion === false) {
            return false;
        }
        
        $rawFiles = $this->getRemoteFileContent(self::REMOTE . 'versions/main/core/' . $nextVersion . '/files.json', 'ERROR');
        if ($rawFiles === false) {
            return false;
        }
        $files = json_decode($rawFiles, true);
        
        logg("Begin update to v$nextVersion", 'INFO');
        $this->runBeforeChangeFiles($nextVersion);
        foreach ($files['M'] as $fileArray) {
            $this->processModify($fileArray, $nextVersion);
        }
        foreach ($files['A'] as $fileArray) {
            $this->processAdd($fileArray, $nextVersion);
        }
        foreach ($files['D'] as $fileArray) {
            $this->processDelete($fileArray, $nextVersion);
        }
        $this->runAfterChangeFiles($nextVersion);
        logg("End update to v$nextVersion", 'INFO');
    }

    protected function rewritePathFile($filename) {
        if (substr($filename, 0, 7) === 'plugin/') {
            return $this->treatPlugin($filename);
        } elseif (substr($filename, 0, 6) === 'theme/') {
            return $this->treatTheme($filename);
        } elseif (substr($filename, 0, 5) === 'core/') {
            return $this->treatCore($filename);
        } elseif (substr($filename, 0, 7) === 'common/') {
            return $this->treatCommon($filename);
        } else {
            return ROOT . $filename;
        }
    }

    protected function getRemoteFile($filename, $version) {
        return self::REMOTE . '299ko/v' . $version . '/' . $filename;
    }

    protected function treatPlugin($filename) {
        preg_match('/^plugin\/(\w+)\/(.*)$/i', $filename, $matches);
        $plugin = $matches[1];
        if (is_dir(PLUGINS . $plugin) === false) {
            // plugin is not installed, no treatment
            return false;
        }
        return PLUGINS . $plugin . '/' . $matches[2];
    }
    
    protected function treatTheme($filename) {
        preg_match('/^theme\/(.*)$/i', $filename, $matches);
        return THEMES . $matches[1];
    }
    
    protected function treatCore($filename) {
        preg_match('/^core\/(.*)$/i', $filename, $matches);
        return CORE . $matches[1];
    }
    
    protected function treatCommon($filename) {
        preg_match('/^common\/(.*)$/i', $filename, $matches);
        return COMMON . $matches[1];
    }


    protected function processModify($file, $version) {
        $localFileName = $this->rewritePathFile($file);
        if ($localFileName === false) {
            return;
        }
        $remoteFile = $this->getRemoteFile($file, $version);
        $content = $this->getRemoteFileContent($remoteFile, 'ERROR');
        if ($content === false) {
            // Remote file dont exist
            return;
        }
        if (@file_put_contents($localFileName, $content, LOCK_EX)) {
            return true;
        }
        logg("unable to write $localFileName", 'ERROR');
        return false;
    }
    
    protected function processAdd($file, $version) {
        $localFileName = $this->rewritePathFile($file);
        if ($localFileName !== false) {
            return;
        }
        $remoteFile = $this->getRemoteFile($file, $version);
        $content = $this->getRemoteFileContent($remoteFile, 'ERROR');
        if ($content === false) {
            // Remote file dont exist
            return;
        }
        if (@file_put_contents($localFileName, $content, LOCK_EX)) {
            logg("file $localFileName Added");
            return true;
        }
        logg("unable to write $localFileName", 'ERROR');
        return false;
    }
    
    protected function processDelete($file) {
        $localFileName = $this->rewritePathFile($file);
        if ($localFileName === false) {
            return;
        }
        if (@unlink($localFileName)) {
            logg("file $localFileName Deleted");
            return true;
        }
        logg("unable to delete $localFileName", 'ERROR');
        return false;
    }

    /**
     * Get the content of a remote file by its URL
     * Log if file is not found
     * 
     * @param string File URL
     * @param string Severity Log message
     * @return mixed Content or false
     */
    protected function getRemoteFileContent($remoteFileUrl, $severity = 'INFO') {
        $headers = get_headers($remoteFileUrl);
        if (strpos($headers[0], '404') !== false) {
            logg("Remote file $remoteFileUrl dont exist", $severity);
            return false;
        }
        $handle = @fopen($remoteFileUrl, 'r');
        // Check if file exists
        if (!$handle) {
            logg("Cant open $remoteFileUrl", $severity);
            return false;
        } else {
            $content = stream_get_contents($handle);
            fclose($handle);
        }
        if ($content === '404: Not Found') {
            // Github file dont exist
            logg("Remote file $remoteFileUrl dont exist", $severity);
            return false;
        }
        return $content;
    }
    
    protected function runBeforeChangeFiles($nextVersion) {
        $remoteFile = $this->getRemoteFile('_beforeChangeFiles.php', $nextVersion);
        $content = $this->getRemoteFileContent($remoteFile);
        if ($content === false) {
            // No script to run before change files
            return;
        }
        $tmpFile = PLUGINS . 'configmanager' . '/tmp_beforeChangeFiles.php';
        if (@file_put_contents($tmpFile, $content, LOCK_EX)) {
            require_once $tmpFile;
            @unlink($tmpFile);
            return true;
        }
        return false;
    }
    
    protected function runAfterChangeFiles($nextVersion) {
        $remoteFile = $this->getRemoteFile('_afterChangeFiles.php', $nextVersion);
        $content = $this->getRemoteFileContent($remoteFile);
        if ($content === false) {
            // No script to run before change files
            return;
        }
        $tmpFile = PLUGINS . 'configmanager' . '/tmp_afterChangeFiles.php';
        if (@file_put_contents($tmpFile, $content, LOCK_EX)) {
            require_once $tmpFile;
            @unlink($tmpFile);
            return true;
        }
        return false;
    }

}