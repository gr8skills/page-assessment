<?php

namespace App\Http\Controllers;

use App\Transaction;
use App\User;
use App\Wallet;
use App\WalletType;
use Carbon\Carbon;
use Illuminate\Database\Console\Migrations\ResetCommand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use function PHPUnit\Framework\isNan;


class WalletController extends Controller
{
    public function generateUniqueNuban()
    {
        try {
            do {
                $nuban = random_int(1000000, 9999999);
            } while (DB::table('wallets')->where('nuban', '303' . $nuban)->exists());

        } catch (\Exception $e) {

        }
        return $nuban;
    }

    public function generateTransactionRef($limit)
    {
        $ref = Str::random($limit);
        return 'WT-'.strtoupper($ref);
    }

    public function attachMultipleWalletsToUser(Request $request)
    {
        $data = $request->all();
        $wallet_qty = (int)$data['wallet_qty'];
        $user_qty = (int)$data['user_qty'];

        if (!isset($wallet_qty) || is_null($wallet_qty)){
            return response()->json([
                'error' => 'Wallet Quantity per user cannot be empty!'
            ],304);
        }
        if (!isset($user_qty) || is_null($user_qty)){
            return response()->json([
                'error' => 'Number of users cannot be empty!'
            ],304);
        }
        $users = \App\User::with('wallets')->inRandomOrder()->limit($user_qty)->get();
        $ran_amount = array(5000,12000,18000,7500,59350,22200,30100);

        foreach ($users as $user)
        {
            for ($i = 1; $i <= $wallet_qty; $i++){
                $wallet_type = \App\WalletType::inRandomOrder()
                    ->firstOrFail();
                $randomAmount = $ran_amount[array_rand($ran_amount, 1)];
                $bal = $wallet_type->min_balance + $randomAmount;
                $monthly_interest_rate = $wallet_type->monthly_interest_rate / 100;
                $balance = $bal + ($monthly_interest_rate * $bal); //add interest

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

        return response()->json([
           'success' => $wallet_qty*$user_qty .' Wallets added successfully',
            'users' => User::with('wallets')->get(),
        ], 200);

    }

    public function seedTransactions($qty)
    {
//        $data = $request->all();
//        $qty = (int)$data['qty'];
        $currentTime = Carbon::now('Africa/Lagos');
        $sender_ids = [];
        $senders = \App\User::with('wallets')->inRandomOrder()->limit($qty)->get();
        foreach ($senders as $sender){
            $sender_ids[] = $sender->id;
        }

        $ran_amount = array(1500,2000,3000,4500,10000,12000,14500);

        foreach ($senders as $sender){
            $randomAmount = $ran_amount[array_rand($ran_amount, 1)];
            $receiver = \App\User::with('wallets')->whereNotIn('id',$sender_ids)->inRandomOrder()->firstOrFail();
            foreach ($sender->wallets as $wallet){
                $min = WalletType::findOrFail($wallet->wallet_type_id);
                $check = (double)$wallet->balance - (double)$min->min_balance;
                if ($randomAmount < $check){
                    $snd = Wallet::find($wallet->id);
                    $snd->prev_balance = $wallet->balance;
                    $snd->balance = $wallet->balance - $randomAmount;

                    $rcv = Wallet::find($receiver->wallets[0]->id);
                    $rcv->prev_balance = $rcv->balance;
                    $rcv->balance = $rcv->balance + $randomAmount;
                    (new Transaction)->create([
                       'sender_nuban' => $wallet->nuban,
                       'receiver_nuban' => $receiver->wallets[0]->nuban,
                       'amount' => $randomAmount,
                       'transaction_date' => $currentTime->toDateTimeString(),
                        'transaction_ref' => $this->generateTransactionRef(8),
                        'created_at'=> $currentTime->toDateTimeString(),
                        'updated_at'=> $currentTime->toDateTimeString(),

                    ]);
                    $snd->save();
                    $rcv->save();
                    break;
                }else{
                    continue;
                }

            }
        }
        return response()->json([
            'success' => "Transactions seeded successfully",
        ], 200);
    }

    public function allUsers()
    {
//        $users = User::with(['wallets.sent_transactions','wallets.received_transactions'])->get();
        $users = User::all();
        return response()->json([
            'users' => $users,
        ], 200);
    }

    public function singleUser($id)
    {
        $user = User::with(['wallets.sent_transactions','wallets.received_transactions'])->find($id);
        if (isset($user)){
            return response()->json([
                'user'=>$user
            ], 200);
        }else{
            return response()->json([
                'error' => 'User not found'
            ], 404);
        }
    }

    public function allWallets()
    {
        $wallets = Wallet::all();
        return response()->json([
            'wallets' => $wallets,
        ], 200);
    }

    public function singleWallet($nuban)
    {
        $wallet = Wallet::with(['user','type','sent_transactions','received_transactions'])
            ->where('nuban','=', $nuban)
            ->orWhere('id','=', $nuban)
            ->first();

        if (isset($wallet)){
            return response()->json([
                'wallet'=>$wallet
            ], 200);
        }else{
            return response()->json([
                'error' => 'Wallet not found!'
            ], 404);
        }
    }

    public function getStatistics()
    {
        $user_count = User::count();
        $wallet_count = Wallet::count();
        $total_wallet_balance = Wallet::select(DB::raw('SUM(balance) AS total_balance'))->get();
        $transaction_count = Transaction::count();
        return response()->json([
            'user_count' => $user_count,
            'wallet_count' => $wallet_count,
            'total_wallet_balance' => $total_wallet_balance[0]->total_balance,
            'transaction_count' => $transaction_count
        ]);
    }

    public function transact(Request $request)
    {
        $data = $request->all();
        $req = ['sender', 'receiver', 'amount']; //Required Parameters
        foreach ($req as $r){
            if (!isset($data[$r]))
                return response()->json(['error' => "Please Provide all Required data: {$r} not set"]);
        }
        $currentTime = Carbon::now('Africa/Lagos');
        $data['amount'] = (double)$data['amount'];
        if (is_nan($data['amount']))
            return response()->json(['error' => "Please Provide amount: {$data['amount']} in numeric value"]);

        $sender = Wallet::with('type','user')->where('nuban','=', $data['sender'])
            ->orWhere('id','=', $data['sender'])
            ->first();

        if (!isset($sender))
            return response()->json(['error' => "Please Provide A Valid Sender NUBAN or ID, {$data['sender']} is invalid"]);

        $receiver = Wallet::where('nuban','=', $data['receiver'])
            ->orWhere('id','=', $data['receiver'])
            ->first();

        if (!isset($receiver))
            return response()->json(['error' => "Please Provide A Valid Receiver NUBAN or ID, {$data['receiver']} is invalid"]);

        $limit = $sender->type->min_balance;
        $check = $sender->balance - ($limit + $data['amount']);
        if ($check > 0){
            $sender->prev_balance = $sender->balance;
            $sender->balance = $sender->balance - $data['amount'];
            $receiver->prev_balance = $receiver->balance;
            $receiver->balance = $receiver->balance + $data['amount'];

            (new Transaction)->create([
                'sender_nuban' => $sender->nuban,
                'receiver_nuban' => $receiver->nuban,
                'amount' => $data['amount'],
                'transaction_date' => $currentTime->toDateTimeString(),
                'transaction_ref' => $this->generateTransactionRef(8),
                'created_at'=> $currentTime->toDateTimeString(),
                'updated_at'=> $currentTime->toDateTimeString(),
            ]);
            $sender->save();
            $receiver->save();
            return response()->json([
                'success' => "{$sender->user->name} with the wallet: {$sender->nuban} transfer of {$data['amount']} to {$receiver->nuban} was successful"
            ], 200);
        }else{
            return response()->json(['error' => "{$data['amount']} exceeds your account limit, check your account balance and retry with a valid amount"]);
        }



    }
}
