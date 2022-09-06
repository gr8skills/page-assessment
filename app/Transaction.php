<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'sender_nuban', 'receiver_nuban', 'amount', 'transaction_date', 'transaction_ref'
    ];

    protected $casts = [
        'amount' => 'double',
        'transaction_date' => 'datetime'
    ];

    public function sender()
    {
        return $this->belongsTo(Wallet::class, 'nuban', 'sender_nuban');
    }

    public function receiver()
    {
        return $this->belongsTo(Wallet::class, 'nuban', 'receiver_nuban');
    }
}
