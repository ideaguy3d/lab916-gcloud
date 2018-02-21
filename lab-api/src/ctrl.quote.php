<?php

namespace Google\Cloud\Samples\Bookshelf;

$action = isset($_GET["action"]) ? $_GET["action"] : null;
$sendEmail = isset($_GET["send-email"]) ? $_GET["send-email"] : null;

//              ~ START: Quote Data Model data ~
$email = isset($_GET['email']) ? $_GET['email'] : 'no email created';
$name = isset($_GET['name']) ? $_GET['name'] : 'no name entered';
$company = isset($_GET['company']) ? $_GET["company"] : 'no company entered';
$number = isset($_GET['number']) ? $_GET["number"] : 'no number entered';
$message = isset($_GET['message']) ? $_GET["message"] : 'no message written';
$currentSellingChannels = isset($_GET["current-selling-channels"]) ? $_GET["current-selling-channels"] : "no sales channels entered";
$monthlyAmazonSalesEstimate = isset($_GET["estimated-monthly-sales-amazon"]) ? $_GET["estimated-monthly-sales-amazon"] : "unknown monthly sales";
$monthlyBudgetAmazon = isset($_GET["monthly-budget-on-amazon"]) ? $_GET["monthly-budget-on-amazon"] : "unknown marketing budget";
$yearlySalesAllChannels = isset($_GET["estimated-yearly-sales-all-channels"]) ? $_GET["estimated-yearly-sales-all-channels"] : 'unknown yearly sales';
$yearlyMarketingBudget = isset($_GET["annual-marketing-budget-for-company"]) ? $_GET["annual-marketing-budget-for-company"] : "unkown annual marketing budget";
$amazonServices = isset($_GET["amazon-services"]) ? $_GET["amazon-services"] : "no amazon services selected";
$numProdCompany = isset($_GET["number-of-products"]) ? $_GET["number-of-products"] : "unknown number of company products";
$numProdAmazon = isset($_GET["number-of-products-on-amazon"]) ? $_GET["number-of-products-on-amazon"] : "unknown number of amazon products";
$amazonGoals = isset($_GET["amazon-goals"]) ? $_GET["amazon-goals"] : "amazon goals not entered";
$amazonExp = isset($_GET["summary-of-experiences"]) ? $_GET["summary-of-experiences"] : null;
$website = isset($_GET["website"]) ? $_GET["website"] : null;
//              ~ END: Quote Data Model data ~

if ($action === "gcloud-quote-create") {
    echo " - in googleQuoteContactF() from ctrl.quote.php file - ";
    $model = $app['quote.model']($app);
    
    $quoteDate = date("Ymd");

    $quoteData = [ // c = column
        "quote_date" => $quoteDate, // c0
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
        "number_products_in_company" => $numProdCompany, // c12
        "number_products_on_amazon" => $numProdAmazon, // c13
        "amazon_goals" => $amazonGoals, // c14
        "amazon_experience" => $amazonExp, // c15
        "website" => $website, // c16
    ];

    $createId = $model->create($quoteData);
    echo "quotedId = $createId";
}

if ($sendEmail === "send") {
    echo "\n<br> - In sendEmail action... \n<br> sendEmail = $sendEmail; email = $email; message = $message;\n<br>";
    $to = "hello@lab916.com";
    $subject = "Quote Questionnaire Answers";
    $messageBody = $message . "<br>\n - email from $email";
    $from = $email;

    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'From: ' . $from . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

    if (mail($to, $subject, $messageBody, $headers)) {
        echo "\n<br> - email sent successfully \n<br>";
    }
    else {
        echo "\n<br> - email did not send :( \n<br>";
    }
}
