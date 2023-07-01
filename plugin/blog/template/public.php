<?php defined('ROOT') OR exit('No direct script access allowed'); ?>
<?php include_once(THEMES . $core->getConfigVal('theme') . '/header.php') ?>

<?php if ($mode == 'list') { ?>
    <?php foreach ($news as $k => $v) { ?>
        <article>
            <?php if (!$runPlugin->getConfigVal('hideContent')) { ?>
                <header>
                    <?php
                    if ($pluginsManager->isActivePlugin('galerie') && galerie::searchByfileName($v['img']))
                        echo '<img class="featured" src="' . UPLOAD . 'galerie/' . $v['img'] . '" alt="' . $v['img'] . '" />';
                    ?>
                    <div class="item-head">
                        <h2>
                            <a href="<?php echo $v['url']; ?>"><?php echo $v['name']; ?></a>
                        </h2>
                        <p class="date"><?php echo $v['date']; ?>
                            <?php if ($runPlugin->getConfigVal('comments') && !$v['commentsOff']) { ?> | <?php echo $newsManager->countComments($v['id']); ?> commentaire<?php if ($newsManager->countComments($v['id']) > 1) echo 's' ?><?php } ?></p>
                    </div>
                </header><?php
                if ($v['intro']) {
                    echo $v['intro'];
                } else {
                    echo $v['content'];
                }
            } else {
                ?>
                <h2>
                    <a href="<?php echo $v['url']; ?>"><?php echo $v['name']; ?></a>
                </h2>
                <p class="date"><?php echo $v['date']; ?><?php if ($runPlugin->getConfigVal('comments') && !$v['commentsOff']) { ?> | <?php echo $newsManager->countComments($v['id']); ?> commentaire<?php if ($newsManager->countComments($v['id']) > 1) echo 's' ?><?php } ?></p>
            <?php } ?>
        </article>
    <?php } ?>
    <ul class="pagination">
        <?php foreach ($pagination as $k => $v) { ?>
            <li><a href="<?php echo $v['url']; ?>"><?php echo $v['num']; ?></a></li>
        <?php } ?>
    </ul>
<?php } ?>

<?php if ($mode == 'list_empty') { ?>
    <p>Aucun élément n'a été trouvé.</p>
<?php } ?>

<?php if ($mode == 'read') { ?>
    <article>
        <header>
            <?php
            if ($pluginsManager->isActivePlugin('galerie') && galerie::searchByfileName($item->getImg()))
                echo '<img class="featured" src="' . UPLOAD . 'galerie/' . $item->getImg() . '" alt="' . $item->getName() . '" />';
            ?>
            <div class="item-head">
                <p class="date">
                    Posté le <?php echo util::FormatDate($item->getDate(), 'en', 'fr'); ?>
                    <?php if ($runPlugin->getConfigVal('comments') && !$item->getCommentsOff()) { ?> | <?php echo $newsManager->countComments(); ?> commentaire<?php if ($newsManager->countComments($item->getId()) > 1) echo 's' ?><?php } ?>
                    | <a href="<?php echo $runPlugin->getPublicUrl(); ?>">Retour à la liste</a>
                </p>
            </div>
        </header>
        <?php
        echo $item->getContent();
        if ($runPlugin->getConfigVal('displayAuthor')) {
            ?>
        <footer>
            <div class='blog-author'>
                <div class='blog-avatar'>
                    <img src='<?php echo $runPlugin->getConfigVal('authorAvatar'); ?>' alt='<?php echo $runPlugin->getConfigVal('authorName'); ?>'/>
                </div>
                <div class='blog-infos'>
                    <div class='blog-infos-name'>
                        <span><?php echo $runPlugin->getConfigVal('authorName'); ?></span>
                    </div>
                    <div class='blog-infos-bio'>
                        <?php echo $runPlugin->getConfigVal('authorBio'); ?>
                    </div>
                </div>
            </div>
        </footer>
        <?php
        }
        ?>
    </article>
    <?php if ($runPlugin->getConfigVal('comments') && !$item->getCommentsOff()) { ?>
        <section id="comments">
            <header>
                <div class="item-head"><h2>Commentaires</h2></div>
            </header>
            <?php if ($newsManager->countComments() == 0) { ?><p>Il n'y a pas de commentaires</p><?php } else { ?>
                <ul class="comments-list">
                    <?php
                    foreach ($newsManager->getComments() as $k => $v) {
                        ?>
                        <li class="comments-item">
                            <span class="infos"><?php echo $v->getAuthor(); ?> | <?php echo util::FormatDate($v->getDate(), 'en', 'fr'); ?></span>
                            <div class="comment" id="comment<?php echo $v->getId(); ?>"><p><?php echo nl2br($v->getContent()); ?></p></div>
                        </li>
                        <?php
                    }
                    ?></ul><?php
            }
            ?>
            <footer>
                <h2>Ajouter un commentaire</h2>
                <form method="post" action="<?php echo $runPlugin->getPublicUrl(); ?>send.html">
                    <input type="hidden" name="id" value="<?php echo $item->getId(); ?>" />
                    <input type="hidden" name="back" value="<?php echo $runPlugin->getPublicUrl() . util::strToUrl($item->getName()) . '-' . $item->getId() . '.html'; ?>" />
                    <p>
                        <label for="author">Pseudo</label><br>
                        <input style="display:none;" type="text" name="_author" value="" />
                        <input type="text" name="author" id="author" required="required" />
                    </p>
                    <p><label for="authorEmail">Email</label><br><input type="text" name="authorEmail" id="authorEmail" required="required" /></p>
                    <p><label for="commentContent">Commentaire</label><br><textarea name="commentContent" id="commentContent" required="required"></textarea></p>
                    <?php if (isset($antispamField)) echo $antispamField; ?>
                    <p><input type="submit" value="Publier le commentaire" /></p>
                </form>
            </footer>
        </section>
    <?php } ?>
<?php } ?>

<?php include_once(THEMES . $core->getConfigVal('theme') . '/footer.php') ?>