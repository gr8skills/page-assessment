<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WalletType extends Model
{
    protected $fillable = [
        'name', 'shortcode', 'min_balance', 'monthly_interest_rate'
    ];

    protected $casts = [
        'min_balance' => 'double',
    ];

    public function wallet()
    {
        return $this->hasMany(Wallet::class, 'wallet_type_id', 'id');
    }

}
