<div class="users-list">
	<div class="simple-scrollbar">
		<ul>
			{if $friends_req}
			<li>
				<div class="tree-header">
					<div class="icon icon-down-dir tree-more"></div>
					<span>Заявки в друзья</span>
				</div>
				<ul class="list">
					{foreach from=$friends_req item=$friend_r}
						{include file="user/item.tpl" user=$friend_r}
					{/foreach}
				</ul>
			</li>
			{/if}
			{if $friends_online}
			<li>
				<div class="tree-header">
					<div class="icon icon-down-dir tree-more"></div>
					<span>Друзья онлайн</span>
				</div>
				<ul class="list">
					{foreach from=$friends_online item=$friend_o}
						{include file="user/item.tpl" user=$friend_o}
					{/foreach}
				</ul>
			</li>
			{/if}
			<li>
				<div class="tree-header">
					<div class="icon icon-down-dir tree-more"></div>
					<span>Все друзья</span>
				</div>
				<ul class="list">
					{foreach from=$friends item=$friend}
						{include file="user/item.tpl" user=$friend}
					{/foreach}
				</ul>
			</li>
		</ul>
	</div>
</div>