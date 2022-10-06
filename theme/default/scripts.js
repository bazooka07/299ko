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

document.addEventListener("DOMContentLoaded", function() {
    if (document.querySelector('#mobile_menu')) {
        document.querySelector('#mobile_menu').addEventListener("click", function() {

            var navigation = document.querySelector('#header #navigation');

            if (!navigation.classList.contains('active')) {
                // slideDown

                navigation.classList.add('active');
                navigation.style.height = "auto";

                var height = navigation.clientHeight + "px";

                navigation.style.height = "0px";

                setTimeout(function() {
                    navigation.style.height = height;
                }, 0);

            } else {
                // slideUp

                navigation.style.height = "0px";

                navigation.addEventListener('transitionend', function() {
                    navigation.classList.remove('active');
                }, {once: true});
            }

        });
    }


    var pathname = window.location.href.split('#')[0];
    document.querySelectorAll('a[href^="#"]').forEach(function(item) {
        var link = item.getAttribute('href');
        item.setAttribute('href', pathname + link);
    });


    document.querySelectorAll('.msg').forEach(function(item, index) {
        item.querySelector('.msg-button-close').addEventListener('click', function() {
            fadeOut(item);
        });

        setTimeout(function() {
            fadeOut(item);
        }, 5000 + index * 5000);

    });

});


function fadeOut(el) {
    el.style.opacity = 1;
    (function fade() {
        if ((el.style.opacity -= .1) < 0) {
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
        if (!((val += .1) > 1)) {
            el.style.opacity = val;
            requestAnimationFrame(fade);
        }
    })();
};