/**
 * Created by julius on 12/7/2017.
 */
/**
 * Created by Julius Alvarado
 */

(function () {
    "use strict";

    var app = angular.module('quote', []),
        controllerId = 'QuoteCtrl',
        factoryId = 'jDataSer';

    //#region Quiz Application:
    app.factory(factoryId, ['$http', jDataServiceClass]);

    app.controller(controllerId, ['$scope', '$sce', 'jDataSer', QuoteCtrlClass]);

    function QuoteCtrlClass($scope, $sce, jDataSer) {
        $scope.introMessage = 'Test your knowledge';
        $scope.score = 0;
        $scope.activeQuestion = 0; // set activeQuestion to -1 and uncomment 'Intro Slide' to get the intro slide back
        $scope.activeQuestionAnswered = 0;
        $scope.percentScore = 0;
        $scope.optionIsSelected = false;
        $scope.userHasSent = false;

        // contactHub is the real HubSpot req obj
        $scope.contactHubObject = {
            email: "",
            name: "",
            company: "",
            message: "",
            number: "916-123-4567",
            currentSalesChannels: [],
            companySnapshot: [],
            amazonGoals: [],
            amazonServices: [],
            otherQuestions: []
        };

        //-- used in the side navigation:
        $scope.changeActiveQuestion = function (idx) {
            $scope.activeQuestion = idx;
        };

        $scope.goBack = function () {
            $scope.activeQuestion -= 1;
        };

        // This function helps manage the view model
        function createHubspotContact(shQuestion, answerObj) {
            // manage 'Currently Selling' slide
            if (shQuestion === "Currently Selling") {
                if (answerObj.optionIsSelected && answerObj.text !== "other") {
                    $scope.contactHubObject.currentSalesChannels[answerObj.id] = answerObj.text;
                } else {
                    if ($scope.contactHubObject.currentSalesChannels[answerObj.id] && answerObj.text !== "other") {
                        delete $scope.contactHubObject.currentSalesChannels[answerObj.id];
                    }
                }
                console.log("jha - $scope.contactHubObject.currentSalesChannels = ");
                console.log($scope.contactHubObject.currentSalesChannels);
            }
            // manage 'Company Snapshot' slide
            else if (shQuestion === "Company Snapshot") {
                console.log("jha - $scope.contactHubObject.companySnapshot =");
                console.log($scope.contactHubObject.companySnapshot);
            }
            // manage 'Amazon Goals' slide
            else if (shQuestion === "Amazon Goals") {
                console.log("jha - $scope.contactHubObject.amazonGoals =");
                console.log($scope.contactHubObject.amazonGoals);
            }
            // manage 'Amazon Services' slide
            else if (shQuestion === "Amazon Services") {
                if (answerObj.optionIsSelected && answerObj.text !== "other") {
                    $scope.contactHubObject.amazonServices[answerObj.id] = answerObj.text;
                } else {
                    if ($scope.contactHubObject.amazonServices[answerObj.id] && answerObj.text !== "other") {
                        delete $scope.contactHubObject.amazonServices[answerObj.id];
                    }
                }
                console.log("jha - $scope.contactHubObject.amazonServices =");
                console.log($scope.contactHubObject.amazonServices);
            }
            // manage 'Other Question' slide
            else if (shQuestion === "Other Questions") {
                console.log("jha - $scope.contactHubObject.otherQuestions = ");
                console.log($scope.contactHubObject.otherQuestions);
            }
        }

        // this function is purely for our Google Cloud SQL back end.
        function googleCloudSqlContact() {
            $scope.contactHubObject.userId = (Math.random() * 10);
            console.log("jha - " + $scope.contactHubObject);
            jDataSer.googleCloudSqlContact($scope.contactHubObject).then(function (res) {
                console.log("jha - Google Response =");
                console.log(res.data);
            });
        }

        // this function will populate our HubSpot table via their web service
        $scope.makeHubspotRequest = function () {
            var send2hubspot = true;

            googleCloudSqlContact();

            if (send2hubspot) {
                jDataSer.createHubspotContact($scope.contactHubObject).then(function (res) {
                    console.log("jha - res.data =");
                    console.log(res.data);
                });
            }

            $scope.userHasSent = true;
        };

        // sort of like the engine that powers this questionnaire
        $scope.selectAnswer = function (indexQuestion, indexAnswer) {
            var questionState = $scope.myQuestions[indexQuestion].questionState;

            //-- this will style the answer card to indicate it has been selected:
            $scope.myQuestions[indexQuestion].answers[indexAnswer].optionIsSelected =
                !$scope.myQuestions[indexQuestion].answers[indexAnswer].optionIsSelected;

            // questionState is always going to initially be falsey because user has yet to click on an answer
            if (questionState !== 'answered') {
                var correctAnswer = $scope.myQuestions[indexQuestion].correct;

                $scope.myQuestions[indexQuestion].selectedAnswer = indexAnswer;
                $scope.myQuestions[indexQuestion].correctAnswer = correctAnswer;

                if (indexAnswer === correctAnswer) {
                    $scope.myQuestions[indexQuestion].correctness = 'correct';
                    $scope.score += 1;
                } else {
                    $scope.myQuestions[indexQuestion].correctness = 'incorrect';
                }

                // now that user has clicked on an answer I now set .questionState
                $scope.myQuestions[indexQuestion].questionState = 'answered';
            }

            var sq = $scope.myQuestions[indexQuestion].shortenedQuestion;
            var ao = $scope.myQuestions[indexQuestion].answers[indexAnswer];
            createHubspotContact(sq, ao);
        };

        function testOne() {
            jDataSer.testOne($scope.contactHubObject).then(function(res) {
                console.log("jha - testOne response:");
                console.log(res.data);
            })
        }

        $scope.isSelected = function (qIndex, aIndex) {
            return $scope.myQuestions[qIndex].selectedAnswer === aIndex;
        };

        $scope.isCorrect = function (qIndex, aIndex) {
            return $scope.myQuestions[qIndex].correctAnswer === aIndex;
        };

        $scope.selectContinue = function () {
            return $scope.activeQuestion += 1;
        };

        $scope.createShareLinks = function (percent) {
            var url = 'http://php1.julius3d.com';
            var emailLink = '<a href="mailto:?subject=Quiz Score.&amp;body=Beat my ' + percent +
                '% quiz score at ' + url + ' studios." class="btn btn-sm btn-warning email">Email</a>';
            var tweetLink = '<a href="http://twitter.com/share?text=I scored ' + percent + ' on my AngularJS quiz. ' +
                'Beat my score at &hashtags=ngQuiz&url=' + url + '" target="_blank" class="btn btn-sm btn-info twitter">Tweet</a>';

            var newMarkup = emailLink + tweetLink;
            return $sce.trustAsHtml(newMarkup);
        };

        activate();

        function activate() {
            jDataSer.getLocalQuizData().then(function (res) {
                $scope.myQuestions = res.data;
                $scope.totalQuestions = $scope.myQuestions.length;
            });
        }
    }

    function jDataServiceClass($http) {

        var getQuizData = function () {
            return $http.get('api/quiz/get-quiz-data.php?jtoken=public');
        };

        var getLocalQuizData = function () {
            return $http.get('quote_data.json')
        };

        var googleCloudSqlContact = function (data) {
            var email = encodeURIComponent(data.email);
            var name = encodeURIComponent(data.name);
            var company = encodeURIComponent(data.company);
            var message = encodeURIComponent(data.message);
            var number = encodeURIComponent(data.number);
            var estimatedYearlySalesAllChannels = encodeURIComponent(data.companySnapshot[0]);
            var estimatedMonthlySalesAmazon = encodeURIComponent(data.companySnapshot[1]);
            var annualMarketingBudget = encodeURIComponent(data.companySnapshot[2]);
            var monthlyMarketingBudgetAmazon = encodeURIComponent(data.companySnapshot[3]);
            var summaryExperience = encodeURIComponent(data.amazonGoals[0]);
            var amazonGoals = encodeURIComponent(data.amazonGoals[1]);
            var website = encodeURIComponent(data.otherQuestions[0]);
            var numberProductsCompany = encodeURIComponent(data.otherQuestions[1]);
            var numberProductsAmazon = encodeURIComponent(data.otherQuestions[2]);
            var sendEmail = encodeURIComponent("send");

            //-- "Current Sales Channels" is an array, so convert to a str:
            var currentSalesChannelsStr = "";
            data.currentSalesChannels.forEach(function (value) {currentSalesChannelsStr += (value + " __ ");});
            var currentSalesChannels = encodeURIComponent(currentSalesChannelsStr);

            //-- "Amazon Services" also has to be converted to a string:
            var amazonServicesStr = "";
            data.amazonServices.forEach(function (value) {amazonServicesStr += (value + " __ ");});
            var amazonServices = encodeURIComponent(amazonServicesStr);

            //-- send the request:
            var action = encodeURIComponent('gcloud-create');
            var reqStr = '/?action=' + action + '&name=' + name + '&email=' + email +
                '&number=' + number + '&message=' + message +
                '&current-selling-channels=' + currentSalesChannels +
                '&estimated-yearly-sales-all-channels=' + estimatedYearlySalesAllChannels +
                '&estimated-monthly-sales-amazon=' + estimatedMonthlySalesAmazon +
                '&annual-marketing-budget-for-company=' + annualMarketingBudget +
                '&monthly-budget-on-amazon=' + monthlyMarketingBudgetAmazon +
                '&summary-of-experiences=' + summaryExperience + '&amazon-goals=' + amazonGoals +
                '&amazon-services=' + amazonServices + '&website=' + website +
                '&number-of-products=' + numberProductsCompany + '&send-email=' + sendEmail +
                '&number-of-products-on-amazon=' + numberProductsAmazon + '&company=' + company;
            console.log("jha - request string = " + reqStr);
            return $http.get(reqStr);
        };

        var createHubspotContact = function (data) {
            var email = encodeURIComponent(data.email);
            var name = encodeURIComponent(data.name);
            var company = encodeURIComponent(data.company);
            var message = encodeURIComponent(data.message);
            var number = encodeURIComponent(data.number);
            // each answer has an id, the index of the array is the answers id.
            var estimatedYearlySalesAllChannels = encodeURIComponent(data.companySnapshot[0]);
            var estimatedMonthlySalesAmazon = encodeURIComponent(data.companySnapshot[1]);
            var annualMarketingBudget = encodeURIComponent(data.companySnapshot[2]);
            var monthlyMarketingBudgetAmazon = encodeURIComponent(data.companySnapshot[3]);
            var summaryExperience = encodeURIComponent(data.amazonGoals[0]);
            var amazonGoals = encodeURIComponent(data.amazonGoals[1]);
            var website = encodeURIComponent(data.otherQuestions[0]);
            var numberProductsCompany = encodeURIComponent(data.otherQuestions[1]);
            var numberProductsAmazon = encodeURIComponent(data.otherQuestions[2]);

            //-- Current Sales Channels is an array, so convert to a str:
            var currentSalesChannelsStr = "";
            data.currentSalesChannels.forEach(function (value) {
                currentSalesChannelsStr += (value + " __ ");
            });
            var currentSalesChannels = encodeURIComponent(currentSalesChannelsStr);

            //-- Amazon Services also has to be converted to a string:
            var amazonServicesStr = "";
            data.amazonServices.forEach(function (value) {
                amazonServicesStr += (value + " __ ");
            });
            var amazonServices = encodeURIComponent(amazonServicesStr);

            //-- send the request:
            var action = encodeURIComponent('hubspot');
            return $http.get('php/hubspot1.php?action=' + action + '&name=' + name + '&email=' + email +
                '&number=' + number + '&message=' + message +
                '&current-selling-channels=' + currentSalesChannels +
                '&estimated-yearly-sales-all-channels=' + estimatedYearlySalesAllChannels +
                '&estimated-monthly-sales-amazon=' + estimatedMonthlySalesAmazon +
                '&annual-marketing-budget-for-company=' + annualMarketingBudget +
                '&monthly-budget-on-amazon=' + monthlyMarketingBudgetAmazon +
                '&summary-of-experiences=' + summaryExperience + '&amazon-goals=' + amazonGoals +
                '&amazon-services=' + amazonServices + '&website=' + website +
                '&number-of-products=' + numberProductsCompany + '&number-of-products-on-amazon=' + numberProductsAmazon
            );
        };

        var testOne = function (data) {
            var email = encodeURIComponent(data.email);
            var name = encodeURIComponent(data.name);
            return $http.get('/quote/test/one/?email='+email+'&name='+name);
        };

        return {
            getQuizData: getQuizData,
            getLocalQuizData: getLocalQuizData,
            createHubspotContact: createHubspotContact,
            googleCloudSqlContact: googleCloudSqlContact,
            testOne: testOne
        }
    }

    //#endregion
}());