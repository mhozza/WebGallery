function maxlength(element,length)
{  
  if (element.value.length>length) element.value=element.value.substring(0,length);
  $("#"+element.id+"_count").html("Zostáva "+(length-element.value.length)+" znakov.");  
}

