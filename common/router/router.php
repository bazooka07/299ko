<?php

/**
 * @copyright (C) 2023, 299Ko
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 * @author Maxence Cauderlier <mx.koder@gmail.com>
 * 
 * @package 299Ko https://github.com/299Ko/299ko
 */
defined('ROOT') OR exit('No direct script access allowed');

class router extends AltoRouter {

    /**
     * 
     * @var \router
     */
    private static $instance;

    private function __construct() {
        if (!empty($_SERVER['REQUEST_URL'])) {
            $url = $_SERVER['REQUEST_URL'];
        } else {
            $url = $_SERVER['REQUEST_URI'];
        }

        parent::__construct();
        $this->setBasePath(str_replace('\\', '/', BASE_PATH));
        $this->map('GET', '/', 'CoreController#renderHome', 'home');
        //$this->map('GET', '/users/[i:id]/', 'UserController#showDetails', 'user' );
        //echo $this->generate('user', ['id' => 5]);
        //echo util::urlBuild($this->generate('user', ['id' => 5]));
    }
    
    public function getCleanURI() {
        $requestUrl = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
        return substr($requestUrl, strlen($this->basePath));
    }

    /**
     * Return Core Instance
     * 
     * @return \self
     */
    public static function getInstance() {
        if (is_null(self::$instance))
            self::$instance = new router();
        return self::$instance;
    }

    public function generate($routeName, array $params = []):string {
        return util::urlBuild(parent::generate($routeName, $params));
    }

}
