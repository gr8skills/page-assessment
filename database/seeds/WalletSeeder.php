<?php

use Illuminate\Database\Seeder;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = \App\User::all();
        $ran_amount = array(1500,2000,3800,7500,22000,50700);


        foreach ($users as $user)
        {
            $wallet_type = \App\WalletType::inRandomOrder()
                ->firstOrFail();
            $randomAmount = $ran_amount[array_rand($ran_amount, 1)];
            $bal = $wallet_type->min_balance + $randomAmount;
            $monthly_interest_rate = $wallet_type->monthly_interest_rate / 100;
            $balance = $bal + ($monthly_interest_rate * $bal);
            DB::table('wallets')->insert([
                    'nuban' => '303'.$this->generateUniqueNuban(),
                    'user_id' => $user->id,
                    'wallet_type_id' => $wallet_type->id,
                    'balance' => $balance,
                    'created_at'=> DB::raw('CURRENT_TIMESTAMP'),
                    'updated_at'=> DB::raw('CURRENT_TIMESTAMP'),
            ]);
        }

    }

    public function generateUniqueNuban()
    {
        try {
            do {
                $nuban = random_int(1000000, 9999999);
            } while (DB::table('wallets')->where('nuban', '303' . $nuban)->exists());

        } catch (Exception $e) {

        }
        return $nuban;
    }
}
