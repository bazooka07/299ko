{% if mode === "list_empty" %}
	<p>Aucun élément n'a été trouvé.</p>
{% else %}
	{% for k, v in news %}
		<article>
			{% if runPlugin.getConfigVal("hideContent") == false %}
				<header>
					{% if pluginsManager.isActivePlugin("galerie") && galerie.searchByFileName(v.img) %}
						<img class="featured" src="{{v.imgUrl}}" alt="{{v.img}}"/>
					{% endif %}
					<div class="item-head">
						<h2>
							<a href="{{v.url}}">{{v.name}}</a>
						</h2>
						<p class="date">{{v.date}}
							{% if runPlugin.getConfigVal("comments") && v.commentsOff == false %}
								|
								{{ newsManager.countComments(v.id) }}
								commentaire{% if newsManager.countComments(v.id) > 1 %}s{% endif %}
							{% endif %}
						</p>
					</div>
				</header>
				{% if v.intro %}
					{{v.intro}}
				{% else %}
					{{v.content}}
				{% endif %}
			{% else %}
				<h2>
					<a href="{{v.url}}">{{v.name}}</a>
				</h2>
				<p class="date">{{v.date}}
					{% if runPlugin.getConfigVal("comments") && v.commentsOff == false %}
						|
						{{ newsManager.countComments(v.id) }}
						commentaire{% if newsManager.countComments(v.id) > 1 %}s{% endif %}
					{% endif %}
				</p>
			{% endif %}
		</article>
	{% endfor %}
	<ul class="pagination">
		{% for k, v in pagination %}
			<li>
				<a href="{{v.url}}">{{v.num}}</a>
			</li>
		{% endfor %}
	</ul>
{% endif %}
