function loadDetail(image_url)
{ 
  $.get(image_url,{ajax:'true'},function(data){
    $('body').append(data);
  });
}