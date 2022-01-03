<?php
use Laravel\Passport\ClientRepository;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\oath2;

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


Route::get('/', function () {
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
   $credentials = $clientRepository->forUser(1345);
   return view('credentialsOath2',compact('credentials'));
   //return $credentials;

});





     });

});




require __DIR__.'/auth.php';
