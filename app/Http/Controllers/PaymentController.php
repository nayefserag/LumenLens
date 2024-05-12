<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Token;
use Laravel\Lumen\Routing\Controller;
class PaymentController extends Controller
{
    public function charge(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_KEY'));
        $token = $request->input('stripeToken');

        try {
            $charge = Charge::create([
                'amount' => 1000, 
                'currency' => 'eur',
                'source' => $token,
                'description' => 'charge'
            ]);

            return response()->json(['status' => 'success', 'data' => $charge]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'failure', 'error' => $e->getMessage()]);
        }
    }


    // public function createToken(Request $request)
    // {
    //     Stripe::setApiKey(env('STRIPE_KEY'));

    //     try {
    //         $token = Token::create([
    //             'card' => [
    //                 'number'    => '4242424242424242',
    //                 'exp_month' => 12,
    //                 'exp_year'  => date('Y') + 3,
    //                 'cvc'       => '123'
    //             ]
    //         ]);

    //         return response()->json(['token' => $token->id]);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 400);
    //     }
    // }
    
}
