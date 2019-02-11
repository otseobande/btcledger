@extends('layouts.app')

@section('content')
<div class="container">
    @if(Session::has('successMsg'))
      <div class="alert alert-success"> {{ Session::get('successMsg') }}</div>
    @endif
    <div class="row justify-content-between mb-3" style="text-align: center;">
      <div class="col-md-3">
        <div class="card">
          <div class="card-body p-4">
            <h4>BTC Available</h4>
            {{number_format($btcAvailable, 10)}}BTC
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card">
          <div class="card-body p-4">
            <h4>Total Sell Amount</h4>
            &#8358;{{number_format($sells, 2)}}
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card">
          <div class="card-body p-4">
            <h4>Total Buy Amount</h4>
            &#8358;{{number_format($buys, 2)}}
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card {{ $profit >= 0 ? 'profit' : 'loss'}}">
          <div class="card-body p-4">
            <h4>Profit</h4>
            &#8358;{{number_format($profit, 2)}}
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header d-flex flex-row align-items-center justify-content-between">
            <span>Transactions</span>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#newTransaction">
              New transaction <i class="fa fa-plus"></i>
            </button>
          </div>
          <div class="card-body" style="overflow: auto">
            <div class="mb-2">
              <form>
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="startDate">Start Date:</label>
                      <input
                        type="date"
                        name="startDate"
                        class="form-control"
                        value="{{$startDate}}"
                        v-model="startDate"
                        required
                      />
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="endDate">End Date:</label>
                      <input
                        type="date"
                        name="endDate"
                        class="form-control"
                        :min="startDate"
                        value={{$endDate}}
                        required
                      />
                    </div>
                  </div>

                </div>
                <button type="submit" class="btn btn-primary">
                  Filter <i class="fa fa-filter"></i>
                </button>
              </form>
            </div>
            @if($transactions->count() > 0)
              <table class="table table-sm">
                <thead>
                  <tr>
                    <th scope="col">Rate</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Type</th>
                    <th scope="col">Charges</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Date</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($transactions as $transaction)
                    <tr>
                      <td>&#8358;{{ number_format($transaction->rate) }}</td>
                      <td>{{ $transaction->quantity }}BTC</td>
                      <td>{{ $transaction->type }}</td>
                      <td>&#8358;{{ number_format($transaction->charges, 2) }}</td>
                      <td>&#8358;{{ number_format($transaction->value, 2) }}</td>
                      <td>{{ \Carbon\Carbon::parse($transaction->created_at)->toDayDateTimeString() }}</td>
                      <td>
                        <button class="btn btn-sm btn-danger" @click="handleDelete({{$transaction->id}})">
                          Delete <i class="fa fa-trash"></i>
                        </button>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              {{ $transactions->appends(request()->query())->links() }}
            @else
              <div class="text-center" style="font-size: 20px;">No transaction found</div>
            @endif
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="newTransaction" tabindex="-1" role="dialog" aria-labelledby="newTransactionLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="newTransactionLabel">New Transaction</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
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
                  v-model="rate"
                  step="any"
                  required
                >
              </div>
              <div class="form-group">
                <label for="exampleInputPassword1">BTC Quantity</label>
                <input
                  type="number"
                  class="form-control"
                  name="quantity"
                  id="quantity"
                  placeholder="Enter BTC quantity"
                  step="any"
                  v-model="quantity"
                  required
                >
              </div>
              <div class="form-group">
                <label for="exampleInputPassword1">Charges (&#8358;)</label>
                <input type="number" class="form-control" name="charges" id="charges" placeholder="Enter Charge" value="0" step="any">
              </div>
              <div class="form-group">
                <label for="amount">Amount (&#8358;)</label>
                <input
                  type="number"
                  class="form-control"
                  name="amount"
                  id="amount"
                  value="0"
                  step="any"
                  v-model="amount"
                >
              </div>
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
              <button type="submit" class="btn btn-success">Record <i class="fa fa-save"></i></button>
              <button type="button" class="btn btn-secondary float-right" data-dismiss="modal">Close</button>
            </form>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection
