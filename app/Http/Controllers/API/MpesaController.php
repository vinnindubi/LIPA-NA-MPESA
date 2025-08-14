<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MpesaController extends Controller
{
    public function generateAccessToken(){
 
        $consumer_key="QP1RAVsnfef9ua8VMX7q2G43YfzRdFiWGzO58n0DGLGbBU92";
        $consumer_secret="DezlfTUipb2vmGAQXdci9EY01GW6QL5WP4kMDfI9KAA7qXF0sDEEl0yMtE7jvfA1";

        $ch = curl_init('https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Basic ' . base64_encode($consumer_key.':'.$consumer_secret)
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $response = curl_exec($ch);
        $result= json_decode($response);
       return $result->access_token;
    }
    public function stkPush(){
            $access_token=$this->generateAccessToken();
        $BusinessShortCode= 174379;
        $passkey='bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
        //$timestamp = Carbon::rawParse('now')->format('YmdHms');

        //$password= base64_encode($BusinessShortCode.$passkey.$timestamp);
        $amount=1;

        $ch = curl_init('https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
           "Authorization: Bearer $access_token",
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            "BusinessShortCode"=>$BusinessShortCode,
            // password = as a result of .base64_encode($businessShortCode.$passkey.$timestamp)
            "Password"=>"MTc0Mzc5YmZiMjc5ZjlhYTliZGJjZjE1OGU5N2RkNzFhNDY3Y2QyZTBjODkzMDU5YjEwZjc4ZTZiNzJhZGExZWQyYzkxOTIwMjUwODE0MTMxMzU3",
            //"password"=>$password,
            "Timestamp"=> "20250814131357",
            "TransactionType"=> "CustomerPayBillOnline",
            "Amount"=> $amount,
            "PartyA"=> 254705248170,
            "PartyB"=>174379,
            "PhoneNumber"=> 254705248170,
            "CallBackURL"=> "https://mydomain.com/path",
            "AccountReference"=> "VinniBiz",
            "TransactionDesc"=> "Payment of a car" 
        ]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response     = curl_exec($ch);
curl_close($ch);
echo $response;
    }
}
