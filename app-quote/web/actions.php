<?php
/**
 * Created by PhpStorm.
 * User: Lab916
 * Date: 2/2/2018
 * Time: 1:52 PM
 */

include __DIR__ . "/php/connect.php";

$error = "";
$actionGoogle = isset($_GET['action']) ? $_GET['action'] : '';

$name = isset($_GET['name']) ? $_GET['name'] : '';
$number = isset($_GET['number']) ? $_GET['number'] : '';

$email = isset($_GET['email']) ? $_GET['email'] : '';
$validEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
$realEmail = mysqli_real_escape_string($link, $validEmail);

$company = isset($_GET['company']) ? $_GET['company'] : '';
$currentSellingChannels = isset($_GET['current-selling-channels']) ? $_GET['current-selling-channels'] : '';
$monthlyAmazonSalesEstimate = isset($_GET['estimated-monthly-sales-amazon']) ? $_GET['estimated-monthly-sales-amazon'] : '';
$monthlyBudgetAmazon = isset($_GET['monthly-budget-on-amazon']) ? $_GET['monthly-budget-on-amazon'] : '';
$yearlySalesAllChannels = isset($_GET['estimated-yearly-sales-all-channels']) ? $_GET['estimated-yearly-sales-all-channels'] : '';
$yearlyMarketingBudget = isset($_GET['annual-marketing-budget-for-company']) ? $_GET['annual-marketing-budget-for-company'] : '';
$amazonServices = isset($_GET['amazon-services']) ? $_GET['amazon-services'] : '';
$numberProductsInCompany = isset($_GET['number-of-products']) ? $_GET['number-of-products'] : '';
$numberOfProductsOnAmazon = isset($_GET['number-of-products-on-amazon']) ? $_GET['number-of-products-on-amazon'] : '';
$amazonGoals = isset($_GET['amazon-goals']) ? $_GET['amazon-goals'] : '';
$amazonExperience = isset($_GET['summary-of-experiences']) ? $_GET['summary-of-experiences'] : '';
$website = isset($_GET['website']) ? $_GET['website'] : '';

$userId = isset($_GET['user-id']) ? $_GET['user-id'] : '';

if ($actionGoogle == 'google') {
    // a hardcoded password at the moment
    // $someRandomId = rand(0, 1000000);
    $quoteQ = "INSERT INTO quote (name, number, email, company, current_selling_channels, monthly_amazon_sales_estimate, "
        . "monthly_budget_amazon, yearly_sales_all_channels, yearly_marketing_budget, amazon_services, number_products_in_company, "
        . "number_products_on_amazon, amazon_goals, amazon_experience, website) VALUES ('$name','$number', '$email', '$company', '$currentSellingChannels', "
        . "'$monthlyAmazonSalesEstimate', '$monthlyBudgetAmazon', '$yearlySalesAllChannels', '$yearlyMarketingBudget', "
        . "'$amazonServices', '$numberProductsInCompany', '$numberOfProductsOnAmazon', '$amazonGoals', '$amazonExperience', "
        . " '$website')";

    if (mysqli_query($link, $quoteQ)) {
        echo " | user " . $realEmail . " quote created | ";
    } else {
        echo " | ERROR: " . $quoteQ . ", " . mysqli_error($link) . " <:| ";
    }
}

