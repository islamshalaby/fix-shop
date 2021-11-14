<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use JD\Cloudder\Facades\Cloudder;
use Illuminate\Support\Facades\DB;
use App\Wallet;
use App\WalletTransaction;


class WalletController extends AdminController{
    // index
    public function show() {
        $data['wallets'] = Wallet::get();

        return view('admin.wallets', ['data' => $data]);
    }

    // transactions
    public function transactions(Wallet $wallet) {
        $data['wallet'] = $wallet;
        $data['transactions'] = $wallet->transactions;

        return view('admin.wallet_transactions', ['data' => $data]);
    }
}