<div class="post-holder">
    <div class="post" data-id="{$post->id}">
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
            <div class="action">
                {* {if true}  == is_liked    *}
                {* {if false} == is_disliked *}
                <span class="like green circle {if $post->is_liked}active{/if}" onclick="wall.like({$post->id}, 0, event)">
                    <i class="icon-angle-up"></i>
                </span>
                
                <span class="rating small {if false}active{/if}">
                    {if ($post->likes_count > 0 or $post->dislikes_count > 0) }{($post->likes_count - $post->dislikes_count)}{/if}
                </span>
                
                <span class="dislike circle {if $post->is_disliked}active{/if}" onclick="wall.like({$post->id}, 1, event)">
                    <i class="icon-angle-down"></i>
                </span>
            </div>
            <div class="action share {if false}active{/if}">
                <!-- {* {if $post->is_shared} *} -->
                <i class="circle icon-forward-outline"></i>
                <!-- {* {if $post->share_count > 0} must be inserted instead of {if false} *} -->
                {if false}
                    <span class="small">{* {$post->share_count} *}</span>
                {/if}
                <span></span>
            </div>
            <div class="spacer"></div>
            <div class="action comment">
                {if $post->comments_count > 0}
                    <span class="small">{$post->comments_count}</span>
                {/if}
                <i class="comment circle icon-comment-2"></i>
            </div>
        </div>
        {get_comments id={$post->id}}
        {foreach from=$comments item=$comment}
            {*{$comment->text}*}
        {/foreach}






        <!--

        <div class="send-post-form send-comment">
                <div class="avatar middle">
                    {get_avatar id=$oUser->id}
                </div>
                <div onkeypress="onCtrlEnter(event, this);" class="send-form-area {if !$oUser->id}login-button{/if}" contenteditable="true" placeholder="Написать комментарий"></div>
                <div class="buttons">
                    <button class="fl-r send-button icon icon-paper-plane"></button>
                </div>
            </div>
            <div class="comments-list">
                {foreach from=$comments key=$c item=$comment}
                <div class="comment">
                    <div class="avatar middle">
                        {get_avatar id=$comment->author_id}
                    </div>
                    <div class="content">
                        <div class="info">
                            <a href="#" class="name">{$comment->fname} {$comment->lname}</a>
                            <span><abbr title="{$comment->date|date_format:"%Y-%m-%d %H:%I:%S"}" class="time"></abbr></span>
                            <span class="spacer"></span>
                            <div class="actions">
                                <button class="icon fa icon-dot-3"></button>
                            </div>
                        </div>
                        <div class="text">
                            {$comment->text}
                        </div>
                    </div>
                </div>
                {/foreach}
            </div>
        -->




    </div>
</div>
