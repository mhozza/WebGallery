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

              $("#content").width(mainWidth);
              $("#content").height(mainHeight);

              $("#content").css({left: 0, top: 0});
            }

            resizeWindow();
            $(window).resize(function(){resizeWindow();});