<div class="post-holder">
	<div class="post">
		<div class="post-header">
			<a href="#" class="user-avatar avatar middle">
				{get_avatar id=$post->author_id}
			</a>
			<div class="info-holder">
			<a href="#" class="name">{$post->fname} {$post->lname}</a>
			<abbr title='{$post->date|date_format:"%Y-%m-%d %H:%M:%S"}' class="time"></abbr>
			</div>
			<div class="spacer"></div>
			<div class="actions">
				<button class="icon fa icon-dot-3"></button>
			</div>
		</div>
		<div class="post-content">
			{$post->content}
		</div>
	</div>
</div>