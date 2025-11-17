<?php
namespace App\Http\Controllers;

use App\Models\PaymentType;
use Illuminate\Http\Request;

class PaymentTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = PaymentType::query();
        if ($request->has('search')) {
            $query->where('payment_name', 'like', '%' . $request->search . '%');
        }
        $payments = $query->orderBy('id', 'desc')->paginate(10);
        return view('admin.payment_type.index', compact('payments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment_name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        $validated['status'] = $request->has('status') ? 1 : 0;

        $payment = PaymentType::create($validated);

        return response()->json(['success' => true, 'message' => 'Added successfully', 'data' => $payment]);
    }

    public function update(Request $request, $id)
    {
        $payment = PaymentType::findOrFail($id);
        $validated = $request->validate([
            'payment_name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        $validated['status'] = $request->has('status') ? 1 : 0;
        $payment->update($validated);

        return response()->json(['success' => true, 'message' => 'Updated successfully']);
    }

    public function destroy($id)
    {
        PaymentType::destroy($id);
        return response()->json(['success' => true]);
    }
}
