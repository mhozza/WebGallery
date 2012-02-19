var usernameDisplayed = true;
var usernameWidth = 0;

function showHideUsername()
{
  $("#username").animate({'width':'toggle', 'padding-left':'toggle', 'padding-right':'toggle'},400, function(){
  if(usernameDisplayed)
  {
    $("#toggle_user img").attr('src','images/toolbar/show.png');
  }
  else
  {
    $("#toggle_user img").attr('src','images/toolbar/hide.png');
  }
  usernameDisplayed = !usernameDisplayed;
  });
}

$(document).ready(function() {

  if($("#username").length>0)
  {
    $("#username > div").css('width',$("#username").width());
    var toggle_user_button = '<a href="#" id="toggle_user" class="userbar_item"><div><img src="images/toolbar/hide.png" alt="hide"/></div></a>';
    $("#userbar").prepend(toggle_user_button);
    $("#toggle_user").click(function() {
      showHideUsername();
    });

    timeout = window.setTimeout('showHideUsername()',1000);
  }
});