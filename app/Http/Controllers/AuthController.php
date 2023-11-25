<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Illuminate\Validation\Rule;


class AuthController extends Controller
{
    public function get_users(){
        $users = User::all();
        if($users->isEmpty()){
            return response()->json()->json([
                'message' => 'No users found',
                'status' => 404,
                'users' => array(),
            ]);
        }else{
            return response()->json([
                'message' => 'list of all users',
                'users' => $users,
                'status' => 200,
            ]);
        }
    }

    public function save_user(Request $request){
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ]);
        if($validate->fails()){
            $response = array('response' => $validate->messages(), 'success' => false);
            return response()->json($response);
        }else{
            $data['name'] = $request->name;
            $data['email'] = $request->email;
            $data['password'] = bcrypt($request->password);
            $data['created_at'] = now();
            $insert = User::create($data);
            if ($insert) {
                return response()->json([
                    'success' => 'Data was insert',
                    'data' => $insert,
                ], 200);
            } else {
                return response()->json(['error' => 'Data was not insert']);
            }
        }
    }

    public function edit($id){
        $userData = User::findOrFail($id);
        return response()->json(['user' => $userData], 200);
    }

    public function update(Request $request)
    {
        $id = $request->user_id;

        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|', [
                Rule::unique('users', 'email')->ignore($id)
            ],
        ]);
        if($validate->fails()){
            $response = array('response' => $validate->messages(), 'success' => false);
            return response()->json($response);
        }else{
            $data['name'] = $request->name;
            $data['email'] = $request->email;
            $update = User::findOrFail($id)->update($data);
            $user = User::findOrFail($id);
            if ($update) {
                return response()->json([
                    'success' => 'data updated',
                    'user' => $user
                ]);
            }
        }
    }

    public function delete(Request $request)
    {
        $id = $request->user_id;
        if (User::findOrFail($id)->delete()) {
            return response()->json([
                'success' => 'Data was deleted',
                'id' => $id,
            ]);
        }
    }

}
