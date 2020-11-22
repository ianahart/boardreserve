<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Purchase;
use App\Models\User;


class PurchaseController extends Controller
{
    public function showUserPaymentInfo(Request $request)
    {
        $authID = Auth::user()->id;

        $user = User::select('id', 'name', 'email')
            ->where('id', '=', $authID)
            ->first();

        $fullName = explode(' ', $user->name);

        return view('purchases.showuserinfo', [

            'cart_total' => $request->query('total'),
            'email' => $user->email,
            'first_name' => $fullName[0],
            'last_name' => $fullName[1],

        ]);
    }

    public function storePaymentInformation(Request $request)
    {
        $userId = Auth::user()->id;

        $user = User::select('id')
            ->where('id', '=', $userId)
            ->first();


        $validatedData = $request->validate([
            'firstname' => [
                'required'
            ],
            'lastname' => [
                'required'
            ],
            'email' => [
                'required'
            ],
            'billing_country' => [
                'required'
            ],
            'billing_postal_code' => [
                'required'
            ],
            'billing_city' => [
                'required'
            ],
            'billing_state' => [
                'required'
            ],
            'billing_city' => [
                'required'
            ],
            'billing_street_address' => [
                'required'
            ],
            'shipping_country' => [
                'required'
            ],
            'shipping_postal_code' => [
                'required',
                'numeric'
            ],
            'shipping_city' => [
                'required'
            ],
            'shipping_state' => [
                'required'
            ],
            'shipping_street_address' => [
                'required'
            ],
        ]);


        $purchase = new Purchase;

        $purchase->first_name = ucfirst($request->input('firstname'));
        $purchase->last_name = ucfirst($request->input('lastname'));
        $purchase->userId = $user->id;
        $purchase->email = $request->input('email');

        $purchase->billing_country = ucfirst($request->input('billing_country'));
        $purchase->billing_postal_code = $request->input('billing_postal_code');
        $purchase->billing_city = ucfirst($request->input('billing_city'));
        $purchase->billing_state = ucwords($request->input('billing_state'));
        $purchase->billing_street_address = ucwords($request->input('billing_street_address'));

        $purchase->shipping_country = ucfirst($request->input('shipping_country'));
        $purchase->shipping_postal_code = $request->input('shipping_postal_code');
        $purchase->shipping_city = ucwords($request->input('shipping_city'));
        $purchase->shipping_state = ucwords($request->input('shipping_state'));
        $purchase->shipping_street_address = ucwords($request->input('shipping_street_address'));

        $purchase->shipping_method = $request->input('shipping_method');
        $purchase->price_total = $request->input('total');

        $purchase->save();

        $newestPurchase = Purchase::select('id')->latest()->first();

        $this->generateOrderNumber($newestPurchase);

        return redirect('/users/checkout?total=' . $request->input('total') . '&purchase=' . $newestPurchase->id);
    }

    private function generateOrderNumber($purchase)
    {
        $purchase = Purchase::select('*')
            ->where('id', '=', $purchase->id)
            ->first();

        $purchase->order_number = '#' . str_pad($purchase->id, 8, '0', STR_PAD_LEFT);

        $purchase->save();
    }
}
