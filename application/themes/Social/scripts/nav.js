function nav(){
    var self = {};
    self.load = function(url, e, push){

        console.log(e);
        
        e.preventDefault();

        var element = document.querySelector('.main>.table>.row');
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                var data = eval('('+xhr.responseText+')');
                
                element.innerHTML = data.html;
                jQuery("abbr.timeago").timeago();

                document.title = data.title;

                if (push)
                    history.pushState(url, document.title, url);

                document.body.classList.remove('loading');
                popup.init();
                if($(".messages").length > 0){
                    messages.init();
                }
            }
        };

        xhr.open('get', url, true);
        xhr.setRequestHeader('Content-Only', 1);
        xhr.send();

        document.body.classList.add('loading');
        return false;
    }
    self.init = function(){
        self.main = document.querySelector('.main>.table>.row');
        window.addEventListener('popstate', function (e) {
            self.load(e.state, e, false);
        });

        history.replaceState(location.href, document.title, location.href);
    }
    return self;
}
var nav = new nav();
nav.init();
$(document).on('click', '[data-type=load]', function(e){
    
    nav.load($(this).attr('href'), e, true);

    return false;
})

$(document).on('click', '[data-type=load_sidebar]', function(e){
    load_sidebar($(this).attr('data-id'));
    return false;
})

function error(text){
    alert(text);
}

function load_sidebar_p(id, params){
    $(".app-content").addClass('loading');
    $.post('/ajax/user/sidebar/'+id, params, function(data){
        $(".app-content .tab-content").html(data);
        $(".app-content").removeClass('loading');
    });
}
function load_sidebar(id){
    $(".app-content").addClass('loading');
    $.post('/ajax/user/sidebar/'+id, function(data){
        $(".app-content .tab-content").html(data);
        $(".app-bar a.app-bar-link.selected").removeClass("selected");
        $(".app-bar a.app-bar-link[data-id="+id+"]").addClass("selected");
        $(".app-content").removeClass('loading');
    });
    setTimeout(function(){
        sidebar = id;
    }, 200);
}

$(document).on('keyup', '.search .search-field', function(e){
    e.preventDefault();
    if(e.keyCode == 9 || e.keyCode == 16 || e.keyCode == 17 || e.keyCode == 18 || e.keyCode == 20 || e.keyCode == 91) return;
    var search = $(this).val();
    if(search.length == 0){
        load_sidebar(sidebar);
        return false;
    }
    load_sidebar_p('search', {search: search});
})

function get(parameterName) {
    var result = null,
        tmp = [];
    location.search
    .substr(1)
        .split("&")
        .forEach(function (item) {
        tmp = item.split("=");
        if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
    });
    return result;
}
//navigator.geolocation.getCurrentPosition(showPosition); // Запрашиваем местоположение, и в случае успеха вызываем функцию showPosition
function showPosition(position) {
  /* Выводим координаты */
 // console.log("Широта: " + position.coords.latitude + "<br />");
  //console.log("Долгота: " + position.coords.longitude); 

  $//.post('/user/setPos', {x: position.coords.latitude, y: position.coords.longitude}, function(data){console.log(data);});
}
function setGet(paramName, paramValue)
{
    var url = window.location.href;
    var hash = location.hash;
    url = url.replace(hash, '');
    if (url.indexOf(paramName + "=") >= 0)
    {
        var prefix = url.substring(0, url.indexOf(paramName));
        var suffix = url.substring(url.indexOf(paramName));
        suffix = suffix.substring(suffix.indexOf("=") + 1);
        suffix = (suffix.indexOf("&") >= 0) ? suffix.substring(suffix.indexOf("&")) : "";
        url = prefix + paramName + "=" + paramValue + suffix;
    }
    else
    {
    if (url.indexOf("?") < 0)
        url += "?" + paramName + "=" + paramValue;
    else
        url += "&" + paramName + "=" + paramValue;
    }
    return url + hash;
}
function delGet2(key) {
    var sourceURL = window.location.href;
    var rtn = sourceURL.split("?")[0],
        param,
        params_arr = [],
        queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
    if (queryString !== "") {
        params_arr = queryString.split("&");
        for (var i = params_arr.length - 1; i >= 0; i -= 1) {
            param = params_arr[i].split("=")[0];
            if (param === key) {
                params_arr.splice(i, 1);
            }
        }
        rtn = rtn + "?" + params_arr.join("&");
    }
    return rtn;
}
function delGet(parameter) {
    var url = window.location.href;
    var urlparts= url.split('?');   
    if (urlparts.length>=2) {

        var prefix= encodeURIComponent(parameter)+'=';
        var pars= urlparts[1].split(/[&;]/g);

        for (var i= pars.length; i-- > 0;) {    
            if (pars[i].lastIndexOf(prefix, 0) !== -1) {  
                pars.splice(i, 1);
            }
        }

        url= urlparts[0] + (pars.length > 0 ? '?' + pars.join('&') : "");
        return url;
    } else {
        return url;
    }
}