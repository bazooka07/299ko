<!DOCTYPE html>
<html lang="fr">
    <head>
        {% HOOK.frontHead %}
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>{{ SHOW.titleTag }}</title>
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=5" />
        <meta name="description" content="{{ SHOW.metaDescriptionTag }}" />
        <link rel="icon" href="{{ SHOW.themeIcon }}" />
        {{ SHOW.linkTags }}
        {{ SHOW.scriptTags }}
        {% HOOK.endFrontHead %}        
    </head>
    <body>
        <div id="container">
            <div id="header">
                <nav id="header_content">
                    <button id="mobile_menu" aria-label="Menu"></button>
                    <p id="siteName"><a href="{{ SHOW.siteUrl }}">{{ SHOW.siteName }}</a></p>
                    <ul id="navigation">
                        {{ SHOW.mainNavigation }}
                        {% HOOK.endMainNavigation %}
                    </ul>
                </nav>
            </div>
            <div id="alert-msg">
                {{ SHOW.displayMsg }}
            </div>
            <div id="banner"></div>
            <main id="body">
                {% IF CORE.getConfigVal(hideTitles) == 0 %}
                    <div id="pageTitle">
                    {{ SHOW.mainTitle }}
                </div>
                {% ENDIF %}
                <div id="body-page">
                    <div id="content" class="{{ SHOW.pluginId }}">
                        {{ CONTENT }}
                    </div>
                    {{ show.displayPublicSidebar() }}
                </div>
            </main>
            <div id="footer">
                <div id="footer_content">
                    {% HOOK.footer %}
                    <p>
                        <a target='_blank' href='https://github.com/299ko/'>Just using 299ko</a> - Thème {{ SHOW.theme }} - <a rel="nofollow" href="{{ util.urlBuild("", true) }}">Administration</a>
                    </p>
                    {% HOOK.endFooter %}
                </div>
            </div>
        </div>
        {% HOOK.endFrontBody %}
    </body>
</html>
