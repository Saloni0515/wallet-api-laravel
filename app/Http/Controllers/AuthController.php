<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Hash;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Auth;

class AuthController extends Controller
{
    public function register(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => ['required','string'],
            'email'=> ['required','email', 'unique:users'],
            'password'=> ['required','string', 'min:6', 'max:25'],
            'password_confirmation' => ['required','same:password'],
        ]);

        if($validator->fails())
        {
            $messages = $validator->messages();
            $errors = $messages->all();
            return response()->json([
                "status" => 404,
                "error" => 'validation_error',
                "message" => $validator->errors(),
            ],Response::HTTP_BAD_REQUEST);
        }

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
        ]);


        $unique_code = $request->email.$request->password;
        $user_token = base64_encode($unique_code);

        User::where('id', $user->id)->update([
            'access_token'   => $user_token
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'User registered successfully.',
            'access_token' => $user_token,
            'data' => $user
        ]);
    }

    public function login(Request $request){

        $validator = Validator::make($request->all(), [
            'email'=> ['required','email'],
            'password'=> ['required'],
        ]);

        if($validator->fails())
        {
            $messages = $validator->messages();
            $errors = $messages->all();
            return response()->json([
                "status" => 404,
                "error" => 'validation_error',
                "message" => $validator->errors(),
            ],Response::HTTP_BAD_REQUEST);
        }

       $user = User::where('email',$request->email)->first();
       if(!$user){
           return response()->json([
               "status" => 401,
               "error" => 'validation error',
               "message" => 'User not found',
           ],Response::HTTP_BAD_REQUEST);
       }

       if (!Hash::check($request->password,$user->password)) {
           return response()->json([
               "status" => 401,
               "error" => 'Authentication error',
               "message" => 'Unauthorised',
           ],Response::HTTP_BAD_REQUEST);
           return response()->json(['error' => 'Unauthorised'], 401);
        } else {
           auth()->login($user);
           $token = auth()->user()->access_token;
           return response()->json([
               'status' => 200,
               'message' => 'User login successfully.',
               'token' => $token,
           ]);
        } 
    }
}
