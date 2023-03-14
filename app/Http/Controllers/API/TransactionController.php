<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\transaction;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 6);
        $food_id = $request->input('food_id');
        $status = $request->input('status');

        if ($id) 
        {
            $transaction = transaction::with(['food','user'])->find($id);
            if ($transaction) {
                return ResponseFormatter::success($transaction, 'Data transaksi berhasil diambil');
            }else{
                return ResponseFormatter::error($transaction,'Data transaksi tidak ada',404);
            }
        }

        $transaction = transaction::with(['food','user'])->where('user_id',Auth::user()->id)
        if ($food_id) {
            $food_id->where('food_id', $food_id);
        }
        if ($status) {
            $food_id->where('status', $status);
        }

        return ResponseFormatter::success(
            $transaction->paginate($limit), 'Data list transaction berhasil diambil');
    }

    public function update($request $request,$id)
    {
        $transaction = transaction::findOrdFails($id);

        $transaction->update($request->all());
        
        return ResponseFormatter::success($transaction,'Transaksi dapat diperbaharui');
    }
    
    public function checkout(Request $request)
    {
        $request->validate([
            'food_id'=>'required|exists:food,id',
            'user_id'=>'required|exists:user,id',
            'qyt'=>'required',
            'total'=>'required',
            'status'=>'required',
        ]);

        $transaction = transaction::create([
            'food_id' => $request->food_id,
            'user_id' => $request->user_id,
            'qyt'=> $request->qyt,
            'total'=> $request->total,
            'status'=> $request->status,
            'payment_url' => '',
        ])

        //konfigurasi midtrans
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$clientKey = config('services.midtrans.clientKey');
        Config::$isProduction    = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');

        //panggil transaksi yg ud dibuat
        $transaction = transaction::with(['food','user'])->find($transaction->id);

        //membuat transaksi midtrans
        $midtrans = [
            'transaction_details' =>[
                'order_id' => $transaction->id,
                'gross_amount' => (int) $transaction->total,
            ],
            'customer_details' => [
                'first_name' => $transaction->user->name,
                'email' => $transaction->user->email,
            ],
            'enabled_payment' => ['gopay','bank_transfer'],
            'vtweb' => []
        ];

        //memanggil midtrans
        try {
            //ambil halaman payment
            $paymentUrl = Snap::createTransaction($midtrans)->redirect_url;

            $transaction->payment_url = $paymentUrl;
            $transaction->save();

            //mengembalikan data ke API
            return ResponseFormatter::success($transaction,'Transaksi berhasil');
        } catch (Exception $e) {
            return ResponseFormatter:error($e->getMessage(),'Transaksi gagal')
        }
    }
}
