'use strict';

/* Controllers */
function MainViewCtrl($scope, GalleryItems, $routeParams)
{
	var path = $routeParams.albumPath;

	if(path=='') 
		path = '[]';	
	else
		path = '["'+path+'"]';

	$scope.album = GalleryItems.get({path:path},function() {
				$scope.setParent($scope.album.parent);
				photo_view.loadPhotos($scope.album.items);
		});
}

function MainCtrl ($scope) {

 	$scope.albumParent = null;

 	$scope.setParent = function(parent)
 	{
 		$scope.albumParent = parent;
 	}
}