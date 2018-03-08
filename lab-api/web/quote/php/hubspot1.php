<?php
/**
 * Created by PhpStorm.
 * User: lab916
 * Date: 12/12/2017
 * Time: 2:03 PM
 */

include ("connect.php");

// header ('Content-type: application/json');
// echo ")]}'\n";

$arr = array(
    'properties' => array(
        array(
            'property' => 'email',
            'value' => $_GET['email']
        ),
        array(
            'property' => 'name',
            'value' => $_GET['firstname']
        ),
        array(
            'property' => 'message',
            'value' => $_GET['message']
        ),
        array(
            'property' => 'phone',
            'value' => $_GET['number']
        ),
        array(
            'property' => 'current_selling_channels',
            'value' => $_GET['current-selling-channels']
        ),
        // Company Snapshot slide
        array(
            'property' => 'estimated_yearly_sales_all_channels',
            'value' => $_GET['estimated-yearly-sales-all-channels']
        ),
        array(
            'property' => 'estimated_monthly_sales_amazon',
            'value' => $_GET['estimated-monthly-sales-amazon']
        ),
        array(
            'property' => 'annual_marketing_budget_for_company',
            'value' => $_GET['annual-marketing-budget-for-company']
        ),
        array(
            'property' => 'monthly_budget_on_amazon',
            'value' => $_GET['monthly-budget-on-amazon']
        ),
        array(
            'property' => 'summary_of_experiences',
            'value' => $_GET['summary-of-experiences']
        ),
        array(
            'property' => 'amazon_goals',
            'value' => $_GET['amazon-goals']
        ),
        array(
            'property' => 'amazon_services',
            'value' => $_GET['amazon-services']
        ),
        array(
            'property' => 'website',
            'value' => $_GET['website']
        ),
        array(
            'property' => 'number_of_products',
            'value' => $_GET['number-of-products']
        ),
        array(
            'property' => 'number_of_products_on_amazon',
            'value' => $_GET['number-of-products-on-amazon']
        )
    )
);

$post_json = json_encode($arr);
$endpoint = 'https://api.hubapi.com/contacts/v1/contact?hapikey=' . $hubspotConnectId;

$ch = @curl_init();
@curl_setopt($ch, CURLOPT_POST, true);
@curl_setopt($ch, CURLOPT_POSTFIELDS, $post_json);
@curl_setopt($ch, CURLOPT_URL, $endpoint);
@curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
@curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = @curl_exec($ch);
$status_code = @curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_errors = curl_error($ch);
@curl_close($ch);

echo "\n curl Errors: " . $curl_errors;
echo "\n Status code: " . $status_code;
echo "\n Response: " . $response;



