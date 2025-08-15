<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Env;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class MpesaController extends Controller
{
    public function generateAccessToken(){
 
        $consumer_key= env('CONSUMER_KEY');
        $consumer_secret= env('CONSUMER_SECRET');
        

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
    public function stkPush($data){
        
        $access_token = $this->generateAccessToken();
        $BusinessShortCode= 174379;
        $passkey=env('DARAJA_PASSKEY');
       // $password=env('DARAJA_PASSWORD');
        $timestamp = "20250814153755";
        $password = base64_encode($BusinessShortCode .$passkey. $timestamp);
        $amount= $data['amount'];
        $phoneNumber=$data['PhoneNumber'];
        $payload= json_encode([
            "BusinessShortCode"=>$BusinessShortCode,
            // password = as a result of .base64_encode($businessShortCode.$passkey.$timestamp)
            "Password"=>$password,
            "Timestamp"=> $timestamp,
            "TransactionType"=> "CustomerPayBillOnline",
            "Amount"=> $amount,
            "PartyA"=> $phoneNumber,
            "PartyB"=>174379,
            "PhoneNumber"=> $phoneNumber,
            "CallBackURL"=> "https://e465aea6bd94.ngrok-free.app/api/mpesa/callback",
            "AccountReference"=> "VinniBiz",
            "TransactionDesc"=> "Payment of a car" 
        ]);

        $ch = curl_init('https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
           "Authorization: Bearer $access_token",
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response     = curl_exec($ch);
        curl_close($ch);
        return $response;
}
public function mpesaCallback(Request $request)
{
    // Safaricom sends JSON, so decode it
    $data = json_decode($request->getContent());

    // Log the data for debugging
    Log::info('M-Pesa Callback:', array($data));//we convert the data because it is a stdClass Object.
    
    // Optionally store in DB
    // Transaction::create([
    //     'merchant_request_id' => $data['Body']['stkCallback']['MerchantRequestID'] ?? null,
    //     'checkout_request_id' => $data['Body']['stkCallback']['CheckoutRequestID'] ?? null,
    //     'result_code' => $data['Body']['stkCallback']['ResultCode'] ?? null,
    //     'result_desc' => $data['Body']['stkCallback']['ResultDesc'] ?? null,
    //     'amount' => $data['Body']['stkCallback']['CallbackMetadata']['Item'][0]['Value'] ?? null,
    //     ...
    // ]);
    //$amount=$data['Body']['stkCallback']['CallbackMetadata']['Item'][0];
    $result=$data->Body->stkCallback->CallbackMetadata;
    $amount=$result->Item[0]->Value;
    $mpesaReceiptno=$result->Item[1]->Value;
    $phoneNumber=$result->Item[4]->Value;
    $formattedPhone=str_replace('254','0',$phoneNumber);
    $transDate=$result->Item[3]->Value;
        Payment::create([
            "Amount"=>$amount,
            "MpesaReceiptNumber"=>$mpesaReceiptno,
            "PhoneNumber"=>$formattedPhone,
            "TransactionDate"=>$transDate,
        ]);

    return response()->json([
        'ResultCode' => 0,
         'ResultDesc' => 'Success'
        
    ]);
   
}
 public function stkPushRequest( Request $request){
    return $this->stkPush($request);
   
 }

}
