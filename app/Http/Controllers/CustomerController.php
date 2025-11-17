<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Division;
use App\Models\Township;
use App\Models\MemberCard;
use App\Models\CustomerType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::select('customers.customer_code','customers.name','customers.phone','customers.email','customers.address','divisions.name as division_name','townships.name as township_name','customers.address','customer_types.name as customer_type')
                ->leftjoin('divisions','customers.division_id','divisions.id') 
                ->leftjoin('townships','customers.township_id','townships.id')
                ->leftjoin('customer_types','customers.customer_type_id','customer_types.id')
                ->orderBy('customers.id','desc')
                ->paginate(10);
        $customerTypes = CustomerType::all();
        $divisions = Division::all();
        $townships = Township::all();
        $mercards  =  MemberCard::all();
        return view('customers.index', compact('customers','customerTypes','divisions','townships','mercards'));
    }

     public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string',
            'address' => 'required|string',
            'payment_method' => 'required|string',
            'cart_data' => 'required|string',
        ]);

        $cartItems = json_decode($request->cart_data, true);

        if(!$cartItems || count($cartItems) === 0){
            return back()->with('error', 'Cart is empty!');
        }

        // Calculate total
        $total = 0;
        foreach($cartItems as $item){
            $total += $item['price'] * $item['qty'];
        }

        // Create Order
        $order = Order::create([
            'user_id' => Auth::id() ?? null,
            'order_no' => 'ORD-' . strtoupper(uniqid()),
            'customer_name' => $request->customer_name,
            'total' => $total,
            'address' => $request->address,
            'payment_method' => $request->payment_method,
            'status' => 'pending',
        ]);

        // Save Order Items
        foreach($cartItems as $item){
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'qty' => $item['qty'],
                'total' => $item['price'] * $item['qty'],
            ]);
        }

        return redirect()->route('shop')->with('success', 'Your order has been placed successfully!');
    }

    // for modal edit JSON fetch
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return response()->json($customer);
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:customers,phone,'.$customer->id,
            'email' => 'nullable|email|max:255',
            'division' => 'nullable|string|max:255',
            'township' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);
       
        $customer->update($data);

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy($id)
    {
        Customer::findOrFail($id)->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted.');
    }

    public function getTownships($division_id)
    {
        $townships = Township::where('division_id', $division_id)->get(['id', 'name']);
        return response()->json($townships);
    }

    public function autocomplete(Request $request)
{
    $query = $request->get('q');
    $customers = \App\Models\Customer::where('name','like',"%{$query}%")
        ->limit(10)
        ->get(['id','name']);

    return response()->json($customers);
}


    
}
