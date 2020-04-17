<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Luigel\Paymongo\Facades\Paymongo;

class CheckoutController extends Controller
{
    public function processPayment(Request $request) {

    	// Initialize variables 
    	$message = "";
    	$total_amount = 0;
    	$order_reference = "TEST0001";

    	// Validate Fields from here 
    	$this->validate($request, [
	        'name' => 'required|max:255',
	        'ccnumber' => 'required',
	        'expiry' => 'required',
	        'cvc' => 'required',
	        'basket' => 'required',
    	]);

    	// Double check if the basket is empty 
    	// well it'sokay since we have validate it primary just make sure :) 
    	if (count($request->input('basket'))<=0) {
    		return response()->json([
                'code' => 500, 
                'message' => 'Your basket is empty.', 
                'exception' => $e
            ], 500);
    	}

    	// Recompute price getting from the database 
    	// right now getting from the array submitted from the 
    	foreach($request->input('basket') as $item) {
    		$total_amount +=intval($item['price']);
    	}

		// making simplier to pass value 
		// 
    	$number = $request->input('ccnumber');
        $cvc = $request->input('cvc');
        $cname = $request->input('name');
        $exp_year = explode(" / ", $request->input('expiry'));
        $exp_month = intval($exp_year[0]);
        $exp_year = intval($exp_year[1]);

        $email_address = "lparba@gmail.com";

       // Create Token 
        try {

            $token = Paymongo::token()->create([
                'number' => $number,
                'exp_month' => $exp_month,
                'exp_year' => $exp_year,
                'cvc' => $cvc,
                'billing' => [
                    'name' => $cname,
                    'emaail' => $email_address
                ],
            ]); 

         } catch (\Exception $e) {

            $code = $e->getCode();
            $message = $e->getMessage();

            return response()->json([
                'error' => $e->getCode(),
                'message' => $e->getMessage() ], 500);
        }
        
        // Create object to charge with token 
        // 
        $payment = Paymongo::payment()->create([
            'amount' => $total_amount,
            'currency' => 'PHP',
            'description' => 'Payment for ref #:' . $order_reference,
            'statement_descriptor' => 'Test Payment - CreditCard Bill',
            'external_reference_number' => $order_reference,
            'source' => $token
        ]);

        if ($payment->status=="paid") {
        	$message = "Shopping cart successfully paid";
        } else {
        	return response()->json([
                'code' => 402, 
                'message' => 'Payment is required', 
                'exception' => $e
            ], 402);
        }

		return response()->json(['code' => 200, 'message' => $message], 200);

    }

}
