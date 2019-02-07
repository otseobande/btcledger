@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">New Transaction</div>
                <div class="card-body">
                @if(Session::has('successMsg'))
                  <div class="alert alert-success"> {{ Session::get('successMsg') }}</div>
                @endif
                <form method="POST">
                  {{ csrf_field()}}
                  <div class="form-group">
                    <label for="exampleInputEmail1">Rate (&#8358;)</label>
                    <input
                      type="number"
                      class="form-control"
                      name="rate"
                      id="rate"
                      aria-describedby="rate"
                      placeholder="Enter rate"
                      required
                    >
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">BTC Quantity</label>
                    <input type="number" class="form-control" name="quantity" id="quantity" placeholder="Enter BTC quantity" step="any" required>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Charges (&#8358;)</label>
                    <input type="number" class="form-control" name="charges" id="charges" placeholder="Enter Charge" value="0" step="any">
                  </div>
                  <!-- <div class="form-group">
                    <label for="amount">Amount (&#8358;)</label>
                    <input type="number" class="form-control" name="amount" id="amount"  value="0" step="any">
                  </div> -->
                  <p>Transaction type</p>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="type" id="buy-type" value="buy" required>
                    <label class="form-check-label" for="buy-type">
                      Buy
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="type" id="sell-type" value="sell" required>
                    <label class="form-check-label" for="sell-type">
                      Sell
                    </label>
                  </div>
                  <br />
                  <button type="submit" class="btn btn-primary">Record</button>
                </form>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row justify-content-center">
      <div class="col-md-3">
        <div class="card">
          <div class="card-body">
            <h4>Total Sell Value</h4>
            &#8358;{{number_format($sells, 2)}}
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card">
          <div class="card-body">
            <h4>Total Buy Value</h4>
            &#8358;{{number_format($buys, 2)}}
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card">
          <div class="card-body">
            <h4>Profit</h4>
            &#8358;{{number_format($profit, 2)}}
          </div>
        </div>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">Transactions</div>
          <div class="card-body" style="overflow: auto">
            <table class="table table-sm">
              <thead>
                <tr>
                  <th scope="col">Rate (&#8358;)</th>
                  <th scope="col">Quantity (BTC)</th>
                  <th scope="col">Type</th>
                  <th scope="col">Charges (&#8358;)</th>
                  <th scope="col">Value (&#8358;)</th>
                  <th scope="col">Date</th>
                </tr>
              </thead>
              <tbody>
                @foreach($transactions as $transaction)
                  <tr>
                    <td>{{ number_format($transaction->rate) }}</td>
                    <td>{{ $transaction->quantity }}</td>
                    <td>{{ $transaction->type }}</td>
                    <td>{{ $transaction->charges }}</td>
                    <td>{{ number_format($transaction->value, 2) }}</td>
                    <td>{{ \Carbon\Carbon::parse($transaction->created_at) }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            {{ $transactions->links() }}
          </div>
        </div>
      </div>
    </div>
</div>
@endsection
