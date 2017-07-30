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
            <div class="action like">
                {* {if true}  == is_liked    *}
                {* {if false} == is_disliked *}
                <span class="like circle green {if true}active{/if}"><i class="icon-angle-up"></i></span>&nbsp;
                {if $post->likes_count > 0}
                    <span class="small circle {if true}green active{/if} {if false}active{/if}">{$post->likes_count}</span>&nbsp;
                {/if}
                <span class="dislike circle {if false}active{/if}"><i class="icon-angle-down"></i></span>
            </div>
            <div class="action share {if false}active{/if}">
                <!-- {* {if $post->is_shared} *} -->
                <i class="circle icon-forward-outline"></i>
                <!-- {* {if $post->share_count > 0} must be inserted instead of {if false} *} -->
                {if false}
                    <span class="small circle">{* {$post->share_count} *}</span>
                {/if}
                <span></span>
            </div>
            <div class="action comments flr">
                {if $post->comments_count > 0}
                    <span class="small circle">{$post->comments_count}</span>&nbsp;
                {/if}
                <i class="comment circle icon-comment-2"></i>
            </div>
        </div>
        {get_comments id={$post->id}}
        {foreach from=$comments item=$comment}
            {$comment->text}
        {/foreach}
    </div>
</div>
