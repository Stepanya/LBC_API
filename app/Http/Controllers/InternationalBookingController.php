<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;

class InternationalBookingController extends Controller
{
    public function __construct() {
        $this->client = new \GuzzleHttp\Client(
            [
             'base_uri' => 'http://dwebsrv02.lbcapps.com/onlinebooking2/api/trackingno', 
             'http_errors' => false, 
             'headers' => 
                [ 
                    'Authorization' => 'Basic TEJDRXhwcmVzc09ubGluZUJvb2tpbmdJbnQ6a25zZGY2NkRTRkdTNmFzZFZGNlNBNjY0U0E=',
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ]
            ]
        );
    }

    public function convertPayload(Request $request) {
        // Casts the parameters to it's proper type
        $request->merge([
            "ConsigneeId" => intval($request->ConsigneeId),
            "DeliveryZoneId" => intval($request->DeliveryZoneId),
            "IsTnT" => intval($request->IsTnT),
            "PaymentModeId" => intval($request->PaymentModeId),
            "ProductId" => intval($request->ProductId),
            "ProductLineId" => intval($request->ProductLineId),
            "ProductTypeId" => intval($request->ProductTypeId),
            "ServiceTypeId" => intval($request->ServiceTypeId),
            "ShipmentModeId" => intval($request->ShipmentMode),
            "ShipperBarangayOrVillageId" => intval($request->ShipperBrangayorVillageId),
            "ShipperDeliveryZoneId" => intval($request->ShipperDeliveryZoneId),
            "ShipperId" => intval($request->ShipperId),
            "ShipperProvinceOrStateId" => intval($request->ShipperProvinceOrStateId),
            "ShipperTownOrCityId" => intval($request->ShipperTownOrCityId),
            "StatusId" => intval($request->StatusId),
            "ParentStatusId" => intval($request->ParentStatusId),
            "DeliveryTeamPickUpTimeId" => intval($request->DeliveryTeamPickupTimeId),
            "TotalAmount" => intval($request->TotalAmount),
            "BranchId" => intval($request->BranchId),
            "SourceBranchId" => intval($request->SourceBranchId)
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
