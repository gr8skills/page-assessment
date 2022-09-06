<?php

use App\Http\Controllers\WalletController;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new WalletController)->seedTransactions(50);
    }
}
