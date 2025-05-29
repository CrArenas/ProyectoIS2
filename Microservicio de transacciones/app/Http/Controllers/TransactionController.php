<?php

namespace App\Http\Controllers;

use App\Models\Transaction; // Ensure this model exists in the specified namespace
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class TransactionController extends Controller
{

    public function index()
    {
        $transactions = Transaction::all();
        return response()->json($transactions);
    }

    public function store(Request $request, $user)
    {
        //$transaction = Transaction::create($request->all());
        $transaction = new Transaction();
        $transaction->user_id = $user;
        $transaction->amount = $request->amount;
        $transaction->V1 = $request->V1;
        $transaction->V2 = $request->V2;
        $transaction->V3 = $request->V3;
        $transaction->V4 = $request->V4;
        $transaction->V5 = $request->V5;
        $transaction->V6 = $request->V6;
        $transaction->V7 = $request->V7;
        $transaction->V8 = $request->V8;
        $transaction->V9 = $request->V9;
        $transaction->V10 = $request->V10;
        $transaction->V11 = $request->V11;
        $transaction->V12 = $request->V12;
        $transaction->V13 = $request->V13;
        $transaction->V14 = $request->V14;
        $transaction->V15 = $request->V15;
        $transaction->V16 = $request->V16;
        $transaction->V17 = $request->V17;
        $transaction->V18 = $request->V18;
        $transaction->V19 = $request->V19;
        $transaction->V20 = $request->V20;
        $transaction->V21 = $request->V21;
        $transaction->V22 = $request->V22;
        $transaction->V23 = $request->V23;
        $transaction->V24 = $request->V24;
        $transaction->V25 = $request->V25;
        $transaction->V26 = $request->V26;
        $transaction->V27 = $request->V27;
        $transaction->V28 = $request->V28;
        $transaction->save();
        return response()->json(['message'=> 'Se guardo']); 
    }

    public function show($id)
    {
        $transactions = Transaction::where('user_id', $id)->get();
        return response()->json($transactions); 
    }

    public function destroy($id)
    {
        $transaction = Transaction::find($id);
        $transaction->delete();
        return response()->json(['message' => 'Transacción eliminada']);
    }
}
