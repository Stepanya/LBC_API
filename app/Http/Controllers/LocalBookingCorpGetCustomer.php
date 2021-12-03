<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;


use DateTime;

class LocalBookingCorpGetCustomer extends Controller
{
    public function __construct() {
        $this->client = new \GuzzleHttp\Client(
            [
             'base_uri' => 'https://corptntbookingapi.azurewebsites.net/accountverify/api/CheckCustomer', 
             'http_errors' => false, 
             'headers' => 
                [ 
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ]
            ]
        );
    }

    public function sendToLBC(Request $request) {
        
        if (!$request->filled('CustomerAccountNo')) {
            return response()->json(['message' => 'Customer account number cannot be empty.'], 500);
        }

        $response = $this->client->get('?CustomerAccountNo=' . $request->CustomerAccountNo);
        $response_body = json_decode($response->getBody()->getContents());

        // if(isset($response_body->Id)) {

        // }
        
        $status_code = $response->getStatusCode();
        //send response back to process
        if ($status_code == 200) {
            $payload[] = $response_body;
            return response()->json($payload, $status_code);
        } elseif ($status_code == 408) {
            return response()->json(['message' => 'Request to LBC has timed out.'], 408);
        } else {
            return response()->json($response_body, $status_code);
        }
    }
}
