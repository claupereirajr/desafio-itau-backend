<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Cache::get('transactions', []);
        $oneMinuteAgo = now()->subMinute();

        $recentTransactions = array_filter($transactions, function ($transaction) use ($oneMinuteAgo) {
            return \Carbon\Carbon::parse($transaction['dataHora'])->isAfter($oneMinuteAgo);
        });

        $values = array_column($recentTransactions, 'valor');

        if (empty($values)) {
            return response()->json([
                'count' => 0,
                'sum' => 0,
                'avg' => 0,
                'min' => 0,
                'max' => 0
            ]);
        }

        return response()->json([
            'count' => count($values),
            'sum' => array_sum($values),
            'avg' => array_sum($values) / count($values),
            'min' => min($values),
            'max' => max($values)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransactionRequest $request)
    {
        $validated = $request->validated();
        $transaction = [
            'valor' => $validated['valor'],
            'dataHora' => $validated['dataHora']
        ];

        $transactions = Cache::get('transactions', []);
        $transactions[fake()->uuid()] = $transaction;
        Cache::put('transactions', $transactions, now()->addHour());

        if ($transaction) {
            return response()->json('', 201);
        } else {
            return response()->json(['message' => 'Transaction creation failed'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        Cache::forget('transactions');
        return response()->json('', 200);
    }
}
