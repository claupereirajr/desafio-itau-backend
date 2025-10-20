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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransactionRequest $request)
    {
        $validated = $request->validated();
        $transaction = [
            'id' => fake()->uuid(),
            'valor' => $validated['valor'],
            'dataHora' => $validated['dataHora']
        ];
        // Store temporarily (e.g. 10 minutes)
        Cache::put('transaction_' . $transaction['id'], $transaction, now()->addMinutes(10));
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
        Cache::clear();
        return response()->json('', 200);
    }
}
