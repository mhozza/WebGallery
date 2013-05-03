photo_view = new Object();
photo_view.photos = [];

var keyCode = {
  BACKSPACE: 8,
  CAPS_LOCK: 20,
  COMMA: 188,
  CONTROL: 17,
  DELETE: 46,
  DOWN: 40,
  END: 35,
  ENTER: 13,
  ESCAPE: 27,
  HOME: 36,
  INSERT: 45,
  LEFT: 37,
  NUMPAD_ADD: 107,
  NUMPAD_DECIMAL: 110,
  NUMPAD_DIVIDE: 111,
  NUMPAD_ENTER: 108,
  NUMPAD_MULTIPLY: 106,
  NUMPAD_SUBTRACT: 109,
  PAGE_DOWN: 34,
  PAGE_UP: 33,
  PERIOD: 190,
  RIGHT: 39,
  SHIFT: 16,
  SPACE: 32,
  TAB: 9,
  UP: 38
}


// $(document).ready(function(){photo_view.openWindow('gallery/cesta_do_neznama_wallpaper.jpg');});
$(window).resize(function(){photo_view.resizeWindow();});

$(document).keydown(function(event){
  var key = event.keyCode || event.which;
  //Not in a textarea or textbox
  if (event.target.type !== 'text' && event.target.type !== 'textarea') {
      if (key === keyCode.LEFT) {
        photo_view.setPhoto(photo_view.getPreviousId(photo_view.current_photo.id));
      }
      if (key === keyCode.RIGHT) {
        photo_view.setPhoto(photo_view.getNextId(photo_view.current_photo.id));
      }
  }

  if (key === keyCode.ESCAPE) {
    photo_view.closeWindow();
  }
});


photo_view.resizeWindow = function resizeWindow()
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
  this.mainSpaceLeft = parseFloat($("#photo_view").css("margin-left"))+parseFloat($("#photo_view").css("border-left-width"));
  mainSpaceRight = parseFloat($("#photo_view").css("margin-right"))+parseFloat($("#photo_view").css("border-right-width"))+commentsWidth;

  windowHeight = parseFloat($(window).innerHeight());
  mainSpaceTop = parseFloat($("#photo_view").css("margin-top"))+parseFloat($("#photo_view").css("border-top-width"));
  this.mainSpaceBottom = parseFloat($("#photo_view").css("margin-bottom"))+parseFloat($("#photo_view").css("border-bottom-width"));

  commentsSpaceTop = parseFloat($("#comments").css("margin-top"))+parseFloat($("#comments").css("border-top-width"));
  commentsSpaceBottom = parseFloat($("#comments").css("margin-bottom"))+parseFloat($("#comments").css("border-bottom-width"));

  mainWidth = windowWidth-this.mainSpaceLeft-mainSpaceRight;
  mainHeight = windowHeight-mainSpaceTop-this.mainSpaceBottom;
  commentsHeight = windowHeight-commentsSpaceTop-commentsSpaceBottom;

  $("#photo_view").innerWidth(mainWidth);
  $("#photo_view").innerHeight(mainHeight);

  //comments & rating
  $("#comments").innerHeight(commentsHeight);
  $("#comment_text").outerWidth($("#comments").width());
  $("#comments_footer").outerWidth(parseFloat($("#comments").width()));
  $("#comments_footer").css({'bottom': this.mainSpaceBottom})
  $("#comments_content").height($("#comments").height()
    - $("#comments_footer").outerHeight(true)
    - $("#comments_title").outerHeight(true)
    - parseFloat($("#comments_content").css('padding-bottom'))
    - parseFloat($("#comments_content").css('padding-top'))
    - parseFloat($("#comments_content").css('margin-bottom'))
    - parseFloat($("#comments_content").css('margin-top'))
    );

  this.resizeImage();

  //nav
  $(".photo_nav").height($("#prev_photo img").height());
  $(".photo_nav").css({'padding':(mainHeight-$("#prev_photo img").height())/2 + 'px 4px'});
  $("#prev_photo").css({'left':this.mainSpaceLeft});
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

photo_view.resizeImage = function()
{
  zoom = Math.max(this.imgHeight/parseFloat($("#photo_view").height()), this.imgWidth/parseFloat($("#photo_view").width()));

  $("#photo_view img").height(this.imgHeight/zoom);
  $("#photo_view img").width(this.imgWidth/zoom);

  $("#photo_title").width($("#photo_view img").width()
     - parseFloat($("#photo_title").css('margin-left'))
     - parseFloat($("#photo_title").css('margin-right'))
     - parseFloat($("#photo_title").css('padding-left'))
     - parseFloat($("#photo_title").css('padding-right'))
     - parseFloat($("#photo_title").css('border-left-width'))
     - parseFloat($("#photo_title").css('border-right-width'))
    );

  imgLeft = (parseFloat($("#photo_view").width()) - this.imgWidth/zoom)/2;
  imgTop = (parseFloat($("#photo_view").height()) - this.imgHeight/zoom)/2;

  $("#photo_view img").css({'margin-left': imgLeft, 'margin-top':imgTop});

  $("#photo_title").css({
    'left':   this.mainSpaceLeft + parseFloat($("#photo_view").css("padding-left")) + imgLeft,
    'top': imgTop + this.mainSpaceBottom + parseFloat($("#photo_view").css("padding-bottom")) + $("#photo_view img").height() - $("#photo_title").outerHeight(true)
  });
}

photo_view.loadPhotos = function(photos)
{
  console.log('photos reloaded');
  this.photos = [];
  for (var i = 0; i < photos.length; i++) {
    if(photos[i].class=="Photo")
    {
      this.photos.push(photos[i]);
    }
  };
}

photo_view.getPhotoIndexById = function(id)
{
  for (var i = 0; i < this.photos.length; i++) {
    if (this.photos[i].id == id)
    {
      return i;
    }
  }
}

photo_view.preloadPhoto = function(photo)
{
  $("<img/>").attr("src", photo.path).load(function(){
      //this.loaded[i]=true
  });
}

photo_view.preloadPhotos = function(id)
{
  if (id==null) return;
  index = this.getPhotoIndexById(id);
  for(i = index+1;i<index+5 && i<this.photos.length;i++)
  {
    this.preloadPhoto(this.photos[i]);
  }
  for(i = index-1;i>index-2 && i>0;i--)
  {
    this.preloadPhoto(this.photos[i]);
  }
}

photo_view.setPhoto = function(id)
{
  if (id==null) return;
  photo = this.photos[this.getPhotoIndexById(id)];
  this.current_photo = photo;
  $("#photo_view img").attr("src", photo.path);
  if(photo.caption!='')
    $("#photo_title").html('<strong class="photo_title">'+photo.caption+'</strong><br/>');
  else
    $("#photo_title").html('');

  $("#prev_photo").attr("href", 'javascript:photo_view.setPhoto('+this.getPreviousId(id)+');');
  $("#next_photo").attr("href", 'javascript:photo_view.setPhoto('+this.getNextId(id)+');');


  //--- Redraw window --------------------------------------------------------------------------
  //get ImgSize
  $("<img/>") // Make in memory copy of image to avoid css issues
    .attr("src", $("#photo_view img").attr("src"))
    .load(function() {
        photo_view.imgWidth = this.width;   // Note: $(this).width() will not
        photo_view.imgHeight = this.height; // work for in memory images.
        photo_view.resizeWindow();
    });

  $("#photo_title").css({'opacity':1});
  window.clearTimeout(this.titleTimeout);

  $("#photo_title").mouseenter(function(){
    $(this).css({'opacity':1});
    window.clearTimeout(this.titleTimeout);
  });


  $("#photo_title").mouseleave(function(){
    this.titleTimeout = window.setTimeout('photo_view.hideTitle()',1000);
  });

  this.titleTimeout = window.setTimeout('photo_view.hideTitle()',2000);

  this.resizeWindow();

  this.preloadPhotos(id);
}

photo_view.getNextId = function(id)
{
  index = this.getPhotoIndexById(id);
  if(index==undefined || index==this.photos.length-1) return null;
  photo = this.photos[index+1];
  return photo.id
}

photo_view.getPreviousId = function(id)
{
  index = this.getPhotoIndexById(id);
  if(index==undefined || index==0) return null;
  photo = this.photos[index-1];
  return photo.id
}

photo_view.openWindow = function(id)
{
  // photo = this.photos[this.getPhotoIndexById(id)];
  html = '\
  <div id="apaloosa_gallery_view_wrapper">\
    <div id="shadow"></div>\
    \
    <div id="photo_view">\
        <img/>\
    </div>\
    \
    <div id="photo_title"></div>\
    \
    <a href="#" id="prev_photo" class="photo_nav"><img src="images/previous_photo.png"/></a>\
    <a href="#" id="next_photo" class="photo_nav"><img src="images/next_photo.png"/></a>\
    \
    <div id="comments">\
      <strong id="comments_title">Komentáre</strong>\
      <a id="hide_comments_button" title="skryť" class="pull-right" href="javascript:photo_view.hideComments()">&gt;&gt;</a>\
      \
      <div id="comments_content">\
      <div class="comment"><div class="comment_title"><span class="label label-inverse">Michal Hozza</span> <small class="muted pull-right">pred 5 minútami</small></div> <div class="comment_body">Nejaky zmysluplny text, ktory nie je prilis dlhy</div></div>\
      <div class="comment"><div class="comment_title"><span class="label label-inverse">Michal Hozza</span> <small class="muted pull-right">pred 5 minútami</small></div> <div class="comment_body">Nejaky zmysluplny text, ktory nie je prilis dlhy</div></div>\
      </div>\
      <div id="comments_fake_footer">&nbsp;</div>\
      <div id="comments_footer">\
      <form action="" method="post" class="comments">\
        <textarea name="comment_text" id="comment_text"></textarea><br/>\
        <button class="btn btn-primary" type="submit">Pridať</button> <button class="btn" type="reset">Zrušiť</button><span id="comment_text_count"></span>\
      </form>\
        <div id="rating">\
          <span id="rate_label">Ohodnoť:</span>&nbsp;\
          <span id="rating_stars" class="rating pull-right">\
            <span>☆</span><span>☆</span><span>☆</span><span>☆</span><span>☆</span>\
          </span>\
        </div>\
        <div class="dropup">\
          <a href="#" id="share_button" data-toggle="dropdown" class="cleaner dropdown-toggle btn btn-block"><i class="icon-share-alt"></i> Zdieľať</a>\
          <ul class="dropdown-menu pull-right" role="menu">\
            <li><a tabindex="-1" href="#"><i class="icon-google-plus-sign"></i> Google+</a></li>\
            <li><a tabindex="-1" href="#"><i class="icon-facebook-sign"></i> Facebook</a></li>\
            <li><a tabindex="-1" href="#"><i class="icon-twitter-sign"></i> Twitter</a></li>\
          </ul>\
        </div>\
      </div>\
    </div>\
    <a id="photo_view_close_button" class="button" href="javascript:photo_view.closeWindow()"><i class="icon-remove icon-white"></i></a>\
  </div>\
  ';
  $("body").append(html);

  this.setPhoto(id);

  // <span id="rate_label" class="label">Ohodnoťte:</span>&nbsp;<span id="rating_stars" class="rating pull-right">'+stars()+'</span></div>\
  //onSubmit="$.post(document.location.href,{comment_text:$(\'textarea\').val(),ajax:\'true\'},function(data){$(\'#comments\').html(data);});return false;"
  //onChange="maxlength(this,{{CONST.MAX_COMMENT_SIZE}})" onKeyUp="maxlength(this,{{CONST.MAX_COMMENT_SIZE}})"

  $("#hide_comments_button").tooltip({'placement':'left'});
  // $("#share_button").tooltip({'placement':'left'});
  $("#share_button").dropdown();
}

photo_view.closeWindow = function()
{
  $("#apaloosa_gallery_view_wrapper").remove();
}

photo_view.hideComments = function()
{
  $("#comments").addClass("hidden");
  html='<a id="show_comments_button" class="button" href="javascript:photo_view.showComments()"><i class="icon-comment icon-white"></i></a>';
  $("#apaloosa_gallery_view_wrapper").append(html);
  this.resizeWindow();

}

photo_view.showComments = function()
{
  $("#show_comments_button").remove();
  $("#comments").removeClass("hidden");
  this.resizeWindow();
}

photo_view.hideTitle = function()
{
  $("#photo_title").animate({'opacity':0});
}

photo_view.maxlength = function(element,length)
{
  if (element.value.length>length) element.value=element.value.substring(0,length);
  $("#"+element.id+"_count").html("Zostáva "+(length-element.value.length)+" znakov.");
}
