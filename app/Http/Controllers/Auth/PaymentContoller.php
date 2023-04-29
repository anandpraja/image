<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Session;
use Exception;
use App\Models\Payment;
class PaymentContoller extends Controller
{
  public function index(Request $request)
  {
    return view('payment.index');
  }

  public function store(Request $request) {
    $input = $request->all();
    $api = new Api (env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
    $payment = $api->payment->fetch($input['razorpay_payment_id']);
    if(count($input) && !empty($input['razorpay_payment_id'])) {
      try {
        $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount' => $payment['amount']));
        $payment = Payment::create([
          'r_payment_id' => $response['id'],
          'method' => $response['method'],
          'currency' => $response['currency'],
          'user_email' => $response['email'],
          'amount' => $response['amount']/100,
          'json_response' => json_encode((array)$response)
        ]);
      } catch(Exceptio $e) {
        return $e->getMessage();
        Session::put('error',$e->getMessage());
        return redirect()->back();
      }
    }
    Session::put('success',('Payment Successful'));
    return redirect()->back();
  }
}