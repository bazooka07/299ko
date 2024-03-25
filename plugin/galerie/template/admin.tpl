{% if mode == "list" %}
    <section>
        <header>{{ Lang.galerie.images-list }}</header>
        <a class="button" href="{{ ROUTER.generate("admin-galerie-edit") }}">{{ Lang.add }}</a>
        <a class="button showall" data-state="hidden" href="javascript:">{{ Lang.galerie.toggle-hidden }}</a>
        <table>
            <tr>
                <th>{{ Lang.galerie.preview }}</th>
                <th>{{ Lang.galerie.title }}</th>
                <th>{{ Lang.galerie.category }}</th>
                <th>{{ Lang.galerie.url }}</th>
                <th>{{ Lang.galerie.actions }}</th>
            </tr>
            {% for item in galerie.getItems() %}
                <tr class="{% if item.getHidden() %}hidden{% else %}visible{% endif %}">
                    <td><a href="{{ item.getUrl() }}" data-fancybox data-caption="{{ item.getTitle() }}" title="{{ item.getTitle() }}"><img width="128" src="{{ item.getUrl() }}" alt="{{ item.getImg() }}" /></a>
                    </td>
                    <td>{{ item.getTitle() }}</td>
                    <td>{{ item.getCategory() }}</td>
                    <td><input readonly="readonly" type="text" value="{{ item.getUrl() }}" /></td>
                    <td>
                        <a href="{{ ROUTER.generate("admin-galerie-edit-id", ["id" => item.getId()]) }}" class="button">{{ Lang.edit }}</a>
                        <a href="{{ ROUTER.generate("admin-galerie-delete", ["id" => item.getId(), "token" => token]) }}" onclick="if (!confirm('{{ Lang.confirm.deleteItem }}')) return false;" class="button alert">{{ Lang.delete }}</a>
                    </td>
                </tr>
            {% endfor %}
        </table>
    </section>
{% endif %}

{% if mode == "edit" %}
    <form method="post" action="{{ ROUTER.generate("admin-galerie-save") }}" enctype="multipart/form-data">
        {{ show::tokenField() }}
        <section>
            <input type="hidden" name="id" value="{{ item.getId() }}" />
            <header>{{ Lang.core-settings }}</header>
            <p>
                <input {% if item.getHidden() %} 'checked' {% endif %} type="checkbox" name="hidden" id="hidden"/>
                <label for="hidden">{{ Lang.galerie.make-invisible }}</label>
            </p>
            <p>
                <label for="category">
                    {{ Lang.galerie.existing-categories }} : 
                    {% for category in galerie.listCategories() %}
                        <a class="category" href="javascript:" title="{{ Lang.select_category(category) }}"><i class="fa-regular fa-folder-open"></i>{{ category }}</a>
                    {% endfor %}
                </label><br>
                <input type="text" name="category" id="category" placeholder="{{ Lang.galerie.image-category }}" value="{{ item.getCategory() }}" />
            </p>
        </section>
        <section>
            <header>{{ Lang.galerie.content }}</header>
            <p>
                <label for="title">{{ Lang.galerie.title }}</label><br>
                <input type="text" name="title" id="title" value="{{ item.getTitle() }}" required="required" />
            </p>
            <p>
                <label for="date">{{ Lang.galerie.date }}</label><br>
                <input type="date" name="date" id="date" value="{{ item.getDate() }}" /> 
            </p>
            <p>
                {{ contentEditor }}
            </p>
        </section>
        <section>
            <header>{{ Lang.galerie.image }}</header>
            <p>
                <label for="file">{{ Lang.galerie.file }} (png, jpg, jpeg, gif)</label><br>
                <input type="file" name="file" id="file" accept="image/*" {% if item.getImg() == "" %} required="required" {% endif %} />
                <br>
                {% if item.getImg() != "" %}<img src="{{ item.getUrl() }}" alt="{{ item.getImg() }}" />{% endif %}
            </p>
        </section>
        <p><button type="submit" class="button">{{ Lang.save }}</button></p>
    </form>
{% endif %}
