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

class newsManager {

    private $items;
    private $comments;

    public function __construct() {
        $data = array();
        if (file_exists(ROOT . 'data/plugin/blog/blog.json')) {
            $temp = util::readJsonFile(ROOT . 'data/plugin/blog/blog.json');
            $temp = util::sort2DimArray($temp, 'date', 'desc');
            foreach ($temp as $k => $v) {
                $data[] = new news($v);
            }
        }
        $this->items = $data;
    }

    // News

    public function getItems() {
        return $this->items;
    }


    /**
     * Summary of create
     * @param mixed $id
     * @return \news | boolean
     */
    public function create($id) {
        foreach ($this->items as $obj) {
            if ($obj->getId() == $id)
                return $obj;
        }
        return false;
    }

    public function saveNews($obj) {
        $id = intval($obj->getId());
        if ($id < 1) {
            $obj->setId($this->makeId());
            $this->items[] = $obj;
        } else {
            foreach ($this->items as $k => $v) {
                if ($id == $v->getId())
                    $this->items[$k] = $obj;
            }
        }
        return $this->save();
    }

    public function delNews($obj) {
        foreach ($this->items as $k => $v) {
            if ($obj->getId() == $v->getId())
                unset($this->items[$k]);
        }
        return $this->save();
    }

    public function count() {
        return count($this->items);
    }

    public function rss() {
        $core = core::getInstance();
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<rss version="2.0">';
        $xml .= '<channel>';
        $xml .= ' <title>' . $core->getConfigVal('siteName') . ' - ' . pluginsManager::getPluginConfVal('blog', 'label') . '</title>';
        $xml .= ' <link>' . $core->getConfigVal('siteUrl') . '/</link>';
        $xml .= ' <description>' . $core->getConfigVal('siteDescription') . '</description>';
        $xml .= ' <language>' . $core->getConfigVal('siteLang') . '</language>';
        foreach ($this->getItems() as $k => $v)
            if (!$v->getDraft()) {
                $xml .= '<item>';
                $xml .= '<title><![CDATA[' . $v->getName() . ']]></title>';
                $xml .= '<link>' . $core->getConfigVal('siteUrl') . '/news/' . util::strToUrl($v->getName()) . '-' . $v->getId() . '.html</link>';
                $xml .= '<pubDate>' . (date("D, d M Y H:i:s O", strtotime($v->getDate()))) . '</pubDate>';
                $xml .= '<description><![CDATA[' . $v->getContent() . ']]></description>';
                $xml .= '</item>';
            }
        $xml .= '</channel>';
        $xml .= '</rss>';
        header('Cache-Control: must-revalidate, pre-check=0, post-check=0, max-age=0');
        header('Content-Type: application/rss+xml; charset=utf-8');
        echo $xml;
        die();
    }

    private function makeId() {
        $ids = array(0);
        foreach ($this->items as $obj) {
            $ids[] = $obj->getId();
        }
        return max($ids) + 1;
    }

    private function save() {
        $data = array();
        foreach ($this->items as $k => $v) {
            $data[] = array(
                'id' => $v->getId(),
                'name' => $v->getName(),
                'content' => $v->getContent(),
                'intro' => $v->getIntro(),
                'seoDesc' => $v->getSEODesc(),
                'date' => $v->getDate(),
                'draft' => $v->getDraft(),
                'img' => $v->getImg(),
                'categories' => $v->categories,
                'commentsOff' => $v->getCommentsOff(),
            );
        }
        if (util::writeJsonFile(ROOT . 'data/plugin/blog/blog.json', $data))
            return true;
        return false;
    }

    // Comments

    public function getComments() {
        return $this->comments;
    }

    public function createComment($id) {
        foreach ($this->comments as $obj) {
            if ($obj->getId() == $id)
                return $obj;
        }
        return false;
    }

    public function loadComments($idNews) {
        if (!file_exists(@mkdir(DATA_PLUGIN . 'blog/comments/')))
            @mkdir(DATA_PLUGIN . 'blog/comments/');
        if (!file_exists(DATA_PLUGIN . 'blog/comments/' . $idNews . '.json'))
            util::writeJsonFile(DATA_PLUGIN . 'blog/comments/' . $idNews . '.json', array());
        $temp = util::readJsonFile(DATA_PLUGIN . 'blog/comments/' . $idNews . '.json');
        $temp = util::sort2DimArray($temp, 'id', 'asc');
        $data = array();
        foreach ($temp as $k => $v) {
            $data[] = new newsComment($v);
        }
        $this->comments = $data;
    }

    public function countComments($idNews = 0) {
        if ($idNews == 0)
            return count($this->comments);
        else {
            $this->loadComments($idNews);
            return count($this->comments);
        }
    }

    public function saveComment($comment = null, $idNews = null) {
        if ($comment != null) {
            $comment->setId(time());
            $this->comments[] = $comment;
            $idNews = $comment->getIdNews();
        }
        $data = array();
        foreach ($this->comments as $k => $v) {
            $data[] = array(
                'id' => $v->getId(),
                'idNews' => $v->getIdNews(),
                'content' => $v->getContent(),
                'date' => $v->getDate(),
                'author' => $v->getAuthor(),
                'authorEmail' => $v->getAuthorEmail(),
            );
        }
        if ($comment == null && $idNews != null) {
            $idNews = $idNews;
        }
        return util::writeJsonFile(DATA_PLUGIN . 'blog/comments/' . $idNews . '.json', $data);
    }

    public function delComment($obj) {
        foreach ($this->comments as $k => $v) {
            if ($obj->getId() == $v->getId())
                unset($this->comments[$k]);
        }
        return $this->saveComment(null, $obj->getIdNews());
    }

}