<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\Service;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Support\Facades\Session;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\SetupIntent;

class MembershipController extends Controller
{

public function showPaymentForm()
{
    Stripe::setApiKey(env('STRIPE_SECRET'));

    $intent = SetupIntent::create();

    return view('stripe.payment', [
        'clientSecret' => $intent->client_secret,
    ]);
}

public function handleStripePayment(Request $request)
{
    Stripe::setApiKey(env('STRIPE_SECRET'));

    try {
        $paymentMethodId = $request->paymentMethodId;


        return redirect()->route('payment.success'); 
    } catch (\Exception $e) {
        return redirect()->back()->with('error', $e->getMessage());
    }
}

public function paymentSuccess()
{
    return redirect()->route('memberships.index')->with('success', 'Payment transfer successfully!');
}

    
    public function create()
    {
        $services = Service::all();
        return view('memberships.create', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_name' => 'required|string|max:255',
            'phone' => 'required|numeric',
            'issue_date' => 'required|date',
            'expiry_date' => 'required|date',
        ]);
    
        $membership = Membership::create($validated);
    
        $services = array_merge(
            $request->input('dropdown_services', []), 
            $request->input('added_services', [])      
        );
    
        $prices = $request->input('added_prices', []); 
    
        foreach ($services as $serviceId) {
            $price = isset($prices[$serviceId]) ? $prices[$serviceId] : $this->getPriceFromDropdown($serviceId);
            $membership->services()->attach($serviceId, ['price' => $price]);
        }
    
        return redirect()->route('memberships.index')->with('success', 'Membership created successfully!');
    }

    private function getPriceFromDropdown($serviceId)
    {
        $service = Service::find($serviceId);
        return $service ? $service->price : 0;
    }

    public function destroy($id)
    {
        $membership = Membership::findOrFail($id);
        $membership->delete();

        return redirect()->route('memberships.index')->with('success', 'Membership deleted successfully.');
    }

    public function index()
    {
        $memberships = Membership::with('services')->get();
        $services = Service::all();
        return view('memberships.index', compact('memberships', 'services'));
    }

    public function edit($id)
    {
        $membership = Membership::with('services')->findOrFail($id);
        $services = Service::all(); 
        return view('memberships.edit', compact('membership', 'services'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'member_name' => 'required|string|max:255',
            'phone' => 'required|numeric',
            'issue_date' => 'required|date',
            'expiry_date' => 'required|date',
        ]);
    
        $membership = Membership::findOrFail($id);
    
        $allServices = Service::all();  
    
        $membership->update($validated);
    
        $membership->services()->detach();
    
        $services = array_merge(
            $request->input('dropdown_services', []), 
            $request->input('added_services', [])      
        );
    
        $prices = $request->input('added_prices', []); 
    
        foreach ($services as $serviceId) {
            $price = isset($prices[$serviceId]) ? $prices[$serviceId] : $this->getPriceFromDropdown($serviceId);
    
            $membership->services()->attach($serviceId, ['price' => $price]);
        }
    
        return redirect()->route('memberships.index')->with('success', 'Membership updated successfully!');
    }
    

    public function preview($id)
    {
        $membership = Membership::with('services')->findOrFail($id);
        $data = ['membership' => $membership];
        
        $pdf = FacadePdf::loadView('memberships.pdf', $data);
        return $pdf->download('membership-preview.pdf');
    }
}
