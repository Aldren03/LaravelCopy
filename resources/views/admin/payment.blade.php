<!DOCTYPE html>
<html>
<head>
    @include('admin.css')
</head>
<header>
    @include('admin.header')
</header>

<body>
    @include('admin.sidebar')

    <div class="container mt-3">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Payment List</h1>
            </div>

            <div class="row">
                <button class="ml-3 mb-3 btn btn-lg btn-success" data-toggle="modal" data-target="#addModal">
                    <span class="fa fa-plus"> New Payment </span>
                </button>
            </div>

            <form method="GET" action="{{ route('payments.index') }}" class="form-inline mb-3">
                <div class="form-group mr-3">
                    <label for="date" class="mr-2">Select Date:</label>
                    <input type="date" name="date" id="date" class="form-control" value="{{ $selectedDate }}">
                </div>
                <button type="submit" class="btn btn-info">Search</button>
            </form>

            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        @if ($payments->count())
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Loan Reference No.</th>
                                        <th>Payee</th>
                                        <th>Amount</th>
                                        <th>Payment Date</th>
                                        <th>Date Paid</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                    <tr>
                                        <td>{{ $payment->ledger->ref_no }}</td>
                                        <td>{{ $payment->borrower->borrower_name }}</td>
                                        <td>&#8369; {{ number_format($payment->pay_amount, 2) }}</td>
                                        <td>{{ $payment->created_at->format('Y-m-d') }}</td>
                                        <td>{{ $payment->payment_date }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $payments->links() }} 
                        @else
                            <p>No payments found for this date.</p>
                        @endif
                    </div>
                </div>
            </div>

   
            <div class="modal fade" id="addModal" aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('payments.store') }}">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header bg-success">
                                <h5 class="modal-title text-white">Payment Form</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="ledger_id">Loan Reference No.</label>
                                    <select name="ledger_id" id="ledger_id" class="form-control" required>
                                        <option value="">Select Loan</option>
                                        @foreach($ledgers as $ledger)
                                            <option value="{{ $ledger->id }}">{{ $ledger->ref_no }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <input type="hidden" name="borrower_id" id="admin_borrower_id">
                                <div class="form-group">
                                    <label for="admin_payment_date">Payment Date</label>
                                    <select name="payment_date" id="admin_payment_date" class="form-control" required>
                                        <option value="">Select Date</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="admin_borrower_name">Borrower</label>
                                    <input type="text" id="admin_borrower_name" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="admin_daily_payment">Payment</label>
                                    <input type="text" id="admin_daily_payment" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="admin_penalty">Penalty</label>
                                    <input type="number" name="penalty" id="admin_penalty" class="form-control" step="0.01" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="admin_pay_amount">Daily Payable Amount</label>
                                    <input type="number" name="pay_amount" id="admin_pay_amount" class="form-control" step="0.01" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-info">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('admin.script')
</body>
</html>
