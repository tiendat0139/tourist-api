<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'cf_password' => 'required|same:password'
        ];
        if (request()->isCompany) {
            $rules = array_merge($rules, [
                'cpn_name' => 'required',
                'cpn_phone' => 'required|size:10',
                'cpn_addr' => 'required'
            ]);
        }
        $messages = [];
        $validator = Validator::make($request->all(), $rules, $messages, [
            'name' => 'Name',
            'email' => 'Email address',
            'password' => 'Password',
            'cf_password' => 'Confirm password',
            'cpn_name' => 'Company name',
            'cpn_phone' => 'Company phone',
            'cpn_addr' => 'Company address'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'validation_errors' => $validator->errors()
            ]);
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
            if ($request->isCompany) {
                $company = Company::create([
                    'name' => $request->cpn_name,
                    'user_id' => $user->id,
                    'phone' => $request->cpn_phone,
                    'address' => $request->cpn_addr
                ]);
                $token = $user->createToken($user->email .'_Token', ['company:insert'])->plainTextToken;
            } else {
                $token = $user->createToken($user->email .'_Token')->plainTextToken;
            }
        }
        return response()->json([
            'status' => 200,
            'username' => $user->name,

            'token' => $token,
            'message' => 'Register Successfully'
        ]);
    }



    public function login(Request $request){
        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ];
        $messages = [];
        $validator = Validator::make($request->all(), $rules, $messages, [
            'email' => 'Email address',
            'password' => 'Password'
        ]);
        if($validator->fails()){
            return response()->json([
                'validation_errors' => $validator->errors()
            ]);
        } else {
            $user = User::where('email',$request->email)->first();
            if(!$user || Hash::check($user->password, $request->password)){
                return response()->json([
                    'status' => 401,
                    'message' => 'Email or password do not match'
                ]);
            } else {
                if($user->company){
                    $token = $user->createToken($user->email .'_Token', ['company:insert'])->plainTextToken;
                } else {
                    $token = $user->createToken($user->email.'_Token')->plainTextToken;
                }
                return response()->json([
                    'status'=>200,
                    'auth_name' => $user->name,
                    'auth_token'=> $token,
                    'message' => 'Login Successfully'
                ]);
            }
        }
        
    }

    public function logout(){
        // Chú thích cho PHP intelephense hiểu biến $user 
        /** @var \App\Models\MyUserModel $user **/

        $user = auth()->user();
        $user->tokens()->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Sign out Successfully'
        ]);
    }

}
