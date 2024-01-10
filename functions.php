<?php
declare(strict_types=1);
require_once 'vendor/autoload.php';
use GuzzleHttp\Client;

/* 
Here's something to start your career as a hotel manager.

One function to connect to the database you want (it will return a PDO object which you then can use.)
    For instance: $db = connect('hotel.db');
                  $db->prepare("SELECT * FROM bookings");
                  
one function to create a guid,
and one function to control if a guid is valid.
*/

function connect(string $dbHotel): object
{
    $dbPath = __DIR__ . '/' . $dbHotel;
    $db = "sqlite:$dbPath";

    // Open the database file and catch the exception if it fails.
    try {
        $db = new PDO($db);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Failed to connect to the database";
        throw $e;
    }
    return $db;
}

function guidv4(string $data = null): string
{
    // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function isValidUuid(string $uuid): bool
{
    if (!is_string($uuid) || (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $uuid) !== 1)) {
        return false;
    }
    return true;
}

function useTransferCode($transferCode, $totalCOST){
    //Check if it is a valid uuid
    if(isValidUuid($transferCode)){
        //Check with the bank if the transfer code is usable
        $client = new GuzzleHttp\Client([
            'base_uri' => 'https://www.yrgopelag.se/centralbank/']);
        $response = $client->request('POST', 'transferCode', [
            'form_params' => [
                'transferCode' => $transferCode,
                'totalcost' => $totalCOST,
            ]
        ]);

        $responseDecoded = json_decode($response->getBody()->getContents());
        //If it is a success, then use the transfer code
        if($response->getStatusCode() == 200 && !isset($responseDecoded->error)){
            $depositRequest = $client->request('POST', 'deposit', [
                'form_params' => [
                    'transferCode' => $transferCode,
                    'user' => 'KarolinaL',
                ]
            ]);
            // if the deposit was a success, we return true and can proceed with the booking
            if($depositRequest->getStatusCode() == 200){
                return true;
            }
        } else {
            return false;
        }
        //If for any reasen there was an error, return false and stop the registration
    } return false;
}

function getTransferCode(){

    $client = new GuzzleHttp\Client([
        'base_uri' => 'https://www.yrgopelag.se/centralbank/']);

    $response = $client->request('POST', 'withdraw', [
        'form_params' => [
            'user' => 'Rune',
            'api_key' => '9ca1e3d1-aa16-4455-9936-739984164f40',
            'amount' => '40'
        ]
    ]);
    echo $response->getStatusCode();           // 200
    $result = json_decode($response->getBody()->getContents());                 // {"type":"User"...'

    print_r($result);
    //useTransferCode($result->transferCode, $result->amount);
}
//getTransferCode();