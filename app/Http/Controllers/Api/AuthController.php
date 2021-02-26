<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use App\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function register(Request $request){

        $result = $request->validate([
            'firstname'=>'required',
            'lastname'=>'required',
            'email'=>'email|required|unique:users',
            'contact'=>'required',
            'image'=>'required',
            'password'=>'required'

        ]);

        $data = $request->all();
        if ($request->hasFile('image')) {
         
            $file = $request->file('image');
            $name = str_replace(' ', '_', time() . '_' . $file->getClientOriginalName());
            $filePath = 'user/'.$name;
             $response = Storage::disk('public')->put($filePath,file_get_contents($file));
            
            $data['image'] = $filePath;
        }

        $data['password'] =  Hash::make($data['password']);

        
        $user  = User::create($data);
        $user->sendEmailVerificationNotification();
         $this->wallet($user);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response(['user'=>$user, 'access_token'=>$accessToken,'wallet'=>$user->wallets]);
    }

    public function login(Request $request){

        // $result = $request->validate([
        //     'email'=>'email|required|exists:users,email',
        //     'password'=>'required'

        // ]);
        if(is_numeric($request->get('email'))){
            $result =  ['contact'=>$request->get('email'),'password'=>$request->get('password')];
          }
          elseif (filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
            $result =['email' => $request->get('email'), 'password'=>$request->get('password')];
          }

        

        // request()->merge([$fieldType=>$login]);

      
        if(!auth()->attempt($result)){
            return response(['Some thing went wrong']);

        }
        if (auth()->user()->hasVerifiedEmail() || auth()->user()->verified === 1) {
            $accessToken = auth()->user()->createToken('authToken')->accessToken;
             return response(['user'=>auth()->user(), 'access_token'=>$accessToken]);
        }else{
            if(auth()->check()){
                if(auth()->user()->verified === 1){
                    return response()->json(["msg" => "Contact is not  verified."], 400);
                }
            }
            return response()->json(["msg" => "Email is not  verified."], 400);
        }
        
    }

    public function logout(Request $request)
    {
        auth()->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function wallet($user){
       $data =[
            "description" => 'Bons after registger',
            "type" => 'debit',
            "amount" => '500',
            "balance" => '500',
            'user_id'=> $user->id
        ];

       return  Wallet::create($data);
    }
}
