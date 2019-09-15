<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaction;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Exports\TransactionsExport;

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
        $startDate = new Carbon($request->input('startDate', Carbon::now()->toDateString()));
        $endDate = new Carbon($request->input('endDate', Carbon::now()->addDay()->toDateString()));

        if ($startDate == $endDate) {
          $endDate = (new Carbon($startDate))->addDay();
        }

        $transactions = Auth::user()
          ->transactions()
          ->whereBetween('created_at', [$startDate, $endDate])
          ->orderBy('created_at', 'desc')->paginate();


        $sellCollection = Auth::user()
          ->transactions()
          ->whereBetween('created_at', [$startDate, $endDate])
          ->where('type', 'sell')
          ->get();

        $sells = $sellCollection->map(function ($transaction) {
            return $transaction->value;
          })
          ->sum();

        $soldBtc = $sellCollection->map(function ($transaction) {
            return $transaction->quantity;
          })
          ->sum();

        $buyCollection = Auth::user()
          ->transactions()
          ->whereBetween('created_at', [$startDate, $endDate])
          ->where('type', 'buy')
          ->get();

        $buys = $buyCollection->map(function ($transaction) {
            return $transaction->value;
          })
          ->sum();

        $boughtBtc = $buyCollection->map(function ($transaction) {
          return $transaction->quantity;
        })
        ->sum();

        $averageBuyRate = $buyCollection->filter(function ($buyRecord) {
            return $buyRecord->rate > 0;
        })->map(function ($transaction) {
            return $transaction->rate;
        })
        ->average();

        $averageSellRate = $sellCollection->filter(function ($sellRecord) {
            return $sellRecord->rate > 0;
        })->map(function ($transaction) {
            return $transaction->rate;
        })
        ->average();

        $profit = $sells - $buys;
        $btcAvailable = $boughtBtc - $soldBtc;

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

        $startDate = $startDate->toDateString();
        $endDate = $endDate->toDateString();

        return view('transaction', compact(
          'transactions',
          'profit',
          'buys',
          'sells',
          'startDate',
          'endDate',
          'btcAvailable',
          'boughtBtc',
          'soldBtc',
          'averageBuyRate',
          'averageSellRate'
        ));
    }

    public function delete ($id) {
      $transaction = Transaction::where([
        ['user_id', Auth::id()],
        ['id', $id]
      ]);

      $transaction->delete();

      return back()->with('successMsg','Record deleted successfully');
    }

    public function download (Request $request) {
        $startDate = new Carbon($request->input('startDate', Carbon::now()->toDateString()));
        $endDate = new Carbon($request->input('endDate', Carbon::now()->addDay()->toDateString()));

        if ($startDate == $endDate) {
          $endDate = (new Carbon($startDate))->addDay();
        }

        return (new TransactionsExport($startDate, $endDate))
            ->download('transactions.xlsx');
    }
}
