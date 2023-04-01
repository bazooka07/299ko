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
defined('ROOT') OR exit('No direct script access allowed');

$mode = '';
$action = (isset($_GET['action'])) ? urldecode($_GET['action']) : '';
$error = false;
$page = new page();

switch ($action) {
    case 'save':
        if ($administrator->isAuthorized()) {
            $imgId = (isset($_POST['delImg'])) ? '' : $_REQUEST['imgId'];
            if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {
                if ($pluginsManager->isActivePlugin('galerie')) {
                    $galerie = new galerie();
                    $img = new galerieItem(array('category' => ''));
                    $img->setTitle($_POST['name'] . ' (image à la une)');
                    $img->setContent('');
                    $img->setDate(date('Y-m-d H:i:s'));
                    $img->setHidden(1);
                    $galerie->saveItem($img);
                    $imgId = $galerie->getLastId() . '.' . util::getFileExtension($_FILES['file']['name']);
                }
            }
            if ($_POST['id'] != '')
                $pageItem = $page->create($_POST['id']);
            else
                $pageItem = new pageItem();
            $pageItem->setName($_POST['name']);
            $pageItem->setPosition($_POST['position']);
            $pageItem->setIsHomepage((isset($_POST['isHomepage'])) ? 1 : 0);
            $pageItem->setContent((isset($_POST['content'])) ? $core->callHook('beforeSaveEditor', $_POST['content']) : '');
            $pageItem->setFile((isset($_POST['file'])) ? $_POST['file'] : '');
            $pageItem->setIsHidden((isset($_POST['isHidden'])) ? 1 : 0);
            $pageItem->setMainTitle((isset($_POST['mainTitle'])) ? $_POST['mainTitle'] : '');
            $pageItem->setMetaDescriptionTag((isset($_POST['metaDescriptionTag'])) ? $_POST['metaDescriptionTag'] : '');
            $pageItem->setMetaTitleTag((isset($_POST['metaTitleTag'])) ? $_POST['metaTitleTag'] : '');
            $pageItem->setTarget((isset($_POST['target'])) ? $_POST['target'] : '');
            $pageItem->setTargetAttr((isset($_POST['targetAttr'])) ? $_POST['targetAttr'] : '');
            $pageItem->setNoIndex((isset($_POST['noIndex'])) ? 1 : 0);
            $pageItem->setParent((isset($_POST['parent'])) ? $_POST['parent'] : '');
            $pageItem->setCssClass($_POST['cssClass']);
            $pageItem->setImg($imgId);
            if (isset($_POST['_password']) && $_POST['_password'] != '')
                $pageItem->setPassword($_POST['_password']);
            if (isset($_POST['resetPassword']))
                $pageItem->setPassword('');
            if ($page->save($pageItem))
                show::msg("Les modifications ont été enregistrées", 'success');
            else
                show::msg("Une erreur est survenue", 'error');
            header('location:index.php?p=page&action=edit&id=' . $pageItem->getId());
            die();
        }
        break;
    case 'edit':
        if (isset($_GET['id']))
            $pageItem = $page->create($_GET['id']);
        else
            $pageItem = new pageItem();
        $isLink = (isset($_GET['link']) || $pageItem->targetIs() == 'url') ? true : false;
        $isParent = (isset($_GET['parent']) || $pageItem->targetIs() == 'parent') ? true : false;
        $mode = 'edit';
        break;
    case 'del':
        if ($administrator->isAuthorized()) {
            $pageItem = $page->create($_GET['id']);
            if ($page->del($pageItem))
                show::msg("Les modifications ont été enregistrées", 'success');
            else
                show::msg("Une erreur est survenue", 'error');
            header('location:index.php?p=page');
            die();
        }
        break;
    case 'up':
        if ($administrator->isAuthorized()) {
            $pageItem = $page->create($_GET['id']);
            $newPos = $pageItem->getPosition() - 1.5;
            $pageItem->setPosition($newPos);
            $page->save($pageItem);
            header('location:index.php?p=page');
            die();
        }
        break;
    case 'down':
        if ($administrator->isAuthorized()) {
            $pageItem = $page->create($_GET['id']);
            $newPos = $pageItem->getPosition() + 1.5;
            $pageItem->setPosition($newPos);
            $page->save($pageItem);
            header('location:index.php?p=page');
            die();
        }
        break;
    case 'maintenance':
        $id = explode(',', $_GET['id']);
        foreach ($id as $k => $v)
            if ($v != '') {
                $pageItem = $page->create($v);
                $page->del($pageItem);
            }
        header('location:index.php?p=page');
        die();
        break;
    default:
        // Recherche des pages perdues
        $parents = array();
        $lost = '';
        foreach ($page->getItems() as $k => $v)
            if ((int) $v->getParent() == 0) {
                $parents[] = $v->getId();
            }
        foreach ($page->getItems() as $k => $v)
            if ((int) $v->getParent() > 0) {
                if (!in_array($v->getParent(), $parents))
                    $lost .= $v->getId() . ',';
            }
        // Suite...
        if (!$page->createHomepage() && $pluginsManager->getPlugin('page')->getIsDefaultPlugin())
            show::msg("Aucune page d'accueil n'a été définie", 'warning');
        $mode = 'list';
}
?>