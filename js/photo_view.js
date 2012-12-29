var imgWidth, imgHeight;

function resizeWindow()       
{
  if($("#comments").hasClass("hidden"))
  {
    commentsWidth = 0;
  } 
  else 
  {
    commentsWidth = parseFloat($("#comments").outerWidth(true));
  }

  windowWidth = parseFloat($(window).innerWidth());
  mainSpaceLeft = parseFloat($("#photo_view").css("margin-left"))+parseFloat($("#photo_view").css("border-left-width"));
  mainSpaceRight = parseFloat($("#photo_view").css("margin-right"))+parseFloat($("#photo_view").css("border-right-width"))+commentsWidth;
  
  windowHeight = parseFloat($(window).innerHeight());
  mainSpaceTop = parseFloat($("#photo_view").css("margin-top"))+parseFloat($("#photo_view").css("border-top-width"));
  mainSpaceBottom = parseFloat($("#photo_view").css("margin-bottom"))+parseFloat($("#photo_view").css("border-bottom-width"));

  commentsSpaceTop = parseFloat($("#comments").css("margin-top"))+parseFloat($("#comments").css("border-top-width"));
  commentsSpaceBottom = parseFloat($("#comments").css("margin-bottom"))+parseFloat($("#comments").css("border-bottom-width"));

  mainWidth = windowWidth-mainSpaceLeft-mainSpaceRight;
  mainHeight = windowHeight-mainSpaceTop-mainSpaceBottom;
  commentsHeight = windowHeight-commentsSpaceTop-commentsSpaceBottom;

  $("#photo_view").innerWidth(mainWidth);
  $("#photo_view").innerHeight(mainHeight);
  $("#comments").innerHeight(commentsHeight);

  $("#photo_view").css({'left': 0, 'top':0});  

  resizeImage();

  //Buttons
  closeW = parseFloat($("#photo_view_close_button").width());
  closeH = parseFloat($("#photo_view_close_button").height());

  $("#photo_view_close_button").css({'right':mainSpaceRight-closeW/2, 'top':mainSpaceTop-closeH/2});

  if($("#comments").hasClass("hidden"))
  {
    showCommentsLeft = $("#photo_view_close_button").position().left;
    showCommentsTop = $("#photo_view_close_button").position().top+$("#photo_view_close_button").height()+8;
    $("#show_comments_button").css({'left':showCommentsLeft, 'top':showCommentsTop});
  }
}

function resizeImage()
{
  zoom = Math.max(imgHeight/parseFloat($("#photo_view").height()), imgWidth/parseFloat($("#photo_view").width()));  
  
  $("#image_wrapper img").height(imgHeight/zoom);  
  $("#image_wrapper img").width(imgWidth/zoom);    

  $("#image_wrapper").height(imgHeight/zoom);  
  $("#image_wrapper").width(imgWidth/zoom);    

  imgLeft = (parseFloat($("#photo_view").width()) - imgWidth/zoom)/2;
  imgTop = (parseFloat($("#photo_view").height()) - imgHeight/zoom)/2;
  $("#image_wrapper").css({'margin-left': imgLeft, 'margin-top':imgTop});  
}

$(window).resize(function(){resizeWindow();});

$(document).ready(function(){openWindow();});

function openWindow()
{
  html = '\
  <div id="apaloosa_gallery_view_wrapper">\
  <div id="shadow"></div>\
  <div id="photo_view">\
    <div id="image_wrapper">\
    <img src="gallery/cesta_do_neznama_wallpaper.jpg"/>\
    <div id="photo_title"><strong class="photo_title">Cesta do neznáma</strong><br/><span class="photo_subtitle">by Michal Hozza</span></div>\
    </div>\
  </div>\
  <div id="comments"><strong>Komentáre</strong> <a id="hide_comments_button" title="skryť" class="pull-right" href="javascript:hideComments()">&gt;&gt;</a><hr class="divider"/></div>\
  <a id="photo_view_close_button" class="button" href="javascript:closeWindow()"><i class="icon-remove icon-white"></i></a>\
  </div>\
  ';

  $("body").append(html);

  $("#hide_comments_button").tooltip({'placement':'left'});

  //get ImgSize
  $("<img/>") // Make in memory copy of image to avoid css issues
    .attr("src", $("#photo_view img").attr("src"))
    .load(function() {
        imgWidth = this.width;   // Note: $(this).width() will not
        imgHeight = this.height; // work for in memory images.
        resizeWindow();
    });

  resizeWindow();
}

function closeWindow()
{
  $("#apaloosa_gallery_view_wrapper").remove();  
}

function hideComments()
{
  $("#comments").addClass("hidden");
  html='<a id="show_comments_button" class="button" href="javascript:showComments()"><i class="icon-comment icon-white"></i></a>';
  $("#apaloosa_gallery_view_wrapper").append(html);
  resizeWindow();  
  
}

function showComments()
{
  $("#show_comments_button").remove();
  $("#comments").removeClass("hidden");
  resizeWindow();
}