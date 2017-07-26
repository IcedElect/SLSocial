<li class="user-item">
	<a href="/@{$user->login}" class="avatar middle">
		{get_avatar id=$user->id}
	</a>
	<a href="/@{$user->login}" class="user {if ($user->last_action >= ($time - 900))}online{/if}">
		<span class="name">{$user->fname} {$user->lname}</span>
	</a>
	<div class="user-button">
		<button class="icon icon-dot-3" data-type="actions" data-user="{$user->id}"></button>
		<ul class="actions-menu user-menu">
			<li class="loading"><a><i class="fa fa-circle-o-notch fa-spin"></i></a></li>
		</ul>
	</div>
</li>