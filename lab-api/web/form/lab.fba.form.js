(function(){
    "use strict";

    var app = angular.module("lab-fba-form", []);

    app.controller("FormCtrl", ["$scope", "labDataSer", FormCtrlClass]);

    app.factory("labDataSer", ["$http", DataSerClass]);

    function FormCtrlClass($scope) {
        $scope.message = " FBA Form";
    }

    function DataSerClass($http) {
        var base = "/?";

        var createReport = function(data) {
            return $http.get(base);
        };

        return {
            createReport: createReport
        }
    }
})();



