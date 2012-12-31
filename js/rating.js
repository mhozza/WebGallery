function stars()
{
  s = "";  
  for (var i = 0; i < 5; i++) {    
    s+= '<a href="#">\
    <img src="images/star_white.png" alt="'+ i +'" class="rating" onMouseOver="ratingOver(this,true)" onMouseOut="ratingOver(this,false)"/>\
    </a>';
  }
  return s;
}

function ratingOver(element,over)
{  
  if(over)
    $("img.rating",$(element).closest('div')).each(function() {
      if($(this).attr('alt')<=$(element).attr('alt'))
        $(this).attr('src','images/star.png');
    });    
  else
    $("img.rating",$(element).closest('div')).attr('src','images/star_white.png'); 
}

$(document).ready(function(){
    //zmen rating na ajaxovy
    $("img.rating").each(function(){
      $(this).attr('href',$(this).parent().attr('href'));
    });
    $("img.rating").parent().removeAttr('href');
    $("img.rating").each(function(){
      $(this).click(function(){
        $.get($(this).attr('href'),{ajax:'true'},function(data){
          $('span.rating').html(data);
          });
      });
    });  
});