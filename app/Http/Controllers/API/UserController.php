<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
class UserController extends Controller
{
    
    public function register( Request $request){
        $validated=$request->validate([
            "name"=>"required",
            "email"=>"required|email",
            "password"=>"required",
            "confirm_password"=>"required|same:password"
        ]);

        $data=User::create([
            "name"=>$validated['name'],
            "email"=>$validated['email'],
            "password"=>$validated['password']
        ]);
        return response()->json([
            "message"=>"user created successfully",
            "data"=>$data
        ]);
    }
    public function login(){
        
    }
    public function index(){
        
    }
    public function store(){
        
    }
    public function update(){
        
    }
    public function destroy(){
        
    }

}
