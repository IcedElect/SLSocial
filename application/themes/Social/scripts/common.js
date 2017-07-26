function error(){
    alert(error);
}
jQuery(document).on('submit', 'form[data-type=ajax]', function(e){
    e.preventDefault();
    var $that = jQuery(this),
    formData = new FormData($that.get(0));
    jQuery.ajax({
      url: $that.attr('action'),
      type: $that.attr('method'),
      contentType: false,
      processData: false,
      data: formData,
      dataType: 'html',
      beforeSend: function(){
          jQuery("body").addClass('loading');
          $that.find('.form_result').html('<i class="fa fa-spin fa-refresh"></i>');
      },
      success: function(data){
        console.log(data);
        jQuery("body").removeClass('loading');
        jQuery(".error").html("");
        var data = eval('('+data+')');
        if(data.response){
            if('text' in data){
                if($that.attr('data-act') == 'login')
                  window.location.href = data.text;
                $that.find('.form_result').html(data.text);
            }
        }else{
            if($that.attr('data-act') == 'login'){
              jQuery(".error").html(data.error.validation1);
            }else{
              error(data.error);
            }
        }
      }
    });
});

var jQueryj = jQuery.noConflict(); jQueryj(document).ready(function() { jQueryj("abbr.time").timeago(); });