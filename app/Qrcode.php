<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Qrcode extends Model
{
    
    protected $table='qrcode';

    protected $fillable =['user_id','image','sku','is_active'];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
