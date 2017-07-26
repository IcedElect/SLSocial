<div class="users-list">
	<div class="simple-scrollbar">
		<ul>
			{if $users|@count}
			<li>
				<div class="tree-header">
					<div class="icon icon-down-dir tree-more"></div>
					<span>Люди ({$users|@count})</span>
				</div>
				<ul class="list">
					{foreach from=$users item=$user}
						{include file="user/item.tpl" user=$user}
					{/foreach}
				</ul>
			</li>
			{/if}
		</ul>
	</div>
</div>