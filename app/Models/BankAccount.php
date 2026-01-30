<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function transactions(){
        return $this->hasMany(Transaction::class, 'bank_account_id', 'id');
    }
}
