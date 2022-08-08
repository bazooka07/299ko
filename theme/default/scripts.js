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
    $('#mobile_menu').click(function () {
        if ($('#navigation').css('display') == 'none') {
            $('#navigation').slideDown();
        } else {
            $('#navigation').slideUp();
        }
    });
    var pathname = window.location.href.split('#')[0];
    $('a[href^="#"]').each(function () {
        var $this = $(this),
                link = $this.attr('href');
        $this.attr('href', pathname + link);
    });

    $(".msg").each(function (index) {
        $(this).children(".msg-button-close").click(function () {
            $(this).parent().dequeue();
        });
        $(this).delay(5000 + index * 5000).slideUp();
    });
});