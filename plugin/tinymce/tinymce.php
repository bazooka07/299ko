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

## Fonction d'installation

function tinymceInstall() {
    
}

## Hooks

function tinymceAdminHead() {
    $uploadUrl = util::urlBuild('index.php?p=filemanager&action=upload&view=api&token=' . administrator::getToken(), true);
    $url = util::urlBuild(PLUGINS . 'tinymce/lib/tinymce/tinymce.min.js');
    $options = "language: 'fr_FR',
        images_upload_url: '" . $uploadUrl . "',
        automatic_uploads: true,
        plugins: 'advlist anchor autolink autoresize charmap code codesample emoticons fullscreen help image insertdatetime link lists media nonbreaking searchreplace table visualblocks visualchars wordcount',
        toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | forecolor backcolor removeformat | charmap emoticons | insertfile image link codesample | fullscreen',
        max_height: 600";

    echo "<script src='" . $url . "'></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        tinymce.init({
            selector: 'textarea.editor', " . $options . "
        });

        function mceAdd(item){
            tinyMCE.execCommand('mceAddEditor', false, { id: item.id, options: {" . $options . "}})
        }

        function mceEnd(item){
            tinyMCE.execCommand('mceRemoveEditor', false, { id: item.id});
        }

        Fancybox.bind('[data-src=\'#param_panel\']', {
            on: {
                'ready' : (fancybox, eventName) => {
                    document.querySelectorAll('#param_panel textarea.editor').forEach(function (item, index) {
                        mceAdd(item);
                    });
                },
                'init' : (fancybox, eventName) => {
                    document.querySelectorAll('#param_panel textarea.editor').forEach(function (item, index) {
                        mceEnd(item);
                    });
                }
            }
        });
    });
</script>";
}

## Code relatif au plugin