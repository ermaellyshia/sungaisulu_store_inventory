<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class EmployeeController extends Controller
{
    public function index()
    {
        $employees = User::where('role', 'employee')->get();
        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('admin.employees.create');
    }

    public function edit($id)
    {
        $employee = User::findOrFail($id);
        return view('admin.employees.edit', compact('employee'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',  
            'password' => 'nullable|min:8|regex:/[A-Z]/|regex:/[!@#$%^&*(),.?":{}|<>]/',
            
        ]);

        if (empty($validated['password'])) {
            $validated['password'] = Hash::make('password@123');
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        User::create($validated);  

        return redirect()->route('admin.employees')->with('success', 'Employee created successfully');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',  
            'password' => 'nullable|min:8|regex:/[A-Z]/|regex:/[!@#$%^&*(),.?":{}|<>]/',
            
        ]);

        if (isset($validated['password']) && !empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);  

        return redirect()->route('admin.employees')->with('success', 'Employee updated successfully');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->route('admin.employees')->with('error', 'Cannot delete your own account');
        }

        $user->delete();

        return redirect()->route('admin.employees')->with('success', 'Employee deleted successfully');
    }

    public function dashboard()
    {
        $today = now()->toDateString();

        $todaySales = Transaction::whereDate('created_at', $today)->sum('total');
        $todayTransactions = Transaction::whereDate('created_at', $today)->count();
        $todayItems = TransactionItem::whereHas('transaction', function ($q) use ($today) {
            $q->whereDate('created_at', $today);
        })->sum('quantity');

        return view('employee.dashboard', compact('todaySales', 'todayTransactions', 'todayItems'));
    }
    public function cart(Request $request)
    {
        $query = Product::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('product_name', 'LIKE', '%' . $request->search . '%');
        }

        $products = $query->paginate(12);
        return view('employee.cart', compact('products'));
    }
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            // TAMBAH quantity dari form (bukan +1)
            $cart[$product->id]['quantity'] += $request->quantity;
        } else {
            $cart[$product->id] = [
                'name' => $product->product_name,
                'quantity' => $request->quantity,  // GUNA quantity dari form
                'price' => $product->price,
                'stock' => $product->stock,
                'picture' => $product->product_picture
            ];
        }

        session()->put('cart', $cart);
        return back()->with('success', $product->product_name . ' added to the cart!');
    }

    public function viewCart()
    {
        $cart = session()->get('cart', []);
        return view('employee.cart', compact('cart'));
    }

    public function updateCart(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }
        return back()->with('success', 'Cart updated!');
    }

    public function removeFromCart($id)
    {
        $cart = session()->get('cart', []);
        unset($cart[$id]);
        session()->put('cart', $cart);
        return back()->with('success', 'Item cancelled.');
    }

    public function clearCart()
    {
        session()->forget('cart');
        return redirect()->route('employee.dashboard')->with('success', 'Cart emptied.');
    }

    public function processCheckout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('employee.cart')->with('error', 'Cart empty!');
        }

        DB::beginTransaction();
        try {
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'transaction_number' => 'INV-' . time(),
                'subtotal' => 0,
                'tax' => 0,
                'total' => 0,
                'payment_method' => 'cash',
                'customer_name' => 'Walk-in Customer',
                'amount_received' => 0,
                'change_amount' => 0,
            ]);

            $total = 0;
            foreach ($cart as $id => $item) {
                $product = Product::findOrFail($id);
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stock {$product->product_name} not enough!");
                }

                $product->decrement('stock', $item['quantity']);

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'total' => $product->price * $item['quantity']
                ]);

                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'type' => 'out',
                    'quantity' => $item['quantity']
                ]);

                $total += $product->price * $item['quantity'];
            }

            $transaction->update([
                'subtotal' => $total,
                'total' => $total
            ]);

            session()->forget('cart');
            DB::commit();

            return redirect()->route('employee.history')->with('success', 'Checkout succesfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
    public function history()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->with('items.product')
            ->latest()
            ->paginate(10);
        return view('employee.history', compact('transactions'));
    }
    public function pos(Request $request)
    {
        $query = Product::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('product_name', 'LIKE', '%' . $request->search . '%');
        }

        $products = $query->paginate(12);
        return view('employee.pos', compact('products'));
    }
    public function processPayment(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('employee.cart')->with('error', 'Cart empty!');
        }

        DB::beginTransaction();

        try {
            $total = 0;
            foreach ($cart as $id => $item) {
                $product = Product::findOrFail($id);

                // Check stock
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stock {$product->product_name} not enought! Stock: {$product->stock}");
                }

                // Kurangkan stok
                $product->decrement('stock', $item['quantity']);

                // Rekod stock movement
                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'type' => 'out',
                    'quantity' => $item['quantity']
                ]);

                $total += $product->price * $item['quantity'];
            }

            // Simpan transaction
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'transaction_number' => 'INV-' . time(),
                'customer_name' => 'Walk-in Customer',
                'payment_method' => 'cash',
                'subtotal' => $total,
                'tax' => 0,
                'total' => $total,
                'amount_received' => $total,
                'change_amount' => 0,
            ]);

            // Simpan transaction items
            foreach ($cart as $id => $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['price'] * $item['quantity']
                ]);
            }

            // Kosongkan cart
            session()->forget('cart');

            DB::commit();

            return redirect()->route('employee.cart')->with('success', 'Payment successful! Stock are decreased.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
