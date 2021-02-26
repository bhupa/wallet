<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use  HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname','lastname', 'email', 'password','image','contact','otp_no','verified',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function wallets()
    {
        return $this->hasMany('App\Wallet');
    }

    public function credit(){

        return $this->hasMany('App\Wallet')->where('type','credit')->sum('amount');
    }
    public function debit(){

        return $this->hasMany('App\Wallet')->where('type','debit')->sum('amount');
    }

    public function qrCode(){
        return $this->hasMany(Qrcode::class)->orderBy('created_at','desc')->first();
    }
}
