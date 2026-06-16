<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

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
    
    public function getReports(Request $request)
    {
        $startDate = $request->get('start', now()->startOfMonth());
        $endDate = $request->get('end', now());
        
        $totalSales = Transaction::whereBetween('created_at', [$startDate, $endDate])->sum('total');
        $totalTransactions = Transaction::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalItemsSold = TransactionItem::whereHas('transaction', function($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [$startDate, $endDate]);
        })->sum('quantity');
        
        $bestSellers = TransactionItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(total) as total_revenue'))
            ->whereHas('transaction', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->with('product')
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'DESC')
            ->limit(5)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->product_id,
                    'product_name' => $item->product->product_name,
                    'category' => $item->product->category->name ?? 'Uncategorized',
                    'total_quantity' => $item->total_quantity,
                    'total_revenue' => $item->total_revenue,
                ];
            });
        
        return response()->json([
            'total_sales' => $totalSales,
            'total_transactions' => $totalTransactions,
            'total_items_sold' => $totalItemsSold,
            'best_sellers' => $bestSellers,
        ]);
    }
}