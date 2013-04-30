'use strict';


// Declare app level module which depends on filters, and services
angular.module('WebGallery', ['WebGallery.filters', 'WebGallery.services', 'WebGallery.directives']).
  config(function($routeProvider, $locationProvider, $compileProvider) {
  	// $locationProvider.html5Mode(true);
    $routeProvider.when('/*albumPath', {templateUrl: 'templates/js/mainview.html', controller: 'MainViewCtrl'})
    $routeProvider.otherwise({redirectTo: '/'});
	$compileProvider.urlSanitizationWhitelist(/^\s*(https?|javascript):/);
  });
