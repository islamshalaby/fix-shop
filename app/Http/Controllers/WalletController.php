<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Wallet;
use App\WalletTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Helpers\APIHelpers;


class WalletController extends Controller
{

    // get my wallet balance
    public function getMyWalletBalance(Request $request) {
        $user = auth()->user();
        $data['wallet'] = Wallet::where('user_id', $user->id)->first();
		if (isset($data['wallet']['balance'])) {
			$data['wallet']['balance'] = number_format((float)$data['wallet']['balance'], 3, '.', '');
		}else {
			$data['wallet']['balance'] = "0";
		}

        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data['wallet'] , $request->lang);
            return response()->json($response , 200);
    }

    // add balance to wallet
    public function addBalanceToWallet(Request $request) {
        $validator = Validator::make($request->all(), [
            'balance' => 'required'
        ]);
        
            // dd($request->root());
        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 , 'Missing Required Fields' , 'بعض الحقول مفقودة' , null , $request->lang);
            return response()->json($response , 406);
        }
        $user = auth()->user();
        $root_url = $request->root();
        $path='https://apitest.myfatoorah.com/v2/SendPayment';
        $token="bearer rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL";

        $headers = array(
            'Authorization:' .$token,
            'Content-Type:application/json'
        );

        $call_back_url = $root_url."/api/wallet/excute_pay?user_id=".$user->id."&balance=".$request->balance;
        $error_url = $root_url."/api/pay/error";

        $fields =array(
            "CustomerName" => $user->name,
            "NotificationOption" => "LNK",
            "InvoiceValue" => $request->balance,
            "CallBackUrl" => $call_back_url,
            "ErrorUrl" => $error_url,
            "Language" => "AR",
            "CustomerEmail" => $user->email
        );  

        $payload =json_encode($fields);
        $curl_session =curl_init();
        curl_setopt($curl_session,CURLOPT_URL, $path);
        curl_setopt($curl_session,CURLOPT_POST, true);
        curl_setopt($curl_session,CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl_session,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl_session,CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl_session,CURLOPT_IPRESOLVE, CURLOPT_IPRESOLVE);
        curl_setopt($curl_session,CURLOPT_POSTFIELDS, $payload);
        $result=curl_exec($curl_session);
        curl_close($curl_session);
        $result = json_decode($result);
        $data['url'] = $result->Data->InvoiceURL;

        $response = APIHelpers::createApiResponse(false , 200 ,  '' , '' , $data , $request->lang );
        return response()->json($response , 200); 
    }

    // excute pay
    public function excute_pay(Request $request) {
        $validator = Validator::make($request->all(), [
            'balance' => 'required',
            'user_id' => 'required'
        ]);
        

        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 , 'Missing Required Fields' , 'بعض الحقول مفقودة' , null , $request->lang);
            return response()->json($response , 406);
        }

        $wallet = Wallet::where('user_id', $request->user_id)->first();
        if (! isset($wallet['id'])) {
            $wallet = Wallet::create(['balance' => $request->balance, 'user_id' => $request->user_id]);
        }else {
            $wallet['balance'] = $wallet['balance'] + $request->balance;
            $wallet->save();
        }

        WalletTransaction::create([
            'value' => $request->balance,
            'user_id' => $request->user_id,
            'wallet_id' => $wallet['id']
        ]);

        return redirect('api/pay/success'); 
    }
}