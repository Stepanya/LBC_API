<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;

use DateTime;

class InternationalBookingSortProducts extends Controller
{
    public function __construct() {
        $this->client = new \GuzzleHttp\Client(
            [
             'base_uri' => 'https://intapi2booking.lbcapps.com/api/BranchProduct', 
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

        if (!$request->filled('BranchId') || !$request->filled('ProductLineId')) {
            return response()->json(['message' => 'Branch Id or Product Line Id cannot be empty. '], 200);
        }    
        
        $response = $this->client->get('?Id=' . $request->BranchId, [
            'auth' => [
                'LBCExpressOnlineBookingInt', 
                'knsdf66DSFGS6asdVF6SA664SA'
            ]
        ]);

        $response_body = json_decode($response->getBody()->getContents(), true);
        $status_code = $response->getStatusCode();
        $response = [];

        foreach($response_body as $key => &$Product) {
            
            if($Product['ProductLineId'] == $request->ProductLineId 
                && $Product['ShipmentModeId'] == $request->ShipmentModeId) {
                array_push($response, $Product);
            }
            
        }   
        
        //send response back to process
        if ($status_code == 200) {
            return response()->json($response, $status_code);
        } elseif ($status_code == 408) {
            return response()->json(['message' => 'Request to LBC has timed out.'], 408);
        } else {
            return response()->json($response, $status_code);
        }
    }
}
