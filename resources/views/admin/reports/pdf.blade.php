<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daily Report</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h1 { color: #7c3aed; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #7c3aed; color: white; padding: 10px; text-align: left; }
        td { padding: 8px; border-bottom: 1px solid #ddd; }
        .total { font-weight: bold; color: #16a34a; }
        .stats { display: flex; justify-content: space-between; margin-top: 20px; }
        .stat-box { border: 1px solid #ddd; padding: 10px; border-radius: 5px; width: 30%; text-align: center; }
        .stat-box h3 { margin: 0; color: #666; font-size: 12px; }
        .stat-box p { margin: 5px 0 0; font-size: 20px; font-weight: bold; color: #7c3aed; }
    </style>
</head>
<body>
    <h1>Daily Report - {{ date('d/m/Y', strtotime($date)) }}</h1>
    
    <div class="stats">
        <div class="stat-box">
            <h3>Total Sales</h3>
            <p>RM {{ number_format($totalSales, 2) }}</p>
        </div>
        <div class="stat-box">
            <h3>Transactions</h3>
            <p>{{ $totalTransactions }}</p>
        </div>
        <div class="stat-box">
            <h3>Items Sold</h3>
            <p>{{ $totalItems }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Invoice</th>
                <th>Time</th>
                <th>Customer</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $t)
                @foreach($t->items as $item)
                <tr>
                    <td>#{{ $t->transaction_number }}</td>
                    <td>{{ $t->created_at->format('H:i') }}</td>
                    <td>{{ $t->customer_name ?? 'Walk-in' }}</td>
                    <td>{{ $item->product->product_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>RM {{ number_format($item->total, 2) }}</td>
                </tr>
                @endforeach
            @empty
                <tr><td colspan="6" style="text-align:center;">No transactions found</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" style="text-align:right; font-weight:bold;">Grand Total:</td>
                <td style="font-weight:bold; color:#16a34a;">RM {{ number_format($totalSales, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>