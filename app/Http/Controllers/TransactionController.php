<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'customer_name' => 'required|string|max:255',
            'payment_method' => 'required|in:cash,card,ewallet',
            'subtotal' => 'required|numeric',
            'tax' => 'required|numeric',
            'total' => 'required|numeric',
            'amount_received' => 'nullable|numeric',
        ]);

        DB::beginTransaction();

        try {
            $transaction = Transaction::create([
                'transaction_number' => 'TRX-' . strtoupper(uniqid()),
                'customer_name' => $validated['customer_name'],
                'payment_method' => $validated['payment_method'],
                'subtotal' => $validated['subtotal'],
                'tax' => $validated['tax'],
                'total' => $validated['total'],
                'amount_received' => $validated['amount_received'] ?? $validated['total'],
                'change_amount' => ($validated['amount_received'] ?? $validated['total']) - $validated['total'],
                'user_id' => auth()->id(),
            ]);

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['id']);

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'total' => $product->price * $item['quantity'],
                ]);

                $product->decrement('stock', $item['quantity']);
            }

            DB::commit();

            return response()->json([
                'message' => 'Transaction successful',
                'receipt_number' => $transaction->transaction_number,
                'transaction' => $transaction
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Transaction failed: ' . $e->getMessage()], 500);
        }
    }

    public function dailyReport(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));

        $transactions = Transaction::whereYear('created_at', substr($month, 0, 4))
            ->whereMonth('created_at', substr($month, 5, 2))
            ->with(['items' => function ($q) {
                $q->withTrashed();
            }, 'items.product' => function ($q) {
                $q->withTrashed();  // Include deleted products
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalSales = $transactions->sum('total');
        $totalItems = $transactions->sum(function ($t) {
            return $t->items->sum('quantity');
        });
        $totalTransactions = $transactions->count();

        return view('admin.reports.index', compact(
            'transactions',
            'totalSales',
            'totalItems',
            'totalTransactions',
            'month'
        ));
    }

    public function downloadPDF(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));

        $transactions = Transaction::whereYear('created_at', substr($month, 0, 4))
            ->whereMonth('created_at', substr($month, 5, 2))
            ->with(['items' => function ($q) {
                $q->withTrashed();
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalSales = $transactions->sum('total');
        $totalItems = $transactions->sum(function ($t) {
            return $t->items->sum('quantity');
        });
        $totalTransactions = $transactions->count();

        $pdf = Pdf::loadView('admin.reports.pdf', compact(
            'transactions',
            'totalSales',
            'totalItems',
            'totalTransactions',
            'month'
        ));

        return $pdf->download('monthly-report-' . $month . '.pdf');
    }
}
