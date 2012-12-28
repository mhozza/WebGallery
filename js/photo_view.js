var imgWidth, imgHeight;

function resizeWindow()       
{
  var windowWidth = parseFloat($(window).innerWidth());
  var mainSpaceLeft = parseFloat($("#content").css("margin-left"))+parseFloat($("#content").css("border-left"));
  var mainSpaceRight = parseFloat($("#content").css("margin-right"))+parseFloat($("#content").css("border-right"));
  
  var windowHeight = parseFloat($(window).innerHeight());
  var mainSpaceTop = parseFloat($("#content").css("margin-top"))+parseFloat($("#content").css("border-top"));
  var mainSpaceBottom = parseFloat($("#content").css("margin-bottom"))+parseFloat($("#content").css("border-bottom"));

  var mainWidth = windowWidth-mainSpaceLeft-mainSpaceRight;
  var mainHeight = windowHeight-mainSpaceTop-mainSpaceBottom;

  $("#content").innerWidth(mainWidth);
  $("#content").innerHeight(mainHeight);

  $("#content").css({'left': 0, 'top':0});  
  
  var zoom = Math.max(imgHeight/parseFloat($("#content").height()), imgWidth/parseFloat($("#content").width()));  
  
  $("#content img").height(imgHeight/zoom);  
  $("#content img").width(imgWidth/zoom);  

  imgLeft = (parseFloat($("#content").width()) - imgWidth/zoom)/2;
  imgTop = (parseFloat($("#content").height()) - imgHeight/zoom)/2;
  $("#content img").css({'margin-left': imgLeft, 'margin-top':imgTop});  
}

//get ImgSize
$("<img/>") // Make in memory copy of image to avoid css issues
    .attr("src", $("#content img").attr("src"))
    .load(function() {
        imgWidth = this.width;   // Note: $(this).width() will not
        imgHeight = this.height; // work for in memory images.
        resizeWindow();
    });

$(document).ready(function(){resizeWindow();})
$(window).resize(function(){resizeWindow();});