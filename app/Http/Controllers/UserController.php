<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Hash;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::latest()->paginate(10);

        $data = array(
                'status' => 200,
                'data' => $users
            );
        return response()->json($data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $result = $user->save();
        return response()->json($user, 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return [
            "status" => 1,
            "data" =>$users
        ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        
    }


    public function addWalletMoney(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'add_amount'=>['required','numeric', 'min:3','max:100'],
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

        $data = User::find($request->id);
        $data->wallet = $data->wallet+$request->add_amount;
        $data->save();
        return response()->json($data, 200);

    }

    public function buyCookie(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'rate'=> ['required','numeric', 'min:1'],
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

        $data = User::find($request->id);
        $data->wallet = $data->wallet-$request->rate;
        $data->save();
        return response()->json($data, 200);
    }

}
