function change_country(country){
    $("select#city option").hide().removeAttr('selected');
    $("select#city option[data-country="+country+"]").show();
    $("select#city option[data-country="+country+"]:first").attr('selected', 'true');
}
function change_birth(month, year){
    var count = 32 - new Date(year, month - 1, 32).getDate();
    $("select#birthday option").show();
    if(count < 31){
        $("select#birthday option[value=31]").hide().removeAttr('selected');
        if(count < 30){
            $("select#birthday option[value=30]").hide().removeAttr('selected');
            if(count < 29){
                $("select#birthday option[value=29]").hide().removeAttr('selected');
            }
        }
    }
}
function change_status(e){
    e.preventDefault();
    $(".status").hide();
    $("#status").val($('.status span').html());
    $("form.status_form").show();
};
function save_status(e){
    e.preventDefault();
    var status = $("#status").val()
    $.post('', {status: status}, function(data){
        $(".status").show();
        $('.status span').html(status);
        $("form.status_form").hide();
    });

    return false;
};

function change_about(e){
    e.preventDefault();
    $("a[role=change_about]").hide();
    $("a[role=save_about]").show();
    $("p.user_about").html('<div contenteditable="true" id="about_field">'+$("p.user_about").html()+'</div>');
};
function save_about(e){
    e.preventDefault();
    var about = $("p.user_about #about_field").html();
    $.post('', {about: about}, function(data){
        $("a[role=change_about]").show();
        $("a[role=save_about]").hide();
        $("p.user_about").html(about);
    });
};
function change_info(e){
    e.preventDefault();
    $(".block.edit_info").show();
};
function close_info(e){
    e.preventDefault();
    $(".block.edit_info").hide();
    
};

$(function(){

    $(document).on('click', ".edit_info ul.edit_info_menu li a", function(e){
        e.preventDefault();
        var tab = $(this).parent().attr('data-tab');
        $("td.tab").hide();
        $("td.tab[data-tab="+tab+"]").show();
        $(".edit_info ul.edit_info_menu li").removeClass("selected");
        $(this).parent().addClass("selected");
    });

    $(document).on('change', "select#country", function(e){
        var country = $(this).val();
        change_country(country);
    });

    $(document).on('change', "select#birthmonth", function(e){
        var month = $(this).val();
        var year = $("select#birthyear").val();
        change_birth(month, year);
    });
    $(document).on('change', "select#birthyear", function(e){
        var month = $("select#birthmonth").val();
        var year = $(this).val();
        change_birth(month, year);
    });
});