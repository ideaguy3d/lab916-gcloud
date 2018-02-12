<?php

namespace Google\Cloud\Samples\Bookshelf;

use Google\Cloud\Samples\Bookshelf\DataModel\DataModelInterface;

$action = isset($_GET['action']);
$sendEmail = isset($_GET["send-email"]) ? $_GET["send-email"] : null;

// ~ START Quote Data Model data ~
$email = isset($_GET['email']) ? $_GET['email'] : 'no email created';
$name = isset($_GET['name']) ? $_GET['name'] : 'no name entered';
$company = isset($_GET['company']) ? $_GET["company"] : 'no company entered';
$number = isset($_GET['number']) ? $_GET["number"] : 'no number entered';
$message = isset($_GET['message']) ? $_GET["message"] : 'no message written';
$currentSellingChannels = isset($_GET["current-sales-channels"]) ? $_GET["current-sales-channels"] : "no sales channels entered";
$monthlyAmazonSalesEstimate = isset($_GET["estimated-monthly-sales-amazon"]) ? $_GET["estimated-monthly-sales-amazon"] : "unknown monthly sales";
$monthlyBudgetAmazon = isset($_GET["monthly-budget-on-amazon"]) ? $_GET["monthly-budget-on-amazon"] : "unknown marketing budget";
$yearlySalesAllChannels = isset($_GET["estimated-yearly-sales-all-channels"]) ? $_GET["estimated-yearly-sales-all-channels"] : 'unknown yearly sales';
$yearlyMarketingBudget = isset($_GET["annual-marketing-budget-for-company"]) ? $_GET["annual-marketing-budget-for-company"] : "unkown annual marketing budget";
$amazonServices = isset($_GET["amazon-services"]) ? $_GET["amazon-services"] : "no amazon services selected";
$numProdCompnay = isset($_GET["number-of-products"]) ? $_GET["number-of-products"] : "unknown number of company products";
$numProdAmazon = isset($_GET["number-of-products-on-amazon"]) ? $_GET["number-of-products-on-amazon"] : "unknown number of amazon products";
$amazonGoals = isset($_GET["amazon-goals"]) ? $_GET["amazon-goals"] : "amazon goals not entered";
$amazonExp = isset($_GET["summary-of-experiences"]) ? $_GET["summary-of-experiences"] : null;
$website = isset($_GET["website"]) ? $_GET["website"] : null;
// ~ END Quote Data Model data ~

if ($action) {
    if ($action == "gcloud-create") {
        echo "in googleQuoteContactF() :)";
        $model = $app['quote.model']($app);
        $quoteData = [ // c = column
            "email" => $email, // c1
            "name" => $name, // c2
            "company" => $company, // c3
            "number" => $number, // c4
            "message" => $message, // c5
            "current_selling_channels" => $currentSellingChannels, // c6
            "monthly_amazon_sales_estimate" => $monthlyAmazonSalesEstimate, // c7
            "monthly_budget_amazon" => $monthlyBudgetAmazon, // c8
            "yearly_sales_all_channels" => $yearlySalesAllChannels, // c9
            "yearly_marketing_budget" => $yearlyMarketingBudget, // c10
            "amazon_services" => $amazonServices, // c11
            "number_products_in_company" => $numProdCompnay, // c12
            "number_products_on_amazon" => $numProdAmazon, // c13
            "amazon_goals" => $amazonGoals, // c14
            "amazon_experience" => $amazonExp, // c15
            "website" => $website, // c16
        ];

        $createId = $model->create($quoteData);
        echo "quotedId = $createId";
    }
}

echo " - sendEmail = $sendEmail; email = $email; message = $message;";

if ($sendEmail == "send") {
    $to = "julius@lab916.com";
    $subject = "Quote Questionnaire Answers";
    if (mail($to, $subject, $message, $email)) {
        echo " - email sent successfully";
    } else echo " - email did not send :(";
} else echo " - 'send-email' request parameter is incorrect";
