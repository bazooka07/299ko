{{ plop }}

{{ VERSION }}

{{ Lang.site-just-using("1.3.0") }}

{{ Lang.install-php-version-error("15", "abc")}}

{% SET plop = ["plo", 5] %}

{% DUmp plop %}

{% IF ( false == 0 && true == 1 ) && "plop" === plop %}
    plop !
{% ELSEIF ( false == 0 && true == 1 ) && plop === "plo" && 5 === 5 %}
    Plo ?!
{% ELSE %}
    Yo ! Else
{% ENDIF %}

{{ util.urlBuild("plop", true) }}
