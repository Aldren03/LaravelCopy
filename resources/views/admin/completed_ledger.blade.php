<!DOCTYPE html>
<html>
<head>
    @include('admin.css')
</head>
<body>
<header>
    @include('admin.header')
</header>
@include('admin.sidebar')


<div class="container mt-3">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Completed Ledger</h1>
    </div>

    <button class="mb-2 btn btn-lg btn-success" data-toggle="modal" data-target="#addModal">
        <span class="fa fa-plus"></span> Create Completed Ledger
    </button>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="" class="form-inline mb-3 d-flex justify-content-between">
                <div class="input-group" style="width: 300px; max-width: 100%;">
                    <input type="text" name="search" class="form-control" placeholder="Search Borrower" value="{{ request()->input('search') }}" style="border-radius: 0.25rem 0 0 0.25rem;">
                    <div class="input-group-append">
                        <button class="btn btn-success" type="submit" style="border-radius: 0 0.25rem 0.25rem 0;">Search</button>
                    </div>
                </div>
                <div class="form-group ml-auto">
                    <label for="date" class="mr-2">Select Date:</label>
                    <input type="date" name="date" id="date" class="form-control" value="">
                </div>
                <button type="submit" class="btn btn-info ml-2">Filter</button>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Borrower</th>
                            <th>Loan Detail</th>
                            <th>Payment Detail</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                            @foreach ($completedLedgers as $ledger)
                            <tr>
                                <td>
                                    <p><small>Name: <strong>{{ $ledger->first_name }} {{ $ledger->last_name }}</strong></small></p>
                                    <p><small>Contact Number: <strong>{{ $ledger->contact_number }}</strong></small></p>
                                    <p><small>Brgy: <strong> {{ $ledger->home_address }}, {{ $ledger->municipality }}, Tarlac, Philippines</strong></small></p>
                                </td>
                                <td>
                                <p><small>Loan Type: <strong> {{ $ledger->loanType->loan_type }} </strong></p>
                                <p>Loan Plan: <strong> {{ $ledger->loanPlan->loanplan }} months [{{ $ledger->loanPlan->interest }}%, {{ $ledger->loanPlan->penalty }}%]</strong></p></small>
                                    <p><small>Amount: <strong>&#8369;{{ number_format($ledger->amount, 2) }}</strong></small></p>
                                    <p><small>Total Payable Amount: <strong>&#8369;{{ number_format($ledger->total_payable_amount, 2) }}</strong></small></p>
                                </td>
                                <td>
                                    <p><small>Reference Number: <strong>{{ $ledger->reference_number }}</strong></small></p>
                                    <p><small>Released Date: <strong>{{ $ledger->start_date }}</strong></small></p>
                                    <p><small>End Date: <strong>{{ $ledger->end_date }}</strong></small></p>
                                </td>
                                <td>
                                    <span class="badge badge-success">{{ strtoupper($ledger->status) }}</span>
                                </td>
                                <td>
                                    <button class="btn btn-info" data-toggle="modal" data-target="#paymentModal{{ $ledger->id }}">
                                        View Payment Schedule
                                    </button>
                                    <!-- Payment Modal -->
                                    <div class="modal fade" id="paymentModal{{ $ledger->id }}" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-success">
                                                    <h5 class="modal-title text-white" id="paymentModalLabel">Payment Schedule</h5>
                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p>Reference No:</p>
                                                            <p><strong>{{ $ledger->reference_number }}</strong></p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p>Full Name:</p>
                                                            <p><strong>{{ $ledger->first_name }} {{ $ledger->last_name }}</strong></p>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p>Total Balance</p>
                                                            <p><strong>&#8369;{{ number_format($ledger->total_balance, 2) }}</strong></p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p>Total Months:</p>
                                                            <p><strong>{{ $ledger->loanPlan->loanplan }} months</strong></p>
                                                        </div>
                                                    </div>
                                                    <hr />
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <table class="table table-bordered">
                                                            <thead>
                                                                        <tr>
                                                                            <th>Due Date</th>
                                                                            <th>Daily Payment</th>
                                                                        </tr>
                                                            </thead>
                                                                    <tbody>
                                                                @forelse ($completedSchedules[$ledger->id] as $schedule)
                                                                        <tr>
                                                                            <td>{{ \Carbon\Carbon::parse($schedule->date)->format('l F d, Y') }}</td>
                                                                            <td>
                                                                                @if ($schedule->status !== 'paid')
                                                                                &#8369;{{ number_format($ledger->daily, 2) }}
                                                                                @else
                                                                                <span class="badge badge-success">Paid</span>
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                        @empty
                                                                        <tr>
                                                                            <td colspan="2">No due dates available</td>
                                                                        </tr>
                                                                        @endforelse
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            
                    </tbody>
                </table>
                {{ $completedLedgers->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Add Completed Ledger Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title text-white">Add Completed Loan Account</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('completed-ledger.add') }}" method="POST">
                    @csrf

                    <!-- Borrower Information -->
                    <div class="form-row">
                        <div class="form-group col-xl-6 col-md-6">
                            <label>First Name</label>
                            <input type="text" name="first_name" class="form-control" required />
                        </div>
                        <div class="form-group col-xl-6 col-md-6">
                            <label>Last Name</label>
                            <input type="text" name="last_name" class="form-control" required />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-xl-6 col-md-6">
                            <label>Contact Number</label>
                            <input type="text" name="contact_number" class="form-control" required />
                        </div>
                        <div class="form-group col-xl-6 col-md-6">
                            <label for="municipality">Municipality:</label>
                            <select name="municipality" id="municipality" class="form-control" required>
                                <option value="">Select Municipality</option>
                                <option value="Anao">Anao</option>
                                <option value="Bamban">Bamban</option>
                                <option value="Camiling">Camiling</option>
                                <option value="Capas">Capas</option>
                                <option value="Concepcion">Concepcion</option>
                                <option value="Gerona">Gerona</option>
                                <option value="LaPaz">La Paz</option>
                                <option value="Mayantoc">Mayantoc</option>
                                <option value="Moncada">Moncada</option>
                                <option value="Paniqui">Paniqui</option>
                                <option value="Pura">Pura</option>
                                <option value="Ramos">Ramos</option>
                                <option value="SanClemente">San Clemente</option>
                                <option value="SanJose">San Jose</option>
                                <option value="SanManuel">San Manuel</option>
                                <option value="SantaIgnacia">Santa Ignacia</option>
                                <option value="TarlacCity">Tarlac City</option>
                                <option value="Victoria">Victoria</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row" id="home-address-row" style="display: none;">
                        <div class="form-group col-xl-6 col-md-6" >
                            <label for="home_address">Barangay:</label>
                            <select name="home_address" id="home_address" class="form-control">
                                <option value="">Select Barangay</option>
                            </select>
                        </div>
                    </div>

                    <!-- Loan Details -->
                    <div class="form-row">
                        <div class="form-group col-xl-6 col-md-6">
                            <label>Loan Type</label>
                            <select name="loan_type_id" class="form-control" required>
                                <option value="" disabled selected>Select Loan Type</option>
                                @foreach($loanTypes as $loanType)
                                    <option value="{{ $loanType->id }}">{{ $loanType->loan_type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-xl-6 col-md-6">
                            <label>Loan Plan</label>
                            <select name="loan_plan_id" class="form-control" id="lplan" required>
                                <option value="" disabled selected>Select Loan Plan</option>
                                @foreach($loanPlans as $loanPlan)
                                    <option value="{{ $loanPlan->id }}" data-interest="{{ $loanPlan->interest }}" data-penalty="{{ $loanPlan->penalty }}" data-months="{{ $loanPlan->loanplan }}">
                                        {{ $loanPlan->loanplan }} months [{{ $loanPlan->interest }}%, {{ $loanPlan->penalty }}%]
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-xl-6 col-md-6">
                            <label>Amount</label>
                            <input type="number" name="amount" class="form-control" id="amount" required />
                        </div>
                        <div class="form-group col-xl-6 col-md-6">
                            <label>Start Date</label>
                            <input type="date" name="start_date" class="form-control" required />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-xl-6">
                            <button type="button" class="btn btn-success btn-block" id="calculate">Calculate Amount</button>
                        </div>
                    </div>

                    <!-- Calculation Results -->
                    <div class="row" id="calcTable">
                        <div class="col-xl-4 col-md-4">
                            <center><span>Total Payable Amount</span></center>
                            <center><strong id="tpa">₱ 0.00</strong></center>
                        </div>
                        <div class="col-xl-4 col-md-4">
                            <center><span>Daily Payable Amount (Mon-Fri)</span></center>
                            <center><strong id="dpa">₱ 0.00</strong></center>
                        </div>
                        <div class="col-xl-4 col-md-4">
                            <center><span>Penalty Amount</span></center>
                            <center><strong id="pa">₱ 0.00</strong></center>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Add Ledger</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('admin.script')
</body>
</html>
