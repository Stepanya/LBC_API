<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;

class VoucherAPI extends Controller
{
    public function __construct() {
        $this->client = new \GuzzleHttp\Client(
            [
             'base_uri' => 'http://psrv01dis.cloudapp.net:8888/POS/ValidatePromoCodeList', 
             'http_errors' => false, 
             'headers' => 
                [ 
                    'Content-Type' => 'application/xml',
                    'Accept' => 'application/xml'
                ]
            ]
        );
    }

    public function getVoucher(Request $request) {
        
        if (!$request->filled('promoCodes')) {
            return response()->json(['message' => 'Promo code cannot be empty.'], 500);
        }

        $response = $this->client->get('?promoCodes=' . $request->promoCodes);
        $status_code = $response->getStatusCode();
        $response_body = $response->getBody()->getContents();
        $responseXml = simplexml_load_string($response_body);
        $payload[] = $responseXml->CmsPromoCode;
        
        //send response back to process
        if ($status_code == 200) {
            return response()->json($payload, $status_code);
        } elseif ($status_code == 408) {
            return response()->json(['message' => 'Request to LBC has timed out.'], 408);
        } else {
            return response()->json($response_body, $status_code);
        }
    }
}

