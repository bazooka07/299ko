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

<section>
    <header>Ajouter un fichier</header>
    <label id="custom-file-label" for="customFile">Ajouter un fichier</label>
    <input type="file" name="image_file" id="customFile" onchange="onSetFilename(this)">
    <progress value="0" max="100" id="filesProgressAjax"></progress>
    <button id="btnUpload" type="button" onclick="uploadFile()">Envoyer le fichier</button>
</section>
<section>
    <header>Liste des fichiers</header>

    <table id="fm-listview">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Dernière modification</th>
                <th>Voir</th>
                <th>URL</th>
                <th>Supprimer</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($manager->getFolders() as $item) {
                echo '<tr><td>' . $item->name . '</td>';
                echo '<td></td><td></td></tr>';
            }

            foreach ($manager->getFiles() as $item) {
                echo '<tr id="' . $item->name . '"><td>' . $item->name . '</td>';
                echo '<td>' . date('d/m/Y à H:i:s', $item->getFileMTime()) . '</td>';
                if ($item->isPicture()) {
                    echo '<td><a class="fm-link" href="' . $item->getUrl() . '" data-fancybox data-caption="' . $item->name . '">'
                    . '<i class="fa-solid fa-eye"></i></a></td>';
                } else {
                    echo '<td></td>';
                }
                echo '<td><a class="fm-link" href="' . $item->getUrl() . '"><i class="fa-solid fa-link"></i></a></td>';
                echo '<td><a class="fm-link alert" href="javascript:deleteFile(\'' . $item->name . '\')"><i class="fa-solid fa-trash"></i></a></td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</section>
<script>
    function onSetFilename(data) {
        let fileName = data.value.split("\\").pop();
        document.getElementById("custom-file-label").innerText = fileName;
    }
    ;

    function deleteFile(name) {
        if (!confirm("ÃŠtes-vous sÃ»r de vouloir supprimer ce fichier ?")) {
            return;
        }

        let formData = new FormData();
        formData.append('filename', name);
        let xhr = new XMLHttpRequest();
        xhr.open("POST", '<?php echo $deleteUrl; ?>', true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const data = JSON.parse(this.responseText);
                if (data.success === 0) {
                    alert("Erreur pendant la suppression du fichier");
                } else {
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
        document.getElementById("btnUpload").innerHTML = '<i class="fa-solid fa-circle-notch fa-spin fa-2x"></i>';
        document.getElementById("btnUpload").setAttribute('disabled', true);
        if (image_files.length) {
            let formData = new FormData();
            formData.append('image', image_files[0]);
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
                        alert("Echec de l'envoi. Veuillez réessayer.");
                    } else {
<?php if ($ajaxView) { ?>
                            Fancybox.close(true);
                            Fancybox.show([
                                {
                                    src: "<?php echo $fancyUrl; ?>",
                                    type: "ajax"
                                }
                            ]);
<?php } else { ?>
                            window.location.href = "<?php echo $redirectUrl; ?>";
<?php } ?>
                    }
                }
            };
            xhr.send(formData);
        } else {
            alert("Aucun fichier sÃ©lectionnÃ©");
        }
    }
    ;
</script>