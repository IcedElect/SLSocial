$(document).on('keypress', '.send_post #content', function(e){
    var keyCode = e.keyCode || e.charCode || e.which;
    if (keyCode == 10 || keyCode == 13){
        if (e.ctrlKey){
            var selection = window.getSelection(),
                range = selection.getRangeAt(0),
                br = document.createElement("br"),
                textNode = document.createTextNode("\u00a0");
            range.deleteContents();
            range.insertNode(br);
            range.collapse(false);
            range.insertNode(textNode);
            range.selectNodeContents(textNode);
            selection.removeAllRanges();
            selection.addRange(range);
            return false;
            }
        else
            wall.addPost($(this).attr('data-wall-id'),$(".btn[role=send]"));
        
        return false;
    }
});
function onCtrlEnter(ev, handler) {
  ev = ev || window.event;
  if (ev.keyCode == 10 || ev.keyCode == 13 && (ev.ctrlKey || ev.metaKey && browser.mac)) {
      handler();
      cancelEvent(ev);
  }
}

$(function(){
    /*$('.wall').infinitescroll({
        navSelector  : "div.nav",
        nextSelector : "div.nav a:first",
        itemSelector : ".wall .post"
    });*/

    //jQuery("abbr.timeago").timeago();
})
function wall(){
    var post_attaches = [];

    this.post_attach = function(type, id){
        var types = {'file':'doc_select'};
        post_attaches.push(type+'_'+id);
        popup.hide(types.type);
    }

    this.addPost = function(wall_id, element){
        var content = $("#content").html();
        $.ajax({
            type: 'POST',
            url: '/user/'+wall_id+'/addPost',
            data: {content: content, attach: post_attaches},
            beforeSend: function () {
                $(element).html('Загрузка...');
            },
            success: function (data){
                data = eval('('+data+')');
                if('error' in data){
                    error(data.error);
                }else{
                    if(data.result && 'post' in data){
                        $(".wall").prepend(data.post);
                        $(".send_post #content").html("");
                        $("abbr.timeago").timeago();
                        post_attaches = [];
                    }else{
                        error('Ошибка 11');
                    }
                }
            },
            error: function(){
                error('Ошибка 12');
            }
        });
        $(element).html('Опубликовать');
        return false;
    };

    this.deletePost = function(id, event){
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: '/post/'+id+'/delete',
            data: {},
            beforeSend: function () {
                
            },
            success: function (data){
                data = eval('('+data+')');
                if(data.response != false){
                    $(".post[data-id="+id+"]").html(data.response).addClass('module');
                }else{
                    if('error' in data)
                        error(data.error);
                }
            },
            error: function(){
                error('Ошибка 12');
            }
        });
        return false;
    };
    this.returnPost = function(id, event){
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: '/post/'+id+'/return',
            data: {},
            beforeSend: function () {
                
            },
            success: function (data){
                data = eval('('+data+')');
                if(data.response != false){
                    $(".post[data-id="+id+"]").replaceWith(data.response);
                    $("abbr.timeago").timeago();
                }else{
                    if('error' in data)
                        error(data.error);
                }
            },
            error: function(){
                error(12);
            }
        });

        return false;
    };
    this.like = function(id, type, event){
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: '/ajax/post/like/'+id+'/'+type,
            data: {},
            beforeSend: function () {
                
            },
            success: function (data){
                data = eval('('+data+')');
                if(data.response != false){
                    var likes = $(".post[data-id="+id+"] span.like"),
                        dislikes = $(".post[data-id="+id+"] span.dislike"),
                        rating = $(".post[data-id="+id+"] span.rating"),
                        likes_count = data.response['likes_count'],
                        rating_count = data.response['rating_count'],
                        dislikes_count = data.response['dislikes_count'];
                    rating.html(rating_count);
                    
                    if(type == 0){
                        likes.addClass('active');
                        dislikes.removeClass('active');
                    }
                    if(type == 1){
                        likes.removeClass('active');
                        dislikes.addClass('active');
                    }

                    if('remove' in data){
                        likes.removeClass('active');
                        dislikes.removeClass('active');
                    }
                }else{
                    if('error' in data)
                        error(data.error);
                }
            },
            error: function(){
                error('Ошибка 12');
            }
        });
        return false;
    };

    this.answer = 0;
    this.setAnswer = function(comment_id, post_id, e){
        this.answer = comment_id;
        this.showCommentForm(post_id, e);

        return false;
    };

    this.comment = function(id){
        var text = $(".post[data-id="+id+"] .send_form .field").html();
        var data = {text: text};
        if(this.answer)
            data.answer = this.answer;
        $.ajax({
            type: 'POST',
            url: '/post/'+id+'/comment',
            data: data,
            beforeSend: function () {
                
            },
            success: function (data){
                data = eval('('+data+')');
                if(data.response != false){
                    $(".post[data-id="+id+"] .comments").append(data.response['html']);
                    if($(".post[data-id="+id+"] .comments_count").html().length > 0)
                        $(".post[data-id="+id+"] .comments_count").html(parseInt($(".post[data-id="+id+"] .comments_count").html())+1);
                    else
                        $(".post[data-id="+id+"] .comments_count").html(1);
                    $(".post[data-id="+id+"] .send_form .field").html("");
                    $("abbr.timeago").timeago();
                }else{
                    if('error' in data)
                        error(data.error);
                }
            },
            error: function(){
                error('Ошибка 12');
            }
        });
        return false;
    };
    this.likeComment = function(id, type, event){
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: '/comment/'+id+'/like/'+type,
            data: {},
            beforeSend: function () {
                
            },
            success: function (data){
                data = eval('('+data+')');
                if(data.response != false){
                    var likes = $(".comment[data-id="+id+"] span.comment_likes_count"),
                        dislikes = $(".comment[data-id="+id+"] span.comment_dislikes_count"),
                        likes_count = data.response['likes_count'],
                        dislikes_count = data.response['dislikes_count'];

                    if(likes_count == 0) likes_count = "";
                    if(dislikes_count == 0) dislikes_count = "";
                    likes.html(likes_count);
                    dislikes.html(dislikes_count);

                    if(type == 0){
                        likes.parent().addClass('selected');
                        dislikes.parent().removeClass('selected');
                    }else{
                        likes.parent().removeClass('selected');
                        dislikes.parent().addClass('selected');
                    }
                    if('remove' in data){
                        likes.parent().removeClass('selected');
                        dislikes.parent().removeClass('selected');
                    }
                }else{
                    if('error' in data)
                        error(data.error);
                }
            },
            error: function(){
                error('Ошибка 12');
            }
        });
        return false;
    };

    this.showSendPostForm = function(e){
        e.preventDefault();
        $(".send_post .block_footer").show();
        return false;
    };
    this.hideSendPostForm = function(e){
        $('div').on('click', function(e){
            if(!$(".send_post").is(':hover')){
                $(".send_post .block_footer").hide();
            }
        })
        return false;
    };

    this.showCommentForm = function(id, e){
        e.preventDefault();
        $(".post[data-id="+id+"] .send_holder").show();
        return false;
    }
    this.showCommentFormButtons = function(id, e){
        e.preventDefault();
        $(".post[data-id="+id+"] .send_comment .buttons").show();
        return false;
    }
    this.hideCommentFormButtons = function(element, e){
        var id = $(element).attr('data-id');
        $('div').on('click', function(e){
            if(!$(".post[data-id="+id+"]").is(':hover')){
                $(".post[data-id="+id+"] .send_holder").hide();
            }
        })
        return false;
    }
}
var wall = new wall();