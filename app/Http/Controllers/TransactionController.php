<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $transactions = Transaction::orderBy('created_at', 'desc')->paginate();

        $sells = Transaction::where('type', 'sell')->get()->map(function ($transaction) {
          return $transaction->value;
        })->sum();

        $buys = Transaction::where('type', 'buy')->get()->map(function ($transaction) {
          return $transaction->value;
        })->sum();

        $profit = $sells - $buys;

        if ($request->isMethod('post')) {
          $transaction = new Transaction();

          $transaction->user_id = Auth::id();
          $transaction->rate = $request->input('rate');
          $transaction->quantity = $request->input('quantity');
          $transaction->type = $request->input('type');
          $transaction->charges = $request->input('charges');
          $transaction->save();

          return back()->with('successMsg','Record saved succesfully!');
        }

        return view('transaction', compact('transactions', 'profit', 'buys', 'sells'));
    }
}
