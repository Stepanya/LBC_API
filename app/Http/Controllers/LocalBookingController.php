<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;

class LocalBookingController extends Controller
{
    public function __construct() {
        $this->client = new \GuzzleHttp\Client(
            [
             'base_uri' => 'http://dwebsrv02.lbcapps.com/onlinebookingdev/api/RetailDomesticBooking', 
             'http_errors' => false, 
             'headers' => 
                [ 
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ]
            ]
        );
    }

    public function convertPayload(Request $request) {
        // Casts the parameters to it's proper type
        $request->merge([
            "ShipmentMode" => intval($request->ShipmentMode),
            "ShipperContactNumber" => intval($request->ShipperContactNumber),
            "ShipperSendSMS" => intval($request->ShipperSendSMS),
            "ShipperMobileNumber" => intval($request->ShipperMobileNumber),
            "ProductLine" => intval($request->ProductLine),
            "ServiceMode" => intval($request->ServiceMode),
            "CODAmount" => floatval($request->CODAmount),
            "ConsigneeContactNumber" => intval($request->ConsigneeContactNumber),
            "ConsigneeMobileNumber" => intval($request->ConsigneeMobileNumber),
            "ConsigneeSendSMS" => intval($request->ConsigneeSendSMS),
            "ReferenceNoTwo" => intval($request->ReferenceNoTwo),
            "Quantity" => intval($request->Quantity),
            "PKG" => intval($request->PKG),
            "ACTWTKgs" => floatval($request->ACTWTKgs),
            "LengthCM" => floatval($request->LengthCM),
            "WidthCM" => floatval($request->WidthCM),
            "HeightCM" => floatval($request->HeightCM),
            "VolWTcbm" => floatval($request->VolWTcbm),
            "CBM" => floatval($request->CBM),
            "ChargeableWT" => floatval($request->ChargeableWT),
            "DeclaredValue" => floatval($request->DeclaredValue),
            "Commodity" => intval($request->Commodity),
            "ForCrating" => intval($request->ForCrating),
            "Paymentmode" => intval($request->Paymentmode),
            "EstimatedFreightRate" => floatval($request->EstimatedFreightRate)
        ]);
        // Converts the payload into an object array
        $payload[] = $request->all();
        // Send request to LBC
        return $this->sendToLBC($payload);
    }

    public function sendToLBC($payload) {

        $response = $this->client->post('',
            ['body' => json_encode($payload)]
        );
        
        $status_code = $response->getStatusCode();
        
        if ($status_code) {
            return response()->json(json_decode($response->getBody()->getContents()), $status_code);
        }
    }
}
