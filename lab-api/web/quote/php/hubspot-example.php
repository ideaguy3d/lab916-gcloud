<?php
/**
 * Created by PhpStorm.
 * User: lab916
 * Date: 12/12/2017
 * Time: 4:07 PM
 */

$arr = array(
    'properties' => array(
        array(
            'property' => 'email',
            'value' => 'apitest@hubspot.com'
        ),
        array(
            'property' => 'firstname',
            'value' => 'hubspot'
        ),
        array(
            'property' => 'lastname',
            'value' => 'user'
        ),
        array(
            'property' => 'phone',
            'value' => '555-1212'
        )
    )
);
$post_json = json_encode($arr);
$hapikey = readline("Enter hapikey: (demo for the demo portal): ");
$endpoint = 'https://api.hubapi.com/contacts/v1/contact?hapikey=' . 'e91ae90f-d873-4207-b316-ab7282b18f4e';
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
echo "curl Errors: " . $curl_errors;
echo "\nStatus code: " . $status_code;
echo "\nResponse: " . $response;