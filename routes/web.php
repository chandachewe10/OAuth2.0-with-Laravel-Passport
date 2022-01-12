<?php
use Laravel\Passport\ClientRepository;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\oath2;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/index', function () {
    return view('welcome');
});



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');




Route::group(['prefix' => 'developers'], function() {


Route::group(['middleware' => 'auth'], function() {


/*This route will call the client_credentials view where a Developer will choose
Either to create the Oath2.0 or the Personal Access Token */

     Route::get('/application', function(){
     return view('client_credentials');
     })->name('developers.application');


//This route will call the create_app view where a Developer will create the Oath2.0

     Route::get('/create_app', function(){
        return view('create_app');
        })->name('developers.create_app');
   


//This function will get Data from the clientsOath view and create the client_user and client_id 

Route::get('oathClients', function (clientRepository $clientRepository,Request $request) {
  $clientRepository->create($request->user_id, $request->app_name, $request->redirect_uri);

  //Show Status success to the dashboard if successfull
  return redirect('dashboard')->with('success', 'CLIENT_ID and CLIENT_SECRET created successfully.'); 
  
  })->name('developers.oathClients');
  
  




//This route will call the create_personal view where a Developer will create the personal access token

Route::get('/create_personal', function(){
    return view('create_personal');
    })->name('developers.create_personal');




  //This function will get Data from the clientsOath view and create the client_id 
  Route::get('OathPersonal', function (clientRepository $clientRepository, Request $request) {
  $clientRepository->createPersonalAccessClient($request->user_id, $request->app_name, $request->redirect_uri);

    
  //Show Status success to the dashboard if successfull
  return redirect('dashboard')->with('personalClientSuccess', 'CLIENT_ID and CLIENT_SECRET created successfully.'); 
   
    })->name('developers.OathPersonal');






//This route will search for the clientS_id and clients_secret in the DB 


                
Route::get('findOath/{id}', function (clientRepository $clientRepository,$id) {
   $credentials = $clientRepository->forUser(decrypt($id));

   //Check if the user is not found

   if(is_null($credentials)){
    Session::flash('warning', 'Whoops you have not created any App with us.'); 
    return redirect('dashboard');

     //Else redirect if found

   }
   else{
    return view('credentialsOath2',compact('credentials'));
   
   }
  

});





//Route for Creating the authorisation Id
 //Set Up the Credentials here,You can Customise this in .env 
  
 Route::get('/redirect', function (Request $request) {
   

  $request->session()->put('state', $state = Str::random(40));
  
  $query = http_build_query([
      'client_id' => $request->client_id,
      'redirect_uri' =>  'http://localhost/Hackathon/Hackathon/public/developers/callback',   // Should be the same with one you used when you were making the client_id and client_secret
      'response_type' => 'code',
      'scope' => '',
      'state' => $state,
    
  ]);
  
  //No need to define the oauth/authorise route at the end of this url, it is defined by laravel 
  return redirect('http://localhost/Hackathon/Hackathon/public/oauth/authorize?'.$query);
  
  })->name('developers.redirect');   


  
  //Recieve The Authorisation code here , compare the states and proceed 
  
  Route::get('/callback', function (Request $request, clientRepository $clientRepository) {
    //Retrive the users credentials (client_id and the client_secret)
    $credentials = $clientRepository->forUser(Auth::user()->nrc_number);

    $state = $request->session()->pull('state');


    throw_unless(
        strlen($state) > 0 && $state === $request->state,
        InvalidArgumentException::class
    );
 //No need to define the oauth/token route at the end of this url, it is defined by laravel 
    $response = Http::asForm()->post('http://localhost/Hackathon/Hackathon/public/oauth/token', [
        'grant_type' => 'authorization_code',
        'client_id' => $credentials->id,
        'client_secret' => $credentials->secret,
        'redirect_uri' => 'http://localhost/Hackathon/Hackathon/public/developers/callback', // Should be the same with one you used when you were making the client_id and client_secret
        'code' => $request->code,
    ]);

    $token = $response->object();
   // return Auth::user();
   // return view('bearerToken',compact('token'));
    $user=User::where('email',"=",Auth::user()->email)->first();
    $user->remember_token = $token->access_token;
    $user->save();
   
    Session::flash('Done', 'Bearer Token Created Successfully.'); 
    return redirect('dashboard');

});






/*This route will call the create_token view where a Developer will enter
the Client id and the client secret in order  to create the Bearer Token */

Route::get('/Bearer_token_creation', function(){
  return view('create_token');
  })->name('developers.create_token');





  

/*This route will call the bearerToken blade view where a Developer will see
the Token which was generated for him/her the blade will show the type of token 
and the expiry date of that particular type of token*/

Route::get('/token_view', function(){
  $token = Auth::user()->remember_token;

  if(is_null($token)){
    Session::flash('token_warning', 'Whooops you have not created any Token with us.'); 
    return redirect('dashboard');
   
  }
  else{
    return view('bearerToken', compact("token"));
  }
  
  })->name('developers.view_token');






     });

});








    


require __DIR__.'/auth.php';
