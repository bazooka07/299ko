document.addEventListener("DOMContentLoaded", function () {

    if (document.querySelector('.galerie-admin table')) {

        document.querySelectorAll('.galerie-admin tr.hidden').forEach(function (item) {
                    item.style.display = 'none';
        });

        document.querySelector('.galerie-admin .showall').addEventListener('click', function () {
            if (this.dataset.state === 'hidden') {
                this.dataset.state = 'displayed';
                this.innerHTML = '{{ Lang.galerie.hide-hidden }}';
            } else {
                this.dataset.state = 'hidden';
                this.innerHTML = '{{ Lang.galerie.toggle-hidden }}';
            }
            document.querySelectorAll('tr.visible').forEach(function (item) {
                if (item.style.display != 'none') {
                    item.style.display = 'none';
                } else {
                    item.style.display = 'table-row';
                }
            });

            document.querySelectorAll('tr.hidden').forEach(function (item) {
                if (item.style.display != 'none') {
                    item.style.display = 'none';
                } else {
                    item.style.display = 'table-row';
                }
            });
        });

    }

    if (document.querySelector('.galerie-admin .category')) {
        document.querySelectorAll('.galerie-admin .category').forEach(function (item) {
            item.addEventListener('click', function () {
                document.querySelector('.galerie-admin input#category').value = this.textContent;
            });
        });
    }

});