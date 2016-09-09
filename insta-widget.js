var access_token = document.location.hash;
document.location.hash = "";
var pos = access_token.indexOf("=");
var token = access_token.slice(pos).slice(1);
var user_id = token.slice(0, token.indexOf('.'));
var widget = jQuery('.insta-inputs');

jQuery(document).ready(function($) {
  if(access_token !== ''){
    widget.each(function(idx, vlx){
      if(idx !== 0){
        var intern = $(this);
        var inputsIntern = intern.parents('.widget-content').children('p:first').children('[name^=widget-instawidget]');

        if(intern.children('.insta-login')) {
          inputsIntern.each(function(id, vl){
            if($(this).attr('id').indexOf('token') > 0){
              $(this).val(token);
            } else if($(this).attr('id').indexOf('user') > 0){
              $(this).val(user_id);
            }
          });
          intern.remove();
        }
      }
    });
  }

  $('.insta-remove').on('click', function(){
    var inputsIntern = $(this).parents('.widget-content').children('p:first').children('[name^=widget-instawidget]');
    var btnLogin = document.createElement("A");
    btnLogin = $(btnLogin);
    btnLogin.attr('href', $('#loginUrl').val()).addClass('insta-login').html("Login");

    inputsIntern.each(function(idx, vlx){
      if($(this).attr('id').indexOf('token') > 0){
        $(this).val("");
      } else if($(this).attr('id').indexOf('user') > 0){
        $(this).val("");
      }
    });

    $(this).parents('.widget-content').children('p.insta-inputs').html("").append(btnLogin);

    $(this).remove();
  });
});
