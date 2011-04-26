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
//    $("img.rating").parent().removeAttr('href');
});