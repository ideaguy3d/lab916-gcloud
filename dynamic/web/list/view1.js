'use strict';

angular.module('myApp.view1', ['ngRoute'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/view1', {
    templateUrl: 'list/view1.html',
    controller: 'View1Ctrl'
  });
}])

.controller('ListCtrl', [function() {

}]);