<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = [];

    public function bank_account(){
        return $this->belongsTo(BankAccount::class, 'bank_account_id', 'id');
    }
}
