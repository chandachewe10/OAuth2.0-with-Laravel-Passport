<?php

namespace App\Http\Controllers;
use Laravel\Passport\ClientRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use App\Models\User;
class oath2 extends Controller
{
    //
    public function findOath2($id,clientRepository $clientRepository){
    return "Hi";   
    }
}
