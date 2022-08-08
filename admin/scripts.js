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

$(document).ready(function () {
    $(".msg").each(function (index) {
        $(this).children(".msg-button-close").click(function () {
            $(this).parent().dequeue();
        });
        $(this).delay(5000 + index * 5000).slideUp();
    });


    // tri menu
    var elem = $('#navigation').find('li').sort(sortMe);
    function sortMe(a, b) {
        return a.className > b.className;
    }
    $('#navigation').append(elem);
    // login
    $('#login input.alert').click(function () {
        document.location.href = $(this).attr('rel');
    });
    // nav
    $('#open_nav').click(function () {
        if ($('#sidebar').css('display') == 'none') {
            $('#sidebar').fadeIn();
        } else {
            $('#sidebar').hide();
        }
    });
});

