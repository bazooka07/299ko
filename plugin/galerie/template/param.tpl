<form method="post" action="{{ ROUTER.generate("admin-galerie-save-config")}}">
    {{ SHOW.tokenField()}}

    <p>
        <input {% if runPlugin.getConfigVal("showTitles") %} checked {% endif %} type="checkbox" name="showTitles" id="showTitles" />
        <label for="showTitles">{{ Lang.show_image_titles }}</label>
    </p>

    <p>
        <label for="label">{{ Lang.page_title }}</label><br>
        <input type="text" name="label" id="label" value="{{ runPlugin.getConfigVal("label") }}" />
    </p>
    <p>
        <label for="order">{{ Lang.image_order }}</label><br>
        <select name="order" id="order">
            <option value="natural" {% if runPlugin.getConfigVal("order") == "natural" %} selected {% endif %}>{{ Lang.natural_order }}</option>
            <option value="byName" {% if runPlugin.getConfigVal("order") == "byName" %} selected {% endif %}>{{ Lang.order_by_name }}</option>
            <option value="byDate" {% if runPlugin.getConfigVal("order") == "byDate" %} selected {% endif %}>{{ Lang.order_by_date }}</option>
        </select>
    </p>
    <p>
        <label for="size">{{ Lang.image_size }}</label><br>
        <select name="size" id="size">
            <option value="800" {% if runPlugin.getConfigVal("size") == "800" %} selected {% endif %}>{{ Lang.small }}</option>
            <option value="1024" {% if runPlugin.getConfigVal("size") == "1024" %} selected {% endif %}>{{ Lang.large }}</option>
            <option value="1280" {% if runPlugin.getConfigVal("size") == "1280" %} selected {% endif %}>{{ Lang.extra_large }}</option>
        </select>
    </p>

    <p>
        {{ galerieGenerateEditor() }}
    </p>

    <p><button type="submit" class="button">Enregistrer</button></p>
</form>