var imgWidth, imgHeight;

function resizeWindow()       
{
  windowWidth = parseFloat($(window).innerWidth());
  mainSpaceLeft = parseFloat($("#photo_view").css("margin-left"))+parseFloat($("#photo_view").css("border-left"));
  mainSpaceRight = parseFloat($("#photo_view").css("margin-right"))+parseFloat($("#photo_view").css("border-right"));
  
  windowHeight = parseFloat($(window).innerHeight());
  mainSpaceTop = parseFloat($("#photo_view").css("margin-top"))+parseFloat($("#photo_view").css("border-top"));
  mainSpaceBottom = parseFloat($("#photo_view").css("margin-bottom"))+parseFloat($("#photo_view").css("border-bottom"));

  mainWidth = windowWidth-mainSpaceLeft-mainSpaceRight;
  mainHeight = windowHeight-mainSpaceTop-mainSpaceBottom;

  $("#photo_view").innerWidth(mainWidth);
  $("#photo_view").innerHeight(mainHeight);

  $("#photo_view").css({'left': 0, 'top':0});  
  
  zoom = Math.max(imgHeight/parseFloat($("#photo_view").height()), imgWidth/parseFloat($("#photo_view").width()));  
  
  $("#photo_view img").height(imgHeight/zoom);  
  $("#photo_view img").width(imgWidth/zoom);  

  imgLeft = (parseFloat($("#photo_view").width()) - imgWidth/zoom)/2;
  imgTop = (parseFloat($("#photo_view").height()) - imgHeight/zoom)/2;
  $("#photo_view img").css({'margin-left': imgLeft, 'margin-top':imgTop});  

  closeW = parseFloat($("#photo_view_close_button").width());
  closeH = parseFloat($("#photo_view_close_button").height());
  $("#photo_view_close_button").css({'right':mainSpaceRight-closeW/2, 'top':mainSpaceTop-closeH/2});
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
  <a id="photo_view_close_button" href="javascript:closeWindow()"></a>\
  </div>\
  ';

  $("body").append(html);

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