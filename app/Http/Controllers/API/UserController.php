<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Actions\Fortify\PasswordValidationRules;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
 

    public function login(Request $request)
    {
        try{
            //validasi input
            $request->validate([
                'email'=>'email|required',
                'password'=>'required'
            ]);

            //mengecek credential
            $credentials = request('email','password');
            if(!Auth::attempt([$credentials])){
                return ResponseFormatter:: error([
                    'message'=>'Unautorized',
                ],'Autentification failed',500);
            }

            //jika hash tidak sesuai maka beri error
            $user = User::where('email',$request->email)->first();
            if(!Hash::check($request->password, $user->password,[])){
                throw new Exception('Invalid Credential');
            }
            
            //jika berhasil login maka
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ],'Authenticated');
        }
        catch(Exceptpion $error){
            return ResponseFormatter::error([
                'message' => 'something error',
                'error' => $error
            ],'Authenticated Failed',500);
        }
    }

    public function Register(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required','string','max:255'],
                'email' => ['requierd','string','email','max:255','unique:users'],
                'password' =>$this->passwordRules()
            ]);
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'address' => $request->address,
                'housenumber' => $request->housenumber,
                'phonenumber' => $request->phonenumber,
                'city' => $request->city,
                'password' => Hash::make($request->password),
            ]);
            $user = User::where('email',$request->email)->first();

            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ]);

        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'something went wrong',
                'error' => $error
            ],'Authenticated Failed',500);
        }
    }

    public function logout(Request $request)
    {
        $token = $request-user()->currentAccessToken()->delete();

        return ResponseFormatter::success($token, 'Token Revoked');
    }

    public function fetch(Request $request)
    {
        return ResponseFormatter::succes($request->user(),'Data profile user berhasil ditambah');
    }

    public function updateProfile(Request $request)
    {
        $data = $request->all();

        $user = Auth::user();
        $user->update($data);

        return ResponseFormatter::success($user,'Profile Update');
    }

    public function updatePhoto(Request $request)
    {
        $validator = Validator::make($request->all(),[
            
            'file' => 'required|image|max:2048'
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(
                ['error' => $validator->error()],
                'update photo fails',401
            );
        }
        if ($request->file('file')) {
            $file = $request->file->store('assets/user','public');

            //simpan foto dalam database
            $user = Auth::user();
            $user->profile_photo_path = $file;
            $user->update();

            return ResponseFormatter::success($file,'file succesfully uploaded');
        }
    }
}
