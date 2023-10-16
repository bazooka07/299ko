<?php

/**
 * @copyright (C) 2023, 299Ko
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 * @author Maxence Cauderlier <mx.koder@gmail.com>
 * 
 * @package 299Ko https://github.com/299Ko/299ko
 */
defined('ROOT') OR exit('No direct script access allowed');

$router = router::getInstance();

$router->map('GET', '/blog[/?]', 'BlogListController#home', 'blog-home');
$router->map('GET', '/blog/cat-[*:name]-[i:id]/[i:page][/?]', 'BlogListController#categoryPage', 'blog-category-page');
$router->map('GET', '/blog/cat-[*:name]-[i:id].html', 'BlogListController#category', 'blog-category');
$router->map('GET', '/blog/[*:name]-[i:id].html', 'BlogReadController#read', 'blog-read');
$router->map('POST', '/blog/send.html', 'BlogReadController#send', 'blog-send');
$router->map('GET', '/blog/rss.html', 'BlogReadController#rss', 'blog-rss');
$router->map('GET', '/blog/[i:page][/?]', 'BlogListController#page', 'blog-page');