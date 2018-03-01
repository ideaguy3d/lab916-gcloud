(function () {
    "use strict";

    var app = angular.module("lab-fba-form", []);

    app.controller("FormCtrl", ["$scope", "labDataSer", FormCtrlClass]);
    app.factory("labDataSer", ["$http", DataSerClass]);

    // CLASS CTRL OBJECT "FormCtrlClass"
    function FormCtrlClass($scope, labDataSer) {
        $scope.message = " FBA Form";

        // data model
        $scope.clientObj = {
            clientName: "",
            mwsAuthKey: "",
            sellerId: ""
        };

        $scope.createReport = function () {
            console.log("lab916 - making the request");
            labDataSer.createReport($scope.clientObj).then(function (res) {
                console.log("response data =");
                console.log(res.data);
                console.log("response object =");
                console.log(res);
            });
        }
    }

    // CLASS SERVICE OBJECT "DataSerClass"
    function DataSerClass($http) {
        var createReportF = function (data) {
            var action = "dynamic-client-add";
            var enAction = encodeURIComponent(action);
            var clientName = encodeURIComponent(data.clientName);
            var sellerId = encodeURIComponent(data.sellerId);
            var mwsAuthKey = encodeURIComponent(data.mwsAuthKey);

            var reqStr = "/?action=" + enAction +
                "&client-name=" + clientName +
                "&seller-id=" + sellerId +
                "&mws-auth-key=" + mwsAuthKey;

            //-- make the HTTP request:
            return $http.get(reqStr);
        };

        return {
            createReport: createReportF
        }
    }
})();



