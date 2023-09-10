<?php

/**
 * @copyright (C) 2023, 299Ko
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 * @author Maxence Cauderlier <mx.koder@gmail.com>
 * 
 * @package 299Ko https://github.com/299Ko/299ko
 */
defined('ROOT') or exit('No direct script access allowed');

class CoreController extends Controller
{

    public function renderHome()
    {
        $callback = $this->runPlugin->getCallablePublic();

        if (method_exists($callback[0], $callback[1])) {
            $obj = new $callback[0]();
            $response = call_user_func([$obj, $callback[1]]);
            return $response;
        } else {
            // unreachable target
            core::getInstance()->error404();
        }
    }
}