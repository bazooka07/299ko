{% HOOK.adminHead %}

<form id="configForm" method="post" action="{{link}}" autocomplete="off">
    {{SHOW.tokenField}}
    <section>
        <header>{{Lang.configmanager-settings}}</header>
        <p>
            <input {% if CORE.getConfigVal("hideTitles") %}checked{% endif %} type="checkbox" name="hideTitles" id="hideTitles" /> <label for="hideTitles">{{Lang.configmanager-hide-titles}}</label>
        </p>
        <p>
            <label for="defaultPlugin">{{Lang.configmanager-public-default-plugin}}</label>
            <select name="defaultPlugin">
                {% for plugin in pluginsManager.getPlugins %}
                    {% if plugin.getCOnfigVal("activate") && plugin.getIsCallableOnPublic %}
                        <option {% if plugin.getIsDefaultPlugin %}selected{% endif %} value="{{plugin.getName}}">{{plugin.getInfoVal("name")}}</option>
                    {% endif %}
                {% endfor %}
            </select>
        </p>
        <p>
            <label for="defaultAdminPlugin">{{Lang.configmanager-admin-default-plugin}}</label>
            <select name="defaultAdminPlugin">
                {% for plugin in pluginsManager.getPlugins %}
                    {% if plugin.getCOnfigVal("activate") && plugin.getIsCallableOnAdmin %}
                        <option {% if plugin.getIsDefaultAdminPlugin %}selected{% endif %} value="{{plugin.getName}}">{{plugin.getInfoVal("name")}}</option>
                    {% endif %}
                {% endfor %}
            </select>
        </p>
        <p>
            <label for="siteName">{{Lang.configmanager-sitename}}</label>
            <input type="text" name="siteName" id="siteName" value="{{ CORE.getConfigVal("siteName")}}" required />
        </p>
        <p>
            <label for="siteDesc">{{Lang.configmanager-sitedesc}}</label>
            <input type="text" name="siteDesc" value="{{ CORE.getConfigVal("siteDesc")}}"/>
        </p>
        <p>
            <label for="siteLang">{{Lang.configmanager-sitelang}}</label>
            <select name="siteLang">
                {% for k, v in lang.getAvailablesLocales %}
                    <option {% if lang.getLocale == k %}selected{% endif %} value="{{k}}">{{v}}</option>
                {% endfor %}
            </select>
        </p>
        <p>
            <label id="label_theme" for="theme">{{Lang.configmanager-theme}}</label>
            <select name="theme">
                {% for k, v in CORE.getThemes %}
                    <option {% if CORE.getConfigVal("theme") == k %}selected{% endif %} value="{{k}}">{{v.name}}</option>
                {% endfor %}
            </select>
        </p>
    </section>
    <section>
        <header>{{Lang.configmanager-advanced-settings}}</header>
        <p>
            <label id='delCacheDesc'>{{Lang.configmanager-delete-cache-desc}}</label><br>
            <a aria-describedby="delCacheDesc" class="button" href="{{cacheClearLink}}">{{Lang.configmanager-delete-cache}}</a>
        </p>
        <p>
            <input {% if CORE.getConfigVal("debug") %}checked{% endif %} type="checkbox" name="debug" /> <label for="debug">{{Lang.configmanager-debug}}</label> 
        </p>
        <p>
            <label>{{Lang.configmanager-siteurl}}</label><br>
            <input type="text" name="siteUrl" value="{{CORE.getConfigVal("siteUrl")}}" />
        </p>
        <p>
            <label for="htaccess">{{Lang.configmanager-htaccess}}</label><br>
            <textarea id="htaccess" name="htaccess">{{CORE.getHtaccess}}</textarea>
        </p>
    </section>
    <section>
        <header>
            {{Lang.configmanager-cache-settings}}
            <span class="help-icon" title="{{Lang.configmanager-cache-help-title}}" data-help="cache">?</span>
        </header>
        <p>
            <input {% if CORE.getConfigVal("cache_enabled") %}checked{% endif %} type="checkbox" name="cache_enabled" id="cache_enabled" /> 
            <label for="cache_enabled">{{Lang.configmanager-cache-enabled}}</label>
            <br><small>{{Lang.configmanager-cache-enabled-desc}}</small>
        </p>
        <p>
            <label for="cache_duration">{{Lang.configmanager-cache-duration}}</label>
            <input type="number" name="cache_duration" id="cache_duration" value="{{CORE.getConfigVal("cache_duration") ?: 3600}}" min="60" max="86400" />
            <br><small>{{Lang.configmanager-cache-duration-desc}}</small>
        </p>
        <p>
            <input {% if CORE.getConfigVal("cache_minify") %}checked{% endif %} type="checkbox" name="cache_minify" id="cache_minify" /> 
            <label for="cache_minify">{{Lang.configmanager-cache-minify}}</label>
            <br><small>{{Lang.configmanager-cache-minify-desc}}</small>
        </p>
        <p>
            <input {% if CORE.getConfigVal("cache_lazy_loading") %}checked{% endif %} type="checkbox" name="cache_lazy_loading" id="cache_lazy_loading" /> 
            <label for="cache_lazy_loading">{{Lang.configmanager-cache-lazy-loading}}</label>
            <br><small>{{Lang.configmanager-cache-lazy-loading-desc}}</small>
        </p>
        <p>
            <label id="cacheClearDesc">{{Lang.configmanager-cache-clear-desc}}</label><br>
            <a aria-describedby="cacheClearDesc" class="button" href="{{cacheClearLink}}">{{Lang.configmanager-cache-clear}}</a>
        </p>
        <p>
            <label id="cacheStatsDesc">{{Lang.configmanager-cache-stats-desc}}</label><br>
            <a aria-describedby="cacheStatsDesc" class="button" href="{{cacheStatsLink}}">{{Lang.configmanager-cache-stats}}</a>
        </p>
        {% if cacheStats %}
        <div class="cache-stats">
            <p><strong>{{Lang.configmanager-cache-files-count}}:</strong> {{cacheStats.files_count}}</p>
            <p><strong>{{Lang.configmanager-cache-total-size}}:</strong> {{cacheStats.total_size_formatted}}</p>
            {% if cacheStats.last_clean %}
            <p><strong>{{Lang.configmanager-cache-last-clean}}:</strong> {{cacheStats.last_clean}}</p>
            {% endif %}
        </div>
        {% endif %}
    </section>
    <p>
        <button type="submit" class="button success">{{Lang.submit}}</button></p>
</form>

<!-- Help popup -->
<div id="help-popup" class="help-popup">
    <button class="close-btn" onclick="closeHelpPopup()">&times;</button>
    <div id="help-content"></div>
</div>
