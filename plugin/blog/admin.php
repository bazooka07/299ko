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

$action = (isset($_GET['action'])) ? $_GET['action'] : '';
$newsManager = new newsManager();
$categoriesManager = new BlogCategoriesManager();

switch ($action) {
    case 'addCategory' :
        if ($administrator->isAuthorized()) {
            $label = filter_input(INPUT_POST, 'category-add-label', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $parentId = filter_input(INPUT_POST, 'category-add-parentId', FILTER_VALIDATE_INT) ?? 0;
            $categoriesManager->createCategory($label, $parentId);
            show::msg("La catégorie a été créée", 'success');
            header('location:.?p=blog');
            die();
        }
    case 'deleteCategory' :
        $id = (int) $_GET['id'];
        if (!$administrator->isAuthorized()) {
            core::getInstance()->error404();
        }
        if ($categoriesManager->isCategoryExist($id)) {
            if ($categoriesManager->deleteCategory($id)) {
                show::msg('La catégorie a bien été supprimée.', 'success');
            } else {
                show::msg('Une erreur s\'est produite lors de la suppression de la catégorie', 'error');
            }
        }
        header('location:.?p=blog');
        die();
    case 'editCategory' :
        $id = (int) $_GET['id'];
        if (!$categoriesManager->isCategoryExist($id)) {
            show::msg('La catégorie demandée n\'existe pas', 'error');
            header('location:.?p=blog');
            die();
        }
        $category = $categoriesManager->getCategory($id);
        $mode = 'editCategory';
        break;
    case 'saveCategory':
            if (!$administrator->isAuthorized()) {
                core::getInstance()->error404();
            }
            $label = filter_input(INPUT_POST, 'label', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $parentId = filter_input(INPUT_POST, 'parentId', FILTER_VALIDATE_INT ?? 0);
            $id = (int) $_GET['id'];
            if (!$categoriesManager->isCategoryExist($id)) {
                show::msg('La catégorie demandée n\'existe pas', 'error');
                header('location:index.php?p=categories&plugin=' . $pluginId);
                die();
            }
            $label = $_POST['label'];
            $parentId = (int) $_POST['parentId'];
            if ($parentId !== 0 && !$categoriesManager->isCategoryExist($parentId)) {
                show::msg('La catégorie parente n\'existe pas', 'error');
                header('location:.?p=blog');
                die();
            }
            $categorie = $categoriesManager->getCategory($id);
            $categorie->parentId = $parentId;
            $categorie->label = $label;
            $categoriesManager->saveCategory($categorie);
            show::msg('La catégorie a bien été modifiée', 'success');
            header('location:.?p=blog');
            die();
    case 'saveconf':
        if ($administrator->isAuthorized()) {
            $runPlugin->setConfigVal('label', trim($_REQUEST['label']));
            $runPlugin->setConfigVal('itemsByPage', trim(intval($_REQUEST['itemsByPage'])));
            $runPlugin->setConfigVal('hideContent', (isset($_POST['hideContent']) ? 1 : 0));
            $runPlugin->setConfigVal('comments', (isset($_POST['comments']) ? 1 : 0));
            $runPlugin->setConfigVal('displayTOC', filter_input(INPUT_POST, 'displayTOC', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
            $runPlugin->setConfigVal('displayAuthor', (isset($_POST['displayAuthor']) ? 1 : 0));
            $runPlugin->setConfigVal('authorName', trim($_POST['authorName']));
            $runPlugin->setConfigVal('authorAvatar', trim($_POST['authorAvatar']));
            $runPlugin->setConfigVal('authorBio', $core->callHook('beforeSaveEditor',htmlspecialchars($_POST['authorBio'])));
            $pluginsManager->savePluginConfig($runPlugin);
            show::msg("Les modifications ont été enregistrées", 'success');
            header('location:.?p=blog');
            die();
        }
        break;
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
            $news = ($_REQUEST['id']) ? $newsManager->create($_REQUEST['id']) : new news();
            $news->setName($_REQUEST['name']);
            $news->setContent($core->callHook('beforeSaveEditor', htmlspecialchars($_REQUEST['content'])));
            $news->setIntro($core->callHook('beforeSaveEditor', htmlspecialchars($_REQUEST['intro'])));
            $news->setSEODesc($_REQUEST['seoDesc']);
            $news->setDraft((isset($_POST['draft']) ? 1 : 0));
            if (!isset($_REQUEST['date']) || $_REQUEST['date'] == "")
                $news->setDate($news->getDate());
            else
                $news->setDate($_REQUEST['date']);
            $news->setImg($imgId);
            $news->setCommentsOff((isset($_POST['commentsOff']) ? 1 : 0));
            if ($newsManager->saveNews($news)) {
                $blogCats = $categoriesManager->getCategories();
                $choosenCats = [];
                if (isset($_POST['categoriesCheckbox'])) {
                    foreach ($_POST['categoriesCheckbox'] as $k => $cat) {
                        $choosenCats[] = (int) $cat;
                    }
                }
                $label = filter_input(INPUT_POST, 'category-add-label', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if ($label !== '') {
                    $parentId = filter_input(INPUT_POST, 'category-add-parentId', FILTER_VALIDATE_INT) ?? 0;
                    $choosenCats[] = $categoriesManager->createCategory($label, $parentId);
                }
                BlogCategoriesManager::saveItemToCategories($news->getId(), $choosenCats);
                show::msg("Les modifications ont été enregistrées", 'success');
            } else {
                show::msg("Une erreur est survenue", 'error');
            }
            header('location:.?p=blog&action=edit&id=' . $news->getId());
            die();
        }
        break;
    case 'edit':
        $mode = 'edit';
        $news = (isset($_REQUEST['id'])) ? $newsManager->create($_GET['id']) : new news();
        $showDate = (isset($_REQUEST['id'])) ? true : false;
        break;
    case 'del':
        if ($administrator->isAuthorized()) {
            $news = $newsManager->create($_REQUEST['id']);
            if ($newsManager->delNews($news)) {
                show::msg("Les modifications ont été enregistrées", 'success');
            } else {
                show::msg("Une erreur est survenue", 'error');
            }
            header('location:.?p=blog');
            die();
        }
        break;
    case 'listcomments':
        $mode = 'listcomments';
        $newsManager->loadComments($_GET['id']);
        break;
    case 'delcomment':
        if ($administrator->isAuthorized()) {
            $newsManager->loadComments($_GET['id']);
            $comment = $newsManager->createComment($_GET['idcomment']);
            if ($newsManager->delComment($comment)) {
                show::msg("Les modifications ont été enregistrées", 'success');
            } else {
                show::msg("Une erreur est survenue", 'error');
            }
            header('location:.?p=blog&action=listcomments&id=' . $_GET['id']);
            die();
        }
        break;
    case 'updatecomment':
        if ($administrator->isAuthorized()) {
            $newsManager->loadComments($_GET['id']);
            $comment = $newsManager->createComment($_GET['idcomment']);
            $comment->setContent($_POST['content' . $_GET['idcomment']]);
            if ($newsManager->saveComment($comment)) {
                show::msg("Les modifications ont été enregistrées", 'success');
            } else {
                show::msg("Une erreur est survenue", 'error');
            }
            header('location:.?p=blog&action=listcomments&id=' . $_GET['id']);
            die();
        }
        break;
    default:
        $mode = 'list';
}