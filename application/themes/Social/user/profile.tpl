<div class="main-col">
	<div class="user-page {if ($u->last_action >= ($time - 900))}online{/if}">
		<div class="user-page-header">
			<div class="avatar big fll">
				{get_avatar id=$u->id}
			</div>
			<div class="user-page-info flr">
				<div class="row">
					<a href="#" class="name">{$u->fname} {$u->lname}</a>
					<div class="spacer"></div>
					<!--<abbr title="" class="time">Online</abbr>-->
				</div>
				<div class="row">
					<p class="status">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Error, mollitia porro deleniti unde deserunt illum temporibus, vitae voluptatibus dolorem ipsam est. Accusamus alias neque laudantium, placeat quod exercitationem fugit autem?</p>
				</div>
				<div class="row">
					<div class="info">
						<span>19 лет</span>
						<div class="dot"></div>
						<span>Мужской</span>
						<div class="dot"></div>
						<span>Санкт-Петербург</span>
					</div>
				</div>
				<div class="row">
					<span>2 общих друга</span>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
	<div class="wall">
		<div class="send-post-form">
			<div class="send-form-area" contenteditable="true" placeholder="Что нового?"></div>
		</div>
		<div class="wall-posts">
			{foreach from=$wall item=post}
				{include file="wall/post.tpl" post=$post}
			{/foreach}
		</div>
	</div>
</div>