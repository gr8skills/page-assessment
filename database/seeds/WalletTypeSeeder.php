<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WalletTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            $data = [
                [
                    'name' => 'STARTER',
                    'shortcode' => 'STR',
                    'min_balance' => 5000,
                    'monthly_interest_rate' => 5, //...percent of savings
                    'created_at'=> DB::raw('CURRENT_TIMESTAMP'),
                    'updated_at'=> DB::raw('CURRENT_TIMESTAMP'),
                ],
                [
                    'name' => 'PRO',
                    'shortcode' => 'PRO',
                    'min_balance' => 10000,
                    'monthly_interest_rate' => 6.5, //...percent of savings
                    'created_at'=> DB::raw('CURRENT_TIMESTAMP'),
                    'updated_at'=> DB::raw('CURRENT_TIMESTAMP'),
                ],
                [
                    'name' => 'PRO PLUS',
                    'shortcode' => 'PR+',
                    'min_balance' => 20000,
                    'monthly_interest_rate' => 8, //...percent of savings
                    'created_at'=> DB::raw('CURRENT_TIMESTAMP'),
                    'updated_at'=> DB::raw('CURRENT_TIMESTAMP'),
                ],
                [
                    'name' => 'GOLD',
                    'shortcode' => 'GLD',
                    'min_balance' => 50000,
                    'monthly_interest_rate' => 12, //...percent of savings
                    'created_at'=> DB::raw('CURRENT_TIMESTAMP'),
                    'updated_at'=> DB::raw('CURRENT_TIMESTAMP'),
                ]
            ];
        DB::table('wallet_types')->insert($data);
    }
}
