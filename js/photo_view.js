var imgWidth, imgHeight;

function resizeWindow()       
{
  var windowWidth = parseFloat($(window).innerWidth());
  var mainSpaceLeft = parseFloat($("#photo_view").css("margin-left"))+parseFloat($("#photo_view").css("border-left"));
  var mainSpaceRight = parseFloat($("#photo_view").css("margin-right"))+parseFloat($("#photo_view").css("border-right"));
  
  var windowHeight = parseFloat($(window).innerHeight());
  var mainSpaceTop = parseFloat($("#photo_view").css("margin-top"))+parseFloat($("#photo_view").css("border-top"));
  var mainSpaceBottom = parseFloat($("#photo_view").css("margin-bottom"))+parseFloat($("#photo_view").css("border-bottom"));

  var mainWidth = windowWidth-mainSpaceLeft-mainSpaceRight;
  var mainHeight = windowHeight-mainSpaceTop-mainSpaceBottom;

  $("#photo_view").innerWidth(mainWidth);
  $("#photo_view").innerHeight(mainHeight);

  $("#photo_view").css({'left': 0, 'top':0});  
  
  var zoom = Math.max(imgHeight/parseFloat($("#photo_view").height()), imgWidth/parseFloat($("#photo_view").width()));  
  
  $("#photo_view img").height(imgHeight/zoom);  
  $("#photo_view img").width(imgWidth/zoom);  

  imgLeft = (parseFloat($("#photo_view").width()) - imgWidth/zoom)/2;
  imgTop = (parseFloat($("#photo_view").height()) - imgHeight/zoom)/2;
  $("#photo_view img").css({'margin-left': imgLeft, 'margin-top':imgTop});  
}

$(window).resize(function(){resizeWindow();});



// $(document).ready(function(){openWindow();});

function openWindow()
{
  html = '\
  <div id="shadow"></div>\
  <div id="photo_view">\
    <img src="gallery/cesta_do_neznama_wallpaper.jpg"/>\
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