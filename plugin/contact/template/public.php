<?php
defined('ROOT') OR exit('No direct script access allowed');
include_once(ROOT . 'theme/' . $core->getConfigVal('theme') . '/header.php');
?>
<section>
    <?php
    echo $runPlugin->getConfigVal('content1');
    ?>

    <form method="post" action="<?php echo $runPlugin->getPublicUrl(); ?>send.html">
        <p>
            <label for="name">Nom</label><br>
            <input style="display:none;" type="text" name="_name" value="" />
            <input required="required" type="text" name="name" id="name" value="<?php echo $name; ?>" />
        </p>	
        <p>
            <label for="firstname">Prénom</label><br>
            <input required="required" type="text" name="firstname" id="firstname" value="<?php echo $firstname; ?>" />
        </p>
        <p>
            <label for="email">Email</label><br>
            <input required="email" type="email" name="email" id="email" value="<?php echo $email; ?>" />
        </p>
        <p>
            <label for="message">Message</label><br>
            <textarea required="required" name="message" id="message"><?php echo $message; ?></textarea>
        </p>
        <?php if ($acceptation) { ?>
            <p class="acceptation">
                <label for="acceptation"><input type="checkbox" required="required" name="acceptation" id="acceptation"/><?php echo $runPlugin->getConfigVal('acceptation'); ?></label>
            </p>
        <?php } ?>
        <?php if (isset($antispamField)) echo $antispamField; ?>
        <p>
            <input type="submit" value="Envoyer" />
        </p>
    </form>

    <?php
    echo $runPlugin->getConfigVal('content2');
    ?>
</section>
<?php
include_once(ROOT . 'theme/' . $core->getConfigVal('theme') . '/footer.php');
