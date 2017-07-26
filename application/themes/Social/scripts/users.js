function users(){
    this.sendReq = function(id, element){
        var text = $(element).html();
        $.ajax({
            type: 'POST',
            url: '/ajax/user/send_req/'+id,
            data: '',
            beforeSend: function () {
                $(element).html('<i class="icon fa fa-circle-o-notch fa-spin fa-3x"></i>Загрузка...');
            },
            success: function (data){
                data = eval('('+data+')');
                if('error' in data){
                    //$(element).replaceWith(data.html);
                    error(data.error);
                }else{
                    //if('text' in data && data.result){
                        //$(element).replaceWith(data.html);
                        //$(element).attr('onclick', data.onclick);
                    //}else{
                        //$(element).html(text);
                        //error('Ошибка 11');
                    //}
                }
                $(element).replaceWith(data.html);
            }
        });
    }

    this.reqCancel = function(id, element){
        var text = $(element).html();
        $.ajax({
            type: 'POST',
            url: '/ajax/user/req_cancel/'+id,
            data: '',
            beforeSend: function () {
                $(element).html('<i class="icon fa fa-circle-o-notch fa-spin fa-3x"></i>Загрузка...');
            },
            success: function (data){
                data = eval('('+data+')');
                if('error' in data){
                    //$(element).html(text);
                    error(data.error);
                }else{
                    //if('text' in data && data.result){
                    //    $(element).attr('onclick', data.onclick);
                    //    $(element).html(data.text);
                    //}else{
                    //    $(element).html(text);
                    //    error('Ошибка 11');
                    //}
                }
                $(element).replaceWith(data.html);
            },
            error: function(){
                error('Ошибка 12');
            }
        });
    }

    this.delFriend = function(id, element, type){
        var text = $(element).html();
        popup.showSelect('confirm/delFriend', {}, function(res){
            popup.remove('confirm/delFriend_select');
            if(res == 'no') return;
            $.ajax({
                type: 'POST',
                url: '/ajax/user/del_friend/'+id,
                data: '',
                beforeSend: function () {
                    if(type)
                        $(element).html('<i class="icon fa fa-circle-o-notch fa-spin fa-3x"></i>Загрузка...');
                },
                success: function (data){
                    data = eval('('+data+')');
                    if('error' in data){
                        //$(element).html(text);
                        error(data.error);
                    }else{
                        //if('text' in data && data.result){
                        //    if(type){
                        //        $(element).attr('onclick', data.onclick);
                        //        $(element).html(data.text);
                        //    }else{
                                load_sidebar('friends');
                        //    }
                        //}else{
                            //$(element).html(text);
                        //    error('Ошибка 11');
                        //}
                        $(element).replaceWith(data.html);
                    }
                },
                error: function(){
                    error('Ошибка 12');
                }
            });
        })
    }

    this.reqOk = function(id, element){
        $.ajax({
            type: 'POST',
            url: '/ajax/user/req_ok/'+id,
            data: '',
            beforeSend: function () {
                $(element).html('<i class="icon fa fa-circle-o-notch fa-spin fa-3x"></i>Загрузка...');
            },
            success: function (data){
                data = eval('('+data+')');
                if('error' in data){
                    error(data.error);
                }else{
                    //if(data.result){
                        load_sidebar('friends');
                        var span = $("span.notifications_friends")
                        if(parseInt(span.html()) > 1){
                            span.html(parseInt(span.html())-1);
                        }else{
                            span.html("");
                        }
                        $(element).replaceWith(data.html);
                    //}else{
                    //    error('Ошибка 11');
                    //}
                }

            }
        });
    }

    this.reqNo = function(id, element){
        $.ajax({
            type: 'POST',
            url: '/ajax/user/req_no/'+id,
            data: '',
            beforeSend: function () {
                $(element).html('<i class="icon fa fa-circle-o-notch fa-spin fa-3x"></i>Загрузка...');
            },
            success: function (data){
                data = eval('('+data+')');
                if('error' in data){
                    error(data.error);
                }else{
                    //if(data.result){
                        $(element).replaceWith(data.html);
                        var span = $("span.notifications_friends")
                        if(parseInt(span.html()) > 1){
                            span.html(parseInt(span.html())-1);
                        }else{
                            span.html("");
                        }
                        load_sidebar('friends');
                    //}else{
                    //    error('Ошибка 11');
                    //}
                }
            }
        });
    }

    this.subscribe = function(id, element){
        var text = $(element).html();
        $.ajax({
            type: 'POST',
            url: '/ajax/user/subscribe/'+id,
            data: '',
            beforeSend: function () {
                $(element).html('<i class="icon fa fa-circle-o-notch fa-spin fa-3x"></i>Загрузка...');
            },
            success: function (data){
                data = eval('('+data+')');
                if('error' in data){
                    //$(element).html(text);
                    error(data.error);
                }else{
                    //if('text' in data && data.result){
                    //    $(element).html(data.text);
                    //    $(element).attr('onclick', data.onclick);
                    //}else{
                    //    $(element).html(text);
                    //    error('Ошибка 11');
                    //}
                    $(element).replaceWith(data.html);
                }
            }
        });
    }
    this.unsubscribe = function(id, element){
        var text = $(element).html();
        $.ajax({
            type: 'POST',
            url: '/ajax/user/unsubscribe/'+id,
            data: '',
            beforeSend: function () {
                $(element).html('<i class="icon fa fa-circle-o-notch fa-spin fa-3x"></i>Загрузка...');
            },
            success: function (data){
                data = eval('('+data+')');
                if('error' in data){
                    //$(element).html(text);
                    error(data.error);
                }else{
                    //if('text' in data && data.result){
                    //    $(element).html(data.text);
                    //    $(element).attr('onclick', data.onclick);
                    //}else{
                    //    $(element).html(text);
                    //    error('Ошибка 11');
                    //}
                    $(element).replaceWith(data.html);
                }
            }
        });
    }
}
var Users = new users();