<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{

    


    protected $table ='wallets';
    protected $fillable =['description','user_id','deposite_by','type','balance','amount'];
   
    /**
     * Get the parent walletable model .
     */
    // public function walletable()
    // {
    //     return $this->morphTo();
    // }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
