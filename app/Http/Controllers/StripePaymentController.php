<?php

    

namespace App\Http\Controllers;

     

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\Session;


     

class StripePaymentController extends Controller

{

    /**

     * success response method.

     *

     * @return \Illuminate\Http\Response

     */

    public function stripe()

    {

        return view('stripe');

    }

    

    /**

     * success response method.

     *

     * @return \Illuminate\Http\Response

     */

     public function stripePost(Request $request)
     {
         try {
             Stripe::setApiKey(env('STRIPE_SECRET'));
 
             // Create a PaymentIntent
             $paymentIntent = PaymentIntent::create([
                 'amount' => 100 * 100, // Amount in cents
                 'currency' => 'usd',
                 'payment_method_types' => ['card'],
             ]);
 
             return view('stripe', ['clientSecret' => $paymentIntent->client_secret]);
         } catch (\Exception $e) {
             return back()->with('error', $e->getMessage());
         }
     }

}


