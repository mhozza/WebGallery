// Read a page's GET URL variables and return them as an associative array.
function getUrlVars(url)
{
  var vars = [], hash;
  var hashes = url.slice(url.indexOf('?') + 1).split('&');
  for(var i = 0; i < hashes.length; i++)
  {
    hash = hashes[i].split('=');
    vars.push(hash[0]);
    vars[hash[0]] = hash[1];
  }
  return vars;
}

//Gallery class
function Gallery(currentDir)
{    
    this.currentDir = currentDir;

    //History
    var History = window.History; 
    if ( !History.enabled ) {
      // History.js is disabled for this browser.
      // This is because we can optionally choose to support HTML4 browsers or not.
      return false;
    }
    g = this;

    // Bind to StateChange Event
    History.Adapter.bind(window,'statechange',function(){
      var State = History.getState();      
      var urlVars = getUrlVars(State.url);
      if(urlVars['album']!=undefined) g.setDir(urlVars['album']);
    });
}

Gallery.prototype.itemBox = function(item)
{
  var link;
  var imagesrc;
  var caption;
  if(item.class == 'Album')
  {
    var beginlink = 'g.setUrl(\'';    
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

Gallery.prototype.setUrl = function(dir)
{
  this.currentDir = dir;    
  History.pushState(null, null, '?album='+this.currentDir);  
}

Gallery.prototype.setDir = function(dir)
{  
  this.currentDir = dir;
  this.loadPhotos();  
}

Gallery.prototype.setParent = function()
{  
  if(this.dirObject.parentPath!=undefined && this.dirObject.parentPath!='')
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
  g = this;
  $('#parentDir').click(function() {
    g.setUrl(g.dirObject.parentPath);
  });
}

Gallery.prototype.setCaption = function()
{
  $('#album_title > h1').text(this.dirObject.caption);
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
    g.items = JSON.parse(data);
    for(var i in g.items)
    {
      if(i==0)
        g.dirObject = g.items[i];
      else
       $('#album_plane > .cleaner').before(g.itemBox(g.items[i]));
    }
    g.setParent();
    g.setCaption();
  });
}

var album = getUrlVars(window.location.href)['album'];
if(album==undefined)
  album = 'gallery';

var g = new Gallery(album);

$(document).ready(function() {
  g.loadPhotos();  
});







