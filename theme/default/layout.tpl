<!DOCTYPE html>
<html lang="fr">
    <head>
        {% HOOK.frontHead %}
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>{% SHOW.titleTag %}</title>
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1" />
        <meta name="description" content="{% SHOW.metaDescriptionTag %}" />
        <link rel="icon" href="{% SHOW.themeIcon %}" />
        {% SHOW.linkTags %}
        {% SHOW.scriptTags %}
        {% HOOK.endFrontHead %}
    </head>
    <body>
        <div id="container">
            <div id="header">
                <div id="header_content">
                    <div id="mobile_menu"></div>
                    <p id="siteName"><a href="{% SHOW.siteUrl %}">{% SHOW.siteName %}</a></p>
                    <ul id="navigation">
                        {% SHOW.mainNavigation %}
                        {% HOOK.endMainNavigation %}
                    </ul>
                </div>
            </div>
            <div id="alert-msg">
                {% SHOW.displayMsg %}
            </div>
            <div id="banner"></div>
            <div id="body">
                <div id="content" class="{% SHOW.pluginId %}">
                    {% SHOW.mainTitle %}
                    {{ CONTENT }}
                </div>
            </div>
        <div id="footer">
            <div id="footer_content">
                {% HOOK.footer %}
                <p>
                    <a target='_blank' href='https://github.com/299ko/'>Just using 299ko</a> - Thème {% SHOW.theme %} - <a rel="nofollow" href="<?php echo ADMIN_PATH ?>">Administration</a>
                </p>
                {% HOOK.endFooter %}
            </div>
        </div>
    </div>
{% HOOK.endFrontBody %}
</body>
</html>
