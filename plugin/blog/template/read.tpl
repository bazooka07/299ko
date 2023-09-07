<article>
	<header>
		{% if pluginsManager.isActivePlugin("galerie") && galerie.searchByFileName(item.getImg) %}
			<img class="featured" src="{{ item.getImgUrl }}" alt="{{ item.getName }}"/>
		{% endif %}
		<div class="item-head">
			<p class="date">
				Posté le
				{{ util::FormatDate(item.getDate(), "en", "fr") }}
				{% if runPlugin.getConfigVal("comments") && item.getCommentsOff == false %}
					|
					{{ newsManager.countComments() }}
					commentaire{% if newsManager.countComments() > 1 %}s{% endif %}
				{% endif %}
				|
				<a href="{{ runPlugin.getPublicUrl }}">Retour à la liste</a>
			</p>
		</div>
	</header>
	{{ item.getContent }}
	{% if runPlugin.getConfigVal("displayAuthor") %}
		<footer>
			<div class='blog-author'>
				<div class='blog-avatar'>
					<img src='{{runPlugin.getConfigVal("authorAvatar")}}' alt='{{runPlugin.getConfigVal("authorName")}}'/>
				</div>
				<div class='blog-infos'>
					<div class='blog-infos-name'>
						<span>{{runPlugin.getConfigVal("authorName")}}</span>
					</div>
					<div class='blog-infos-bio'>
						{{runPlugin.getConfigVal("authorBio")}}
					</div>
				</div>
			</div>
		</footer>
	{% endif %}
</article>
{% if runPlugin.getConfigVal("comments") && item.getCommentsOff == false %}
	<section id="comments">
		<header>
			<div class="item-head">
				<h2>Commentaires</h2>
			</div>
		</header>
		{% if newsManager.countComments(item.getId) == 0 %}
			<p>Il n'y a pas de commentaires</p>
		{% else %}
			<ul class="comments-list">
				{% for k, v in newsManager.getComments() %}
					<li class="comments-item">
						<span class="infos">{{ v.getAuthor }}
							|
							{{ util::FormatDate(v.getDate(), "en", "fr") }}
						</span>
						<div class="comment" id="comment{{ v.getId }}">
							<p>{{nl2br(v.getContent())}}</p>
						</div>
					</li>
				{% endfor %}
			</ul>
		{% endif %}
		<footer>
			<h2>Ajouter un commentaire</h2>
			<form method="post" action="{{ commentSendUrl }}">
				<input type="hidden" name="id" value="{{item.getId}}"/>
				<input type="hidden" name="back" value="{{item.getUrl}}"/>
				<p>
					<label for="author">Pseudo</label><br>
					<input style="display:none;" type="text" name="_author" value=""/>
					<input type="text" name="author" id="author" required="required"/>
				</p>
				<p>
					<label for="authorEmail">Email</label><br><input type="text" name="authorEmail" id="authorEmail" required="required"/></p>
				<p>
					<label for="commentContent">Commentaire</label><br><textarea name="commentContent" id="commentContent" required="required"></textarea>
				</p>
				{% if antispam %}
					{{antispamField}}
				{% endif %}
				<p><input type="submit" value="Publier le commentaire"/></p>
			</form>
		</footer>
	</section>
{% endif %}