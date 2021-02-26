<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Qrcode;
use App\User;
use App\Wallet;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    


    public function store(Request $request){
       
      
        $request->validate([
            'description'=>'required',
            'amount'=>'required',
            'contact'=>'',
            'qrcode'=>''
        ]);
     
        if (auth()->user()->hasVerifiedEmail() && auth()->user()->verified === 1) {
            $balance = Wallet::where('user_id',auth()->user()->id)->orderBy('created_at','desc')->first();
            if($balance->balance > $request->amount){
                
                if($request->has('qrcode')){
                    $qr = Qrcode::where('sku',$request->qrcode)->first();
                   
                   if(!empty($qr) && !empty($qr->user)){
                    $user =  $qr->user;
                   
                   }else{
                    return response()->json(["msg" => "Invalid token."], 400);   
                   }
                   
                    
                }else{
                    $user = User::where('contact',$request->contact)->first();
                }
               
               
                if(!empty($user) && $user->verified === 1){
                    $receiver = $request->except('contact','qrcode');
                    $receiver['balance'] =  (int)$user->debit() - (int)$user->credit() + $request->amount;
                    $receiver['deposite_by'] = auth()->user()->id;
                    $receiver['type'] = 'debit';
                    $receiver['user_id'] = $user->id;
            
                    $deposite['description'] = 'money transfer to other 9';
                    $deposite['amount'] = $request->amount;
                    $deposite['balance'] = (int)auth()->user()->debit() - (int)auth()->user()->credit() - $request->amount;
                    $deposite['deposite_by'] = auth()->user()->id;
                    $deposite['user_id'] = auth()->user()->id;
                    $deposite['type'] = 'credit';
                    Wallet::insert([$receiver,$deposite]);
            
                    return auth()->user()->wallets;
                }else{
                    if($user->verified === 0){
                        return response()->json(["msg" => "Receiver contact is not  verified."], 400);
                    }
                    return response()->json(["msg" => "Their is no user with this contact."], 400);
                }
                
             }else{
                 
                 return response()->json(['message'=>'Insufficent Balance'],400);
             }
        }else{
            
                if(auth()->user()->verified === 0){
                    return response()->json(["msg" => "Contact is not  verified."], 400);
                }
            return response()->json(["msg" => "Email is not  verified."], 400);
        }

       
       
        

   

        // $dep['balance']

        // Wallet::create($data);
        



    }
}
