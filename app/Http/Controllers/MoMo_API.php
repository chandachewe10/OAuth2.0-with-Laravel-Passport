<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\payments_history;


class MoMo_API extends Controller

{
    
    public function uuidv4User(Request $request){
      
           
       
     /*Making the unique UUID_V4 Equivalent to the API user 
     This Recource ID for the API user to be created is  
     UUID version 4 and is required.
     */

      $response =  Http::get('https://www.uuidgenerator.net/api/version4');
      $uuidv4User = $response;
     


     /*Making unique UUID_V4 Equivalent to the API user    
     This will be used during the request to pay from a 
     customer during testing in the sandbox
     */

        $uuidv4Payments = Http::get('https://www.uuidgenerator.net/api/version4');
        $paymentUUIDV4 =  $uuidv4Payments;

    
     /*Registering the API USER to the MoMo SandBox
     Used to create an API user in the sandbox target environment.
     */

     $apiUser = Http::withHeaders([
        "Content-Type" => "application/json",
        "Ocp-Apim-Subscription-Key"=>config('momoAPI.subscription_key'),
        "X-Reference-Id"=> "$uuidv4User",
        
    ])->post('https://sandbox.momodeveloper.mtn.com/v1_0/apiuser', [
    
    
        'providerCallbackHost' => config('momoAPI.callback_host'),
    
    ]);
    
     

    /*Used to create an API key for an API user in the sandbox
     target environment. Creating the API_KEY using Laravel HTTP Client
     */

    $API_KEY = Http::withHeaders([
        "Ocp-Apim-Subscription-Key"=> config('momoAPI.subscription_key'),
        "X-Reference-Id"=>"$uuidv4User",
        
        ])->post('https://sandbox.momodeveloper.mtn.com/v1_0/apiuser/'.$uuidv4User.'/apikey'
        );
     
       
    $API = $API_KEY->object();
     //Value of the API KEY
     $apiKey = $API->apiKey;




     
/*CREATING ACCESS TOKEN (BEARER TOKEN) FROM APIKEY AND X-Reference-Id
WE USE BASIC AUTH WERE X-Reference-Id IS USERNAME AND APIKEY IS PASSWORD
*/

$credentials = base64_encode($uuidv4User.":".$apiKey);

$Token = Http::withHeaders([
    'Content-Type' => 'application/json',
    "Ocp-Apim-Subscription-Key"=> config('momoAPI.subscription_key'),
    'Authorization' => 'Basic ' . $credentials,
    
    
])->post('https://sandbox.momodeveloper.mtn.com/collection/token/');

 $access_token = $Token->object();

 //Value of the token

 $token = $access_token->access_token;





 /*Request to Pay service is used for requesting a payment from a customer (Payer).
 This can be used by e.g. an online web shop to request a payment for a customer. 
 The customer is requested to approve the transaction on the customer client.
 The transaction will be executed once the payer has authorized the payment.
 The requesttopay will be in status PENDING until the transaction is authorized 
 or declined by the payer or it is timed out by the system. Status of the transaction 
 can be validated by using the GET /requesttopay/<resourceId> 
 */

//Payer Data
$payer = json_decode(json_encode($request->payer));

//PartyId Type
$msisdn = $payer->partyIdType;

//parytId
$number = $payer->partyId;


 
$requestToPay = Http::withHeaders([
    "Content-Type" => "application/json",
    "Ocp-Apim-Subscription-Key" => config('momoAPI.subscription_key'),
    "Authorization" => "Bearer ".$token,
    "X-Reference-Id" => "$paymentUUIDV4",
    "X-Callback-Url" => config('momoAPI.callback_url'),
    "X-Target-Environment" => "sandbox",

])->post('https://sandbox.momodeveloper.mtn.com/collection/v1_0/requesttopay',[
    

   
    "amount" => "$request->amount",
    "currency" => "$request->currency",
    "externalId" => "$request->externalId",
    "payer" => [
      "partyIdType" => "$msisdn",
      "partyId" => "$number",
    ],  
    "payerMessage" => "$request->payerMessage",
    "payeeNote" => "$request->payeeMessage",
  



]);


//If the payment has been accepted (202) enter the records of payments in the database  
if($requestToPay->status() == 202){
   
    $payments = payments_history::create([
      
    "amount" => "$request->amount",
    "currency" => "$request->currency",
    "externalId" => "$request->externalId",
    "partyIdType" => "$msisdn",
    "partyId" => "$number",
    "payerMessage" => "$request->payerMessage",
    "payeeMessage" => "$request->payeeNote",  
    ]);

}


/*This operation is used to get the status of a request to pay. 
X-Reference-Id that was passed in the post is used as reference to the request.
*/

$paymentStatus = Http::withHeaders([
    "Ocp-Apim-Subscription-Key" => config('momoAPI.subscription_key'),
    "Authorization" => "Bearer ".$token,
    "X-Target-Environment" => "sandbox",
    
    
])->get('https://sandbox.momodeveloper.mtn.com/collection/v1_0/requesttopay/'.$paymentUUIDV4);


//GETTING THE STATUS OF PAYMENT WHETHER IT WAS SUCCESSFULL OR NOT 
return $status = $paymentStatus;






    }
      
}
