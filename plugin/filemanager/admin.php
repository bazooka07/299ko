<?php

/**
 * @copyright (C) 2022, 299Ko
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 * @author Maxence Cauderlier <mx.koder@gmail.com>
 * 
 * @package 299Ko https://github.com/299Ko/299ko
 */
defined('ROOT') OR exit('No direct script access allowed');

$action = isset($_GET['action']) ? $_GET['action'] : null;
$view = isset($_GET['view']) ? $_GET['view'] : null;

if (isset($_POST['dir'])) {
    $dir = $_POST['dir'];
} else {
    $dir = UPLOAD . 'files/';
}

require_once(PLUGINS . 'filemanager/lib/FileManager.php');
$manager = new FileManager($dir);
$uploadUrl = util::urlBuild('index.php?p=filemanager&view=ajax&action=upload&token=' . administrator::getToken(), true);
$deleteUrl = util::urlBuild('index.php?p=filemanager&view=ajax&action=delete&token=' . administrator::getToken(), true);
$ajaxView = $view === 'ajax' ? true : false;

$fancyUrl = util::urlBuild('index.php?p=filemanager&view=ajax&action=upload&token=' . administrator::getToken(), true);
$redirectUrl = util::urlBuild('index.php?p=filemanager', true);

if ($view === 'ajax') {
    if (!$administrator->isAuthorized()) {
        echo json_encode(['success' => 0]);
        die();
    }
    if ($action === 'upload') {
        if (isset($_FILES['image']['name']) != '') {
            $image = $_FILES['image']['name'];
            if ($manager->uploadFile('image')) {
                echo json_encode(['success' => 1]);
                die();
            } else {
                echo json_encode(['success' => 0]);
                die();
            }
        }
    } elseif ($action === 'delete') {
        $deleted = $manager->deleteFile($_POST['filename']);
        echo json_encode(['success' => $deleted]);
        die();
    }
}

    