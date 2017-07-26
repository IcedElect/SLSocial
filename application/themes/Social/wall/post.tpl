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
        <div class="actions">
            <div class="action like {if $post->is_liked}active{/if}">
                <i class="demo-icon icon-heart"></i>
                <span>{$post->likes_count}</span>
            </div>
            <div class="action share {if true}active{/if}">
                <!-- {* {if $post->is_shared} *} -->
                <i class="demo-icon icon-megaphone"></i>
                <!-- {* {$post->share_count}  *} -->
                <span></span>
            </div>
            <div class="action comments flr">
                {if $post->comments_count > 0}<span>{$post->comments_count}</span>{/if}
                <i class="demo-icon icon-comment-2"></i>
            </div>
        </div>
        {get_comments id={$post->id}}
        {foreach from=$comments item=$comment}
            {$comment->text}
        {/foreach}
    </div>
</div>
