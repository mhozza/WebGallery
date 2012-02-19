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


function Gallery()
{
    /*this.currentDir = currentDir;
    this.setParent*/
}

Gallery.prototype.itemBox = function(item)
{
  var link;
  var imagesrc;
  var caption;
  if(item.class == 'Album')
  {
    var beginlink = 'g.setDir(\'';
    //imagesrc = 'images/album.png';
    imagesrc = 'albumthumbnail.php?w=200&h=140&image=' + item.path;
    caption = item.caption;
    link = '<a href = "#" onclick="'+ beginlink + item.path +'\',\''+ item.parentPath + '\');">';
  }
  else
  {
    var beginlink = 'loadDetail(\'?detail=';
    var endlink = '\');';
    imagesrc = 'getimage.php?w=200&h=140&image=' + item.path;
    caption = item.caption;
    link = '<a href = "#" onclick="' + beginlink + item.path +endlink + '">';
  }
  return link + "<div class='box_item '><div class='box_image'><img src='"+ imagesrc + "'/></div><div class='box_caption'>"+caption+"</div></div></a>";
}

Gallery.prototype.setDir = function(dir,parent)
{
  this.currentDir = dir;
  this.loadPhotos();
  this.setParent(parent);
}

Gallery.prototype.setParent = function(parent)
{
  g = this;
  //alert(parent);
  this.parent = parent;
  if(this.parent!=undefined && this.parent!='')
    $('#parentDir').removeClass('hidden');
  else
    $('#parentDir').addClass('hidden');
  $('#parentDir').attr('href','#');

  $('#parentDir').off('click');
  $('#parentDir').click(function() {
    g.setDir(parent);
  });
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

var g = new Gallery();

$(document).ready(function() {
  g.setDir('gallery','');  
});







