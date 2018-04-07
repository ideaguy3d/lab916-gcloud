(function () {
    "use strict";

    var app = angular.module("lab-fba-form", []);

    app.controller("FormCtrl", ["$scope", "labDataSer", "$sce", "$interval", FormCtrlClass]);
    app.factory("labDataSer", ["$http", DataSerClass]);

    // CLASS CTRL OBJECT "FormCtrlClass"
    function FormCtrlClass($scope, labDataSer, $sce, $interval) {
        $scope.message = " FBA Form";
        $scope.showGif = false;

        // data model
        $scope.clientObj = {
            clientName: "",
            mwsAuthKey: "",
            merchantId: "",
            description: "",
            information: "",
            notes: ""
        };

        $scope.createReport = function () {
            $scope.clientObj.clientName = $scope.clientObj.clientName.toLowerCase();
            $scope.clientObj.clientName = $scope.clientObj.clientName.replace(/\s/gi, '_');

            console.log("lab916 - making the request | Client Name = " + $scope.clientObj.clientName);

            $scope.showGif = true;

            $interval(function () {
                $scope.countdown = 8;
                $scope.countdown--;
            }, 1000, 10, true);

            var actuallyMakeRequest = false;
            if (actuallyMakeRequest) {
                labDataSer.createReport($scope.clientObj).then(function (res) {
                    console.log("response object =");
                    console.log(res);
                    $scope.showGif = false;
                    $scope.reportResponse = $sce.trustAsHtml(res.data);
                });
            }
        }
    }

    // CLASS SERVICE OBJECT "DataSerClass"
    function DataSerClass($http) {

        var createReportF = function (data) {
            var action = "dynamic-client-add";
            var enAction = encodeURIComponent(action);
            var clientName = encodeURIComponent(data.clientName);
            var merchantId = encodeURIComponent(data.merchantId);
            var mwsAuthKey = encodeURIComponent(data.mwsAuthKey);
            var information = encodeURIComponent(data.information);
            var description = encodeURIComponent(data.description);
            var notes = encodeURIComponent(data.notes);

            var reqStr = "/?action=" + enAction + "&client-name=" + clientName + "&mws-auth-key=" + mwsAuthKey
                + "&merchant-id=" + merchantId + "&information=" + information + "&description=" + description
                + "&notes=" + notes;

            console.log("lab916 - The reqStr = " + reqStr);

            //-- make the HTTP request:
            return $http.get(reqStr);
        };

        return {
            createReport: createReportF
        }
    }
})();



