<?php 
declare(strict_types=1);
require_once 'vendor/autoload.php';
use GuzzleHttp\Client;

function getTransferCode(){

    $client = new GuzzleHttp\Client([
        'base_uri' => 'https://www.yrgopelag.se/centralbank/']);

    $response = $client->request('POST', 'withdraw', [
        'form_params' => [
            'user' => 'Rune',
            'api_key' => '9ca1e3d1-aa16-4455-9936-739984164f40',
            'amount' => $_GET['amount'],
        ]
    ]);
    echo $response->getStatusCode();           // 200
    $result = json_decode($response->getBody()->getContents());                 // {"type":"User"...'

    print_r($result);
}

getTransferCode();

?>