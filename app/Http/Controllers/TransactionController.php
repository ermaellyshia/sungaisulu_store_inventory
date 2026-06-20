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
        $date = $request->input('date', now()->toDateString());
        
        $transactions = Transaction::whereDate('created_at', $date)
            ->with('items.product')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $totalSales = $transactions->sum('total');
        $totalItems = $transactions->sum(function($t) {
            return $t->items->sum('quantity');
        });
        $totalTransactions = $transactions->count();
        
        return view('admin.reports.index', compact(
            'transactions', 
            'totalSales', 
            'totalItems', 
            'totalTransactions',
            'date'
        ));
    }

    public function downloadPDF(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        
        $transactions = Transaction::whereDate('created_at', $date)
            ->with('items.product')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $totalSales = $transactions->sum('total');
        $totalItems = $transactions->sum(function($t) {
            return $t->items->sum('quantity');
        });
        $totalTransactions = $transactions->count();
        
        $pdf = Pdf::loadView('admin.reports.pdf', compact(
            'transactions', 'totalSales', 'totalItems', 'totalTransactions', 'date'
        ));
        
        return $pdf->download('daily-report-' . $date . '.pdf');
    }

    public function downloadExcel(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        
        $transactions = Transaction::whereDate('created_at', $date)
            ->with('items.product')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $data = [];
        foreach ($transactions as $t) {
            foreach ($t->items as $item) {
                $data[] = [
                    'Invoice' => $t->transaction_number,
                    'Date' => $t->created_at->format('d/m/Y H:i'),
                    'Customer' => $t->customer_name ?? 'Walk-in',
                    'Product' => $item->product->product_name,
                    'Quantity' => $item->quantity,
                    'Price' => $item->price,
                    'Total' => $item->total,
                ];
            }
        }
        
        return \Maatwebsite\Excel\Facades\Excel::download(
            new class($data) implements \Maatwebsite\Excel\Concerns\FromArray {
                protected $data;
                public function __construct($data) { $this->data = $data; }
                public function array(): array { return $this->data; }
            },
            'daily-report-' . $date . '.xlsx'
        );
    }
}