<?php

namespace App\Http\Controllers\API;

use Midtrans\Config;
use Midtrans\Notification;
use App\Models\transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
    //set konfigurasi midtrans
    Config::$serverKey = config('services.midtrans.serverKey');
    Config::$clientKey = config('services.midtrans.clientKey');
    Config::$isProduction = config('services.midtrans.isProduction');
    Config::$isSanitized = config('services.midtrans.isSanitized');
    Config::$is3ds = config('services.midtrans.is3ds');


    //buat instance midtrans notif

    $notification = new Notification();

    //assigb ke variabel untuk memudahkan coding
    $status = $notification->transaction_status;
    $type = $notification->payment_type;
    $fraud = $notification->fraud_status;
    $order_id = $notification->order_id;

    //cari transsaksi berdasarkan ID
    $transaction = transaction::findOrFail($order_id);

    //handle notif status midtrans
    if($status == 'capture')
    {
        if($type == 'credit_card')
        {
            $transaction->status = 'PENDING';
        }else{
            $transaction->status = 'SUCCESS';
        }
    }else if($status == 'settlement')
    {
        $transaction->status = 'SUCCeSS';
    }else if($status == 'pending')
    {
        $transaction->status = 'PENDING';
    }else if($status == 'deny')
    {
        $transaction->status = 'CANCELED';
    }else if($status == 'expire')
    {
        $transaction->status = 'CANCELED';
    }else if($status == 'cancel')
    {
        $transaction->status = 'CANCELED';
    }

    //simpan trnasaksi
    $transaction->save();
    }

    public function success()
    {
        return view('midtrans.success');
    }
    public function unfinish()
    {
        return view('midtrans.unfinish');
    }
    public function error()
    {
        return view('midtrans.error');
    }
}
