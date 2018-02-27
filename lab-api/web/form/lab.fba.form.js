(function(){
    "use strict";

    var app = angular.module("lab-fba-form", []);

    app.controller("FormCtrl", ["$scope", FormCtrlClass]);

    app.factory("DataSer", ["$http", DataSerClass]);

    function FormCtrlClass($scope) {
        $scope.message = " FBA Form";
    }

    function DataSerClass($http) {

    }
})();



