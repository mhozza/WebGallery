'use strict';

/* Controllers */
function MainViewCtrl($scope, GalleryItems, $routeParams)
{
	var path = $routeParams.albumPath;
	if(path=='')
		$scope.album = GalleryItems.get(function() {
  				$scope.setParent($scope.album.parent);
			});

	else
		$scope.album = GalleryItems.get({path:'"'+path+'"'},function() {
  				$scope.setParent($scope.album.parent);
			});
}

function MainCtrl ($scope) {

 	$scope.albumParent = null;

 	$scope.setParent = function(parent)
 	{
 		$scope.albumParent = parent;
 		console.log(parent);
 	}
}