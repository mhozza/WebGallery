var usernameDisplayed = true;
var usernameWidth = 0;
var hoverShow = false;

function showHideUsername()
{
  // $("#username").animate({'width':'toggle', 'padding-left':'toggle', 'padding-right':'toggle'},400, function(){
  //   usernameDisplayed = !usernameDisplayed;
  // });
}

$(document).ready(function() {

  // if($("#username").length>0)
  // {
  //   $("#username > div").css('width',$("#username").width());
  //   var toggle_user_button = '<div id="toggle_user" class="userbar_item btn"><img src="images/toolbar/user.png" alt="user"/></div>';
  //   $("#userbar").prepend(toggle_user_button);
  //   $("#toggle_user").click(function() {
  //     showHideUsername();
  //   });
  //   $("#userbar").hover(function() {
  //     if(!usernameDisplayed)
  //     {        
  //       showHideUsername();
  //       hoverShow = true;
  //     }      
  //   },function() {
  //     if(usernameDisplayed && hoverShow)
  //     {
  //       showHideUsername();
  //       hoverShow = false;
  //     }
  //   });
  //   timeout = window.setTimeout('showHideUsername()',1000);
  }

  //add album
  $('#add_album').attr('href','JavaScript:void(0);');
  $('#album_dialog').dialog({
    autoOpen: false,
    modal: true,
    //resizable: false,
    buttons: {
      "Add": function() {
        $( this ).dialog( "close" );        
      },
      Cancel: function() {
        $( this ).dialog( "close" );
      }
    },
    close: function() {
      //allFields.val( "" ).removeClass( "ui-state-error" );
    }
  });
      
  $('#add_album').click(function(){
    $( "#album_dialog" ).dialog( "open" );
  });

  //add photos
  $('#add_photos').attr('href','JavaScript:void(0);');
  $('#photos_dialog').dialog({
    autoOpen: false,
    modal: true,
    //resizable: false,
    buttons: {
      "Add": function() {
        $( this ).dialog( "close" );
      },
      Cancel: function() {
        $( this ).dialog( "close" );
      }
    },
    close: function() {
      //allFields.val( "" ).removeClass( "ui-state-error" );
    }
  });
  
  $('#add_photos').click(function(){
    $( "#photos_dialog" ).dialog( "open" );
  });

  //todo: upload interface
  
  $('.fileinput-button').button();  
  $('#fileupload').fileupload({
    dataType: 'json',
    url: 'server/php/',
    dropZone: $('photosDialog'),
    done: function (e, data) {
      $.each(data.result, function (index, file) {
        $('<p style="background:green;"/>').text(file.name).appendTo($('#photos_dialog'));
      });
    },
    add: function (e, data) {
      $.each(data.files, function (index, file) {
        $('<p/>').text(file.name).appendTo($('#photos_dialog'));
      });
      data.submit();
    }    
  });
  
});