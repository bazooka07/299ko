<?php
/**
 * @copyright (C) 2022, 299Ko
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 * @author Maxence Cauderlier <mx.koder@gmail.com>
 * 
 * @package 299Ko https://github.com/299Ko/299ko
 */
defined('ROOT') OR exit('No direct script access allowed');
?>

<section id='fm-listview-container'>
    <header>
        <label id="custom-file-label" for="customFile">Ajouter un fichier</label>
        <input type="file" name="image_file" id="customFile" onchange="onSetFilename(this)">
        <input id='dirInput' type='hidden' value='<?php echo rawurlencode($dir); ?>'>
        <progress value="0" max="100" id="filesProgressAjax"></progress>
        <button id="btnUpload" type="button" onclick="uploadFile()">Envoyer le fichier</button>
    </header>
    <div id='fm-breadcrumb-container'>
        <div id='fm-breadcrumb'>
            <a onclick='refreshView("<?php echo rawurlencode('Back%To%Home%'); ?>")'><i class="fa-solid fa-house"></i></a>
            <?php
            $fullPath = '';
            foreach ($dirParts as $item) {
                if ($item === '') {
                    continue;
                }
                $fullPath .= $item . '/';
                echo '<a onclick="refreshView(\'' . rawurlencode($fullPath) . '\')">' . $item . '</a>';
            }
            ?>
        </div>
        <div id='fm-add-folder'>
            <a class="button" onClick='displayNewFolder()'><i class="fa-solid fa-folder-plus"></i> Créer un dossier</a>
        </div>
    </div>

    <div id="fm-listview">
        <div id="fm-view-files">
            <?php
            if ($dir !== '') {
                ?>
                <div class='fm-thumb'>
                    <div class='fm-folder' onClick='refreshView("<?php echo rawurlencode($dir . '/..'); ?>")'>
                        <i class="fa-solid fa-arrow-turn-up"></i>
                        <p class='fm-title' title='Remonter vers le dossier parent'>..</p>
                    </div>
                    <div class='fm-actions'>
                        Dossier parent
                    </div>
                </div>
                <?php
            }
            foreach ($manager->getFolders() as $item) {
                ?>
                <div class='fm-thumb' id='<?php echo $item->name; ?>'>
                    <div class='fm-folder' title='<?php echo $item->name; ?>' onClick='refreshView("<?php echo rawurlencode($dir . '/' . $item->name); ?>")'>
                        <i class="fa-regular fa-folder-open"></i>
                        <p class='fm-title' title='<?php echo $item->name; ?>'><?php echo $item->name; ?></p>
                    </div>
                    <div class='fm-actions'>
                        <a class="fm-link alert" href="javascript:deleteFolder('<?php echo $item->name; ?>')"><i class="fa-solid fa-trash"></i></a>
                    </div>
                </div>
                <?php
            }

            foreach ($manager->getFiles() as $item) {
                ?>
                <div class='fm-thumb' id='<?php echo $item->name; ?>'>
                    <?php
                    if ($item->isPicture()) {
                        ?><div class='fm-picture'><?php
                            echo '<a style="background-image: url(\'' . $item->getUrl() . '\')" class="fm-link" href="' . $item->getUrl() .
                            '" data-fancybox data-caption="' . $item->name . '" title="' . $item->name . '"></a>';
                        } else {
                            ?><div class='fm-file'><?php
                                echo '<i class="fa-regular fa-file" title="' . $item->name . '"></i>';
                            }
                            ?>
                            <p class='fm-title' title='<?php echo $item->name; ?>'><?php echo $item->name; ?></p>
                        </div>
                        <div class='fm-actions'>
                            <?php
                            echo '<a class="fm-link" onClick="copyToClipboard(\'' . $item->getUrl() . '\')"><i class="fa-regular fa-clipboard"></i></a>';
                            echo '<a class="fm-link" href="' . $item->getUrl() . '"><i class="fa-solid fa-link"></i></a>';
                            echo '<a class="fm-link alert" href="javascript:deleteFile(\'' . $item->name . '\')"><i class="fa-solid fa-trash"></i></a>';
                            ?>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
</section>

<section id="fmAddAFolder">
    <header>
        Créer un dossier
    </header>
    <label for="addFolderName">Nom du dossier à créer</label>
    <input id="addFolderName" name="addFolderName" type="text" />
    <button class="success" id="addFolderNameValidate" data-fancybox-close tabindex="-1" onClick="goCreateFolder(document.getElementById('addFolderName').value)">Créer le dossier</button>
</section>
<script>
    function onSetFilename(data) {
        let fileName = data.value.split("\\").pop();
        document.getElementById("custom-file-label").innerText = fileName;
    }

    function displayNewFolder() {
        Fancybox.show([{src: "#fmAddAFolder", type: "inline"}]);
    }

    function goCreateFolder(folderName) {
        if (folderName === '') {
            Toastify({
                text: "Le nom de dossier ne peut être vide",
                className: "error"
            }).showToast();
            Fancybox.show([{src: "#fmAddAFolder", type: "inline"}]);
            return;
        }

        let formData = new FormData();
        formData.append('folderName', folderName);
        formData.append('dir', document.getElementById("dirInput").value);
        let xhr = new XMLHttpRequest();
        xhr.open("POST", '<?php echo $createUrl; ?>', true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const data = JSON.parse(this.responseText);
                if (data.success === 0) {
                    Toastify({
                        text: "Impossible de créer le dossier",
                        className: "error"
                    }).showToast();
                } else {
                    Toastify({
                        text: "Dossier " + name + " créé",
                        className: "success"
                    }).showToast();
                }
            }
        };
        xhr.send(formData);

        refreshView("<?php echo rawurlencode($dir . '/'); ?>" + folderName);
    }

    function copyToClipboard(text) {
        if (!navigator.clipboard) {
            Toastify({
                text: "Impossible de copier dans le presse-papiers",
                className: "error"
            }).showToast();
        } else {
            navigator.clipboard.writeText(text).then(() => {
                Toastify({
                    text: "Copié dans le presse-papiers",
                    className: "success"
                }).showToast();
            }, () => {
                Toastify({
                    text: "Impossible de copier dans le presse-papiers",
                    className: "error"
                }).showToast();
            });
        }
    }

    function refreshView(folderName) {
        
<?php if ($ajaxView) { ?>
            Fancybox.close(true);
            Fancybox.show([
                {
                    src: "<?php echo $redirectUrl . '&view=ajax&dir=' ?>" + folderName,
                    type: "ajax"
                }
            ]);
<?php } else { ?>
            window.location.href = "<?php echo $redirectUrl . '&dir=' ?>" + folderName;
<?php } ?>
    }

    function deleteFile(name) {
        if (!confirm("Êtes-vous sûr de vouloir supprimer ce fichier ?")) {
            return;
        }

        let formData = new FormData();
        formData.append('filename', name);
        formData.append('dir', document.getElementById("dirInput").value);
        let xhr = new XMLHttpRequest();
        xhr.open("POST", '<?php echo $deleteUrl; ?>', true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const data = JSON.parse(this.responseText);
                if (data.success === 0) {
                    Toastify({
                        text: "Impossible de supprimer l'élément " + name,
                        className: "error"
                    }).showToast();
                } else {
                    Toastify({
                        text: "Fichier " + name + " supprimé",
                        className: "success"
                    }).showToast();
                    const item = document.getElementById(name);
                    fadeOut(item);
                    setTimeout(function () {
                        item.remove();
                    }, 1000);
                }
            }
        };
        xhr.send(formData);
    }

    function deleteFolder(name) {
        if (!confirm("Êtes-vous sûr de vouloir supprimer ce dossier ? Tous les dossiers et fichiers contenus dans ce dossier seront supprimés.")) {
            return;
        }

        let formData = new FormData();
        formData.append('foldername', name);
        formData.append('dir', document.getElementById("dirInput").value);
        let xhr = new XMLHttpRequest();
        xhr.open("POST", '<?php echo $deleteUrl; ?>', true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const data = JSON.parse(this.responseText);
                if (data.success === 0) {
                    Toastify({
                        text: "Impossible de supprimer le dossier " + name,
                        className: "error"
                    }).showToast();
                } else {
                    Toastify({
                        text: "Dossier " + name + " supprimé",
                        className: "success"
                    }).showToast();
                    const item = document.getElementById(name);
                    fadeOut(item);
                    setTimeout(function () {
                        item.remove();
                    }, 1000);
                }
            }
        };
        xhr.send(formData);
    }


    function uploadFile() {
        const image_files = document.getElementById('customFile').files;
        document.getElementById("filesProgressAjax").style.visibility = 'visible';
        if (image_files.length) {
            document.getElementById("btnUpload").innerHTML = '<i class="fa-solid fa-circle-notch fa-spin fa-2x"></i>';
            document.getElementById("btnUpload").setAttribute('disabled', true);
            let formData = new FormData();
            formData.append('image', image_files[0]);
            formData.append('dir', document.getElementById("dirInput").value);
            let xhr = new XMLHttpRequest();
            xhr.open("POST", '<?php echo $uploadUrl; ?>', true);
            xhr.upload.addEventListener("progress", function (e) {
                if (e.lengthComputable) {
                    let percentComplete = e.loaded / e.total * 100;
                    document.getElementById("filesProgressAjax").value = percentComplete;
                }
            }, false);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const data = JSON.parse(this.responseText);
                    if (data.success === 0) {
                        Toastify({
                            text: "Echec de l'envoi du fichier",
                            className: "error"
                        }).showToast();
                    } else {
                        refreshView("<?php echo rawurlencode($dir); ?>");
                    }
                }
            };
            xhr.send(formData);
        } else {
            Toastify({
                text: "Aucun fichier sélectionné",
                className: "error"
            }).showToast();
        }
    }
    ;
</script>