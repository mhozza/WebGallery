/*function next_photo()
{
  
}

function previous_photo()
{
  
}

function close_photo(photo)
{
  
}

function load_photo(photo)
{
  
}

function load_comments(photo)
{
  
}

function load_rating(photo)
{
  
}

function load_caption(photo)
{
  
}*/


function Gallery(currentDir)
{
    this.currentDir = currentDir;
}

Gallery.prototype.itemBox = function(item)
{
  var link;
  var imagesrc;
  var caption;
  if(item.class == 'Album')
  {
    var beginlink = 'g.setDir(\'';
    imagesrc = 'images/album.png';
    caption = item.caption;
    link = '<a href = "#" onclick="'+ beginlink + item.path +'\');">';
  }
  else
  {
    var beginlink = 'loadDetail(\'?detail=';
    var endlink = '\');';
    imagesrc = 'getimage.php?w=200&h=140&image=' + item.path;
    caption = item.caption;
    link = '<a href = "#" onclick="' + beginlink + item.path +endlink + '">';
  }
  return link + "<div class='box_item '><div class='box_image'><img src='"+ imagesrc + "'  /></div><div class='box_caption'>"+caption+"</div></div></a>";  
}

Gallery.prototype.setDir = function(dir)
{
  this.currentDir = dir;
  this.loadPhotos();
}

Gallery.prototype.loadPhotos = function()
{
  var cleaner = $('#album_plane > .cleaner');
  $('#album_plane').empty();
  $('#album_plane').append(cleaner);
  
  g = this;
  $.ajax({
    type: "POST",
    url: "ajax.php",
    data: "action=getPhotos&album="+this.currentDir
  }).done(function( data ) {
    jsonObj = JSON.parse(data);
    for(var i in jsonObj)
    {       
       $('#album_plane > .cleaner').before(g.itemBox(jsonObj[i]));
    }    
  });
}

var g = new Gallery('gallery');

$(document).ready(function() {  
  g.loadPhotos();
});







