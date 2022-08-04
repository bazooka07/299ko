<?php

defined('ROOT') OR exit('No direct script access allowed');
$action = (isset($_GET['action'])) ? $_GET['action'] : '';
switch ($action) {
    case 'save':
        if ($administrator->isAuthorized()) {
            $pos = $_POST['position'];
            seoSavePositionMenu($pos);
            
            $runPlugin->setConfigVal('position', $pos);
            $runPlugin->setConfigVal('trackingId', trim($_POST['trackingId']));
            $runPlugin->setConfigVal('wt', trim($_POST['wt']));
            
            // Save Social adress
            $vars = seoGetSocialVars();
            foreach ($vars as $k => $v) {
                $runPlugin->setConfigVal($v, trim($_POST[$v]));
            }
            
            if ($pluginsManager->savePluginConfig($runPlugin)) {
                $msg = "Les modifications ont été enregistrées";
                $msgType = 'success';
            } else {
                $msg = "Une erreur est survenue";
                $msgType = 'error';
            }
            header('location:index.php?p=seo&msg=' . urlencode($msg) . '&msgType=' . $msgType);
            die();
        }
        break;
    default:
}

function seoSavePositionMenu($position) {
    $arr = ['endFrontHead' => 'seoEndFrontHead'];
    switch ($position) {
        case 'menu':
            $tmp = ['endMainNavigation' => 'seoMainNavigation'];
            break;
        case 'footer':
            $tmp = ['footer' => 'seoFooter'];
            break;
        case 'endfooter':
            $tmp = ['endFooter' => 'seoFooter'];
            break;
        case 'float':
            $tmp = ['endFrontBody' => 'seoEndFrontBody'];
            break;
        default:
            $tmp = [];
    }
    $data = array_merge($arr, $tmp);
    util::writeJsonFile(PLUGINS . 'seo/param/hooks.json', $data);
}

?>