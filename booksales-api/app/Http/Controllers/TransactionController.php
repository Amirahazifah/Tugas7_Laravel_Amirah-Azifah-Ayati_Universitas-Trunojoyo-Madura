<?php

namespace App\Http\Controllers;

use App\Models\BookModel;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('user','book')->get();

        if ($transactions->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Resource data not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Get all resources',
            'data' => $transactions
        ]);
    }

    public function show(Transaction $transaction)
    {
        $user = auth()->user();
        if ($user->role !== 'admin' && $transaction->customer_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Get transaction detail',
            'data' => $transaction->load('user', 'book')
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|exists:books,id',
            'quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'data' => $validator->errors()
            ], 422);
        }

        $uniqueCode = "ORD-" . strtoupper(uniqid());

        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        $book = BookModel::find($request->book_id);

        if ($book->stock < $request->quantity) {
             return response()->json([
                 'success' => false,
                 'message' => 'Stok tidak cukup'
             ], 400);
         }

        $totalAmount = $book->price * $request->quantity;

        $book->stock -= $request->quantity;
        $book->save();

        $transactions = Transaction::create([
            'order_number' => $uniqueCode,
            'customer_id' => $user->id,
            'book_id' => $request->book_id,
            'total_amount' => $totalAmount
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Transaction created successfully',
            'data' => $transactions
        ], 201);
    }

    public function update(Request $request, Transaction $transaction)
    {
        $user = auth()->user();
        if ($user->role !== 'admin' && $transaction->customer_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'data' => $validator->errors()
            ], 422);
        }

        $book = BookModel::find($transaction->book_id);

        $book->stock += $transaction->quantity ?? 0;

        if ($book->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak cukup'
            ], 400);
        }

        $book->stock -= $request->quantity;
        $book->save();

        $transaction->total_amount = $book->price * $request->quantity;
        $transaction->quantity = $request->quantity;  
        $transaction->save();

        return response()->json([
            'success' => true,
            'message' => 'Transaction updated successfully',
            'data' => $transaction
        ]);
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return response()->json([
            'success' => true,
            'message' => 'Transaction deleted successfully'
        ],200);
    }
}
