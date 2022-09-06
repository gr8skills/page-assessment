<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wallet extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nuban', 'user_id', 'balance', 'wallet_type_id', 'prev_balance',
    ];

    protected $casts = [
        'balance' => 'double',
    ];

    public function type()
    {
        return $this->belongsTo(WalletType::class, 'wallet_type_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function sent_transactions()
    {
        return $this->hasMany(Transaction::class, 'sender_nuban', 'nuban');
    }

    public function received_transactions()
    {
        return $this->hasMany(Transaction::class, 'receiver_nuban', 'nuban');
    }
}
