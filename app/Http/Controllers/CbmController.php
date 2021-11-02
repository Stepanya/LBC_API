<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CbmController extends Controller
{   

    public function __construct() {
        $this->client = new \GuzzleHttp\Client(
            [
             'base_uri' => 'http://dwebsrv02.lbcapps.com/onlinebooking2/api/branchproduct', 
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

    public function computeCbmLocal(Request $request) {

        if ($request->length == '' || $request->width == '' || $request->height == '') {
            return responce()->json(['message' => 'Request parameters cannot be empty']);
        }

        $cbm = $request->length * $request->width * $request->height / 1000000;
        
        $response[] = ["CBM" => $cbm, "VolWTcbm" => $cbm];
        return response()->json($response, 200);
    }

    public function computeCbmInternational(Request $request) {

        $params = [
            'query' => [
               'id' => $request->BranchID
            ]
         ];

        $response = $this->client->get('', $params);
        $status_code = $response->getStatusCode();
        $response = json_decode($response->getBody()->getContents());
       
        if ($status_code != 200) {
            return response()->json($response, $status_code);
        }

        $length = 0;
        $width = 0;
        $height = 0;

        foreach($response as $product) {
            if($product->ProductId == $request->ProductID) {
                $length = floatval($product->Length);
                $width = floatval($product->Width);
                $height = floatval($product->Height);
            }
        }

        $cbm = $length * $width * $height / 1000000;
        
        $proccess_response[] = ["Length" => $length, "Width" => $width, "Height" => $height, "CBM" => $cbm];
        return response()->json($proccess_response, 200);
    }
}
