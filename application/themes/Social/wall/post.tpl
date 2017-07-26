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
        <div style="border: 1px solid black; padding: 0px 0px">
            <span>
                <i class="demo-icon icon-heart like {if $post->is_liked}red{/if}"></i>
                {$post->likes_count}
            </span>
            <span>
                <!-- {* {if $post->is_shared} *} -->
                <i class="demo-icon {if true}icon-bullhorn{else}icon-megaphone{/if} share {if true}red{/if}"></i>
                <!-- {* {$post->share_count}  *} -->
            </span>
            <span class="flr">
                <span class="comment">{$post->comments_count}</span>
            <i class="demo-icon icon-comment-2"></i>
            </span>
        </div>
    </div>
</div>
