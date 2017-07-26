function popup(){
    this.init = function(){
        if(get('photo') !== null){
            this.show('photo', {photo: get('photo')});
        } else if(get('album') !== null){
            this.show('album', {album: get('album')});
        } else if(get('albums') !== null){
            this.show('albums', {user: get('albums')});
        } else {
            this.hide();
        }
    }

    this.show = function(id){
        if($(".popup[data-id='"+id+"']").length > 0){
            $("body").css('overflow', 'hidden');
            $(".popup_layer").addClass('show');

            $(".popup[data-id='"+id+"']").addClass('show');
        }else{
            $.post('/popup/'+id, function(data){
                
                data = eval('('+data+')');
                if(data.response['html']){
                    $(".popup_layer").html(data.response['html']);
                    $("body").css('overflow', 'hidden');
                    $(".popup_layer").addClass('show');
                    $(".popup[data-id='"+id+"']").addClass('show');
                }
            })
        }
    }
    this.show = function(id, params){
        if($(".popup[data-id='"+id+"']").length > 0){
            $("body").css('overflow', 'hidden');
            $(".popup_layer").addClass('show');

            $(".popup[data-id='"+id+"']").addClass('show');
        }else{
            $.post('/popup/'+id, params, function(data){
                data = eval('('+data+')');
                if(data.response['html']){
                    $(".popup_layer").html(data.response['html']);
                    $("body").css('overflow', 'hidden');
                    $(".popup_layer").addClass('show');
                    $(".popup[data-id='"+id+"']").addClass('show');
                }
            })
        }
    }

    var e = false;

    this.showSelect = function(id, data, callback){
        $(document).unbind(".popup[data-id='"+id+"_select'] a.select");
        $(".popup[data-id='"+id+"_select'] a.select").off('click');
        if($(".popup[data-id='"+id+"']").length > 0){
            $("body").css('overflow', 'hidden');
            $(".popup_layer").addClass('show');

            $(".popup[data-id='"+id+"']").addClass('show');
        }else{
            $.post('/ajax/popup/'+id, data, function(data){
                console.log(data);
                data = eval('('+data+')');

                if(data.data){
                    $(".popup_layer").html(data.data);
                    $("body").css('overflow', 'hidden');
                    $(".popup_layer").addClass('show');
                    $(".popup[data-id='"+id+"_select']").addClass('show');

                    e = $(".popup[data-id='"+id+"_select'] a.select").bind('click', function(){callback($(this).attr('data-id'));});
                }
            })
        }
    }

    this.hide = function(id){
        $("body").css('overflow', 'auto');
        $(".popup_layer").removeClass('show');

        $(".popup[data-id='"+id+"']").removeClass('show');
    }
    this.hide = function(){
        $("body").css('overflow', 'auto');
        $(".popup_layer").removeClass('show');

        $(".popup").removeClass('show');
    }

    this.scroll = function(){
        if($(".popup_layer.show").scrollTop() > 65){
            $(".popup .popup_header").addClass('header_fixed');
        }else{
            $(".popup .popup_header").removeClass('header_fixed');
        }
    }

    this.remove = function(id){
        this.hide(id);
        $(document).unbind(".popup[data-id='"+id+"_select'] a.select");
        $(".popup[data-id='"+id+"']").replaceWith("");
    }
}
var popup = new popup();
popup.init();