var imgWidth, imgHeight, titleTimeout;

var mainSpaceLeft, mainSpaceBottom;

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

  resizeImage();

  //nav
  $(".photo_nav").height($("#prev_photo img").height());  
  $(".photo_nav").css({'padding':(mainHeight-$("#prev_photo img").height())/2 + 'px 4px'});  
  $("#prev_photo").css({'left':mainSpaceLeft});
  $("#next_photo").css({'right':mainSpaceRight});

  //Buttons
  closeW = parseFloat($("#photo_view_close_button").outerWidth(true));
  closeH = parseFloat($("#photo_view_close_button").outerHeight(true));

  $("#photo_view_close_button").css({'right':mainSpaceRight-closeW/2, 'top':mainSpaceTop-closeH/2});

  if($("#comments").hasClass("hidden"))
  {
    showCommentsLeft = $("#photo_view_close_button").position().left;
    showCommentsTop = $("#photo_view_close_button").position().top+$("#photo_view_close_button").height()+8;//TODO: zrusit magicku konstantu
    $("#show_comments_button").css({'left':showCommentsLeft, 'top':showCommentsTop});
  }
}

function resizeImage()
{
  zoom = Math.max(imgHeight/parseFloat($("#photo_view").height()), imgWidth/parseFloat($("#photo_view").width()));  
  
  $("#photo_view img").height(imgHeight/zoom);  
  $("#photo_view img").width(imgWidth/zoom);  

  $("#photo_title").width($("#photo_view img").width() 
     - parseFloat($("#photo_title").css('margin-left')) 
     - parseFloat($("#photo_title").css('margin-right'))
     - parseFloat($("#photo_title").css('padding-left')) 
     - parseFloat($("#photo_title").css('padding-right'))
     - parseFloat($("#photo_title").css('border-left-width')) 
     - parseFloat($("#photo_title").css('border-right-width'))
    );
  
  imgLeft = (parseFloat($("#photo_view").width()) - imgWidth/zoom)/2;
  imgTop = (parseFloat($("#photo_view").height()) - imgHeight/zoom)/2;
  
  $("#photo_view img").css({'margin-left': imgLeft, 'margin-top':imgTop});

  $("#photo_title").css({
    'left':   mainSpaceLeft + parseFloat($("#photo_view").css("padding-left")) + imgLeft,
    'top': imgTop + mainSpaceBottom + parseFloat($("#photo_view").css("padding-bottom")) + $("#photo_view img").height() - $("#photo_title").outerHeight(true)
  });
  

}

$(window).resize(function(){resizeWindow();});

$(document).ready(function(){openWindow();});

function openWindow()
{
  html = '\
  <div id="apaloosa_gallery_view_wrapper">\
  <div id="shadow"></div>\
  <div id="photo_view">\
      <img src="gallery/cesta_do_neznama_wallpaper.jpg"/>\
  </div>\
  <div id="photo_title"><strong class="photo_title">Cesta do neznáma</strong><br/><span class="photo_subtitle">by Michal Hozza</span></div>\
  <a href="#" id="prev_photo" class="photo_nav"><img src="images/previous_photo.png"/></a>\
  <a href="#" id="next_photo" class="photo_nav"><img src="images/next_photo.png"/></a>\
  <div id="comments"><strong>Komentáre</strong> <a id="hide_comments_button" title="skryť" class="pull-right" href="javascript:hideComments()">&gt;&gt;</a><hr class="divider"/></div>\
  <a id="photo_view_close_button" class="button" href="javascript:closeWindow()"><i class="icon-remove icon-white"></i></a>\
  </div>\
  ';

  $("body").append(html);

  $("#hide_comments_button").tooltip({'placement':'left'});

  //TODO: title fade out + show

  //get ImgSize
  $("<img/>") // Make in memory copy of image to avoid css issues
    .attr("src", $("#photo_view img").attr("src"))
    .load(function() {
        imgWidth = this.width;   // Note: $(this).width() will not
        imgHeight = this.height; // work for in memory images.
        resizeWindow();
    });

  $("#photo_title").mouseenter(function(){
    $(this).css({'opacity':1});
    window.clearTimeout(titleTimeout);
  });

  $("#photo_title").mouseleave(function(){
    titleTimeout = window.setTimeout('hideTitle()',1000);
  });

  titleTimeout = window.setTimeout('hideTitle()',2000);

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

function hideTitle()
{
  $("#photo_title").animate({'opacity':0});
}