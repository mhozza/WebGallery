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
    //this.root = root;
    /*this.currentDir = currentDir;
    this.setParent*/
    // Prepare
    var History = window.History; // Note: We are using a capital H instead of a lower h
    if ( !History.enabled ) {
      // History.js is disabled for this browser.
      // This is because we can optionally choose to support HTML4 browsers or not.
      return false;
    }
    
    // Bind to StateChange Event
    History.Adapter.bind(window,'statechange',function(){ // Note: We are using statechange instead of popstate
      var State = History.getState(); // Note: We are using History.getState() instead of event.state
      History.log(State.data, State.title, State.url);
    });
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
    link = '<a href = "JavaScript:void(0);" onclick="'+ beginlink + item.path +'\',\''+ item.parentPath + '\');">';
  }
  else
  {
    var beginlink = 'loadDetail(\'?detail=';
    var endlink = '\');';
    imagesrc = 'getimage.php?w=200&h=140&image=' + item.path;
    caption = item.caption;
    link = '<a href = "JavaScript:void(0);" onclick="' + beginlink + item.path +endlink + '">';
  }
  return link + "<div class='box_item '><div class='box_image'><img src='"+ imagesrc + "'/></div><div class='box_caption'>"+caption+"</div></div></a>";
}

Gallery.prototype.setDir = function(dir,parent)
{
  this.currentDir = dir;
  this.loadPhotos();
  this.setParent(parent);
  
  // Change our States
  History.pushState(null, null, '?album='+this.currentDir);

  
}

Gallery.prototype.setParent = function(parent)
{
  g = this;
  //alert(parent);
  this.parent = parent;
  if(this.parent!=undefined && this.parent!='')
  {
    $('#parentDir').removeClass('hidden');
    $('#toolbar').removeClass('hidden');
  }
  else
  {    
    $('#parentDir').addClass('hidden');    
    if($('#toolbar > *').length==1)
      $('#toolbar').addClass('hidden');
  }
  $('#parentDir').attr('href','JavaScript:void(0);');

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







