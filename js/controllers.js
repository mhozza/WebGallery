'use strict';

/* Controllers */
function MainViewCtrl($scope, GalleryItems)
{	
	$scope.items = GalleryItems.query();

	$scope.setAlbum = function(path)
	{
		$scope.items = GalleryItems.query({path:'"'+path+'"'});		
	}
}