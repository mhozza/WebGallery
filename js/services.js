'use strict';

/* Services */


// Demonstrate how to register services
// In this case it is a simple value service.
angular.module('WebGallery.services', ['ngResource']).
    factory('GalleryItems', function($resource){
        return $resource('api.php?method=getItems&params=:path', {path:'[]'}, {});
    });
