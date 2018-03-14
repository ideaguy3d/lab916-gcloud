<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">

    <title>LAB 916</title>

    <!-- Custom fonts for this theme -->
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic'
          rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Raleway:400,100,200,300,600,500,700,800,900' rel='stylesheet'
          type='text/css'>

    <!-- Vendor CSS -->
    <link href="../form/js/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="../form/js/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="../form/js/animate.css/animate.min.css" rel="stylesheet" type="text/css">

    <!-- Custom CSS -->
    <link href="../form/css/lab916-yellow.css" rel="stylesheet" type="text/css">
    <link href="../form/css/custom.css" rel="stylesheet">
</head>

<body data-ng-app="lab-fba-form">

<div class="container" data-ng-controller="FormCtrl">
    <br><br>

    <h1>LAB 916</h1>
    <p>Add a new client <strong>"FBA All Orders Data"</strong> Report</p>

    <div class="row">
        <div class="col-sm-12 col-md-8 col-lg-6 col-xl-4">
            <form>
                <input type="text" ng-model="clientObj.mwsAuthKey"  required
                       placeholder="MWS Auth Token">
                <input type="text" ng-model="clientObj.clientName" required
                       placeholder="Client Name(no spaces, all lower case letters)">
                <input type="text" ng-model="clientObj.merchantId" required
                       placeholder="Seller/Merchant ID">

                <br>
                <div type="submit" class="btn btn-sm btn-warning mt-2" data-ng-click="createReport()">Create</div>
            </form>
        </div>
    </div>

    <br>

    <div ng-if="showGif">
        <p>Give it plenty of time to stream data.</p>
        <p>{{ countdown }}</p>
        <img src="../img/ripple.gif" alt="loader">
    </div>
    
    <br>

    <h6>Server Response =</h6>
    <div ng-bind-html="reportResponse"></div>

    <br>
    <p>v{{43-42 + message}}</p>
</div>

<!-- Vendor JavaScript -->
<script src="../form/js/angular/angular.min.js"></script>

<!-- Custom JavaScript -->
<script src="../form/lab.fba.form.js"></script>

</body>
</html>