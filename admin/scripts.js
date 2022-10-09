/**
 * @copyright (C) 2022, 299Ko, based on code (2010-2021) 99ko https://github.com/99kocms/
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 * @author Jonathan Coulet <j.coulet@gmail.com>
 * @author Maxence Cauderlier <mx.koder@gmail.com>
 * @author Frédéric Kaplon <frederic.kaplon@me.com>
 * @author Florent Fortat <florent.fortat@maxgun.fr>
 * @author ShevAbam <me@shevarezo.fr>
 * 
 * @package 299Ko https://github.com/299Ko/299ko
 */

document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll('.msg').forEach(function (item, index) {
        item.querySelector('.msg-button-close').addEventListener('click', function () {
            fadeOut(item);
        });

        setTimeout(function () {
            fadeOut(item);
        }, 5000 + index * 5000);

    });


    // Login : btn Quitter redirection
    if (document.querySelector('#login input.alert')) {
        document.querySelector('#login input.alert').addEventListener('click', function () {
            document.location.href = this.getAttribute('rel');
        });
    }

    // Nav
    if (document.querySelector('#open_nav')) {
        document.querySelector('#open_nav').addEventListener("click", function () {

            var sidebar = document.querySelector('#sidebar');

            if (sidebar.style.display == 'none' || sidebar.style.display == '') {
                fadeIn(sidebar, 'block');
            } else {
                fadeOut(sidebar);
            }

        });
    }

});

function fadeOut(el) {
    el.style.opacity = 1;
    (function fade() {
        if ((el.style.opacity -= .03) < 0) {
            el.style.display = "none";
        } else {
            requestAnimationFrame(fade);
        }
    })();
};

function fadeIn(el, display) {
    el.style.opacity = 0;
    el.style.display = display || "block";
    (function fade() {
        var val = parseFloat(el.style.opacity);
        if (!((val += .03) > 1)) {
            el.style.opacity = val;
            requestAnimationFrame(fade);
        }
    })();
};
