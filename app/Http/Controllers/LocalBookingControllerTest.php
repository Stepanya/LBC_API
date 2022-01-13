<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;

use DateTime;

class LocalBookingControllerTest extends Controller
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


        if (!$request->filled('ShipmentMode')) {
            return response()->json(['message' => 'Shipment mode cannot be empty. '], 500);
        }    
        // Casts the parameters to it's proper type
        $request->merge([
            "TransactionDate" => (new DateTime($request->TransactionDate))->format('m/d/Y') . " " . date("h:i:s a"),
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
            "EstimatedFreightRate" => floatval($request->EstimatedFreightRate),
            "ConsigneeFirstName" => ($request->ConsigneeFirstName === null) ? "" : $request->ConsigneeFirstName,
            "ConsigneeMiddleName" => ($request->ConsigneeMiddleName === null) ? "" : $request->ConsigneeMiddleName,
            "ConsigneeLastName" => ($request->ConsigneeLastName === null) ? "" : $request->ConsigneeLastName,
            "ConsigneeStBldg" => ($request->ConsigneeStBldg === null) ? "" : $request->ConsigneeStBldg,
            "ConsigneeBrgy" => ($request->ConsigneeBrgy === null) ? "" : $request->ConsigneeBrgy,
            "ConsigneeCityMunicipality" => ($request->ConsigneeCityMunicipality === null) ? "" : $request->ConsigneeCityMunicipality,
            "ConsigneeProvince" => ($request->ConsigneeProvince === null) ? "" : $request->ConsigneeProvince
        ]);
        // Converts the payload into an object array
        $payload[] = $request->all();
        // Send request to LBC
        // return response()->json($payload,500);
        return $this->sendToLBC($payload);
    }

    public function sendToLBC($payload) {

        $response = $this->client->post('',
            ['body' => json_encode($payload)]
        );
        
        $status_code = $response->getStatusCode();
        //send response back to process
        if ($status_code == 200) {
            return response()->json(json_decode($response->getBody()->getContents()), $status_code);
        } elseif ($status_code == 408) {
            return response()->json(['message' => 'Request to LBC has timed out.'], 408);
        } else {
            return response()->json(json_decode($response->getBody()->getContents()), $status_code);
        }
    }
}
