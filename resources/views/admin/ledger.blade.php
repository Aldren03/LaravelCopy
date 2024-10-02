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
            <h1 class="h3 mb-0 text-gray-800">Loan List</h1>
        </div>
        <button class="mb-2 btn btn-lg btn-success" data-toggle="modal" data-target="#addModal"><span class="fa fa-plus"></span> Create new Loan Application</button>
        <div class="card shadow mb-4">
            <div class="card-body">
            <form method="GET" action="{{ route('loans.index') }}" class="form-inline mb-3 d-flex justify-content-between">
                    <div class="input-group" style="width: 300px; max-width: 100%;">
                        <input type="text" name="search" class="form-control" placeholder="Search Borrower" value="{{ request()->input('search') }}" style="border-radius: 0.25rem 0 0 0.25rem;">
                        <div class="input-group-append">
                            <button class="btn btn-success" type="submit" style="border-radius: 0 0.25rem 0.25rem 0;">Search</button>
                        </div>
                    </div>
                    <div class="form-group ml-auto">
                        <label for="date" class="mr-2">Select Date:</label>
                        <input type="date" name="date" id="date" class="form-control" value="{{ $selectedDate }}">
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
                            @foreach($ledgers as $ledger)
                                <tr>
                                <td>
                                    @if($ledger->borrower)
                                    <p><small>Name: <strong>{{ $ledger->borrower->borrower_name }}</strong></p>
                                    <p>Contact Number: <strong>{{ $ledger->borrower->contact_number }}</strong></p>
                                    <p>Address: <strong>{{ $ledger->borrower->borrower_address }}</strong></small></p>
                                    @elseif($ledger->borrowerForm)
                                    <p><small>Name: <strong>{{ $ledger->borrowerForm->borrower_name }}</strong></p>
                                    <p>Contact Number: <strong>{{ $ledger->borrowerForm->contact_number }}</strong></p>
                                    <p>Address: <strong>{{ $ledger->borrowerForm->home_address }}</strong></small></p>
                                    @else
                                    <p><small>No borrower or borrower form selected.</small></p>
                                    @endif
                                </td>
                                    <td>
                                        <p><small>Loan Type: <strong> {{ $ledger->loanType->loan_type }} </strong></p>
                                        <p>Loan Plan: <strong> {{ $ledger->loanPlan->loanplan }} months [{{ $ledger->loanPlan->interest }}%, {{ $ledger->loanPlan->penalty }}%]</strong></p></small>
                                        <p><small>Amount: <strong>&#8369; {{ number_format($ledger->amount, 2) }}</strong></small></p>
                                        <p><small>Total Payable Amount: <strong>&#8369; {{ number_format($ledger->payable_amount, 2) }}</strong></small></p>
                                    </td>
                                    <td>
                                        @if ($ledger->status == 2)
                                            @if ($ledger->loanSchedule)
                                                <p><small>Reference Number: <strong>{{ $ledger->ref_no }}</strong></small></p>
                                                <p><small>Released Date: <strong>{{ \Carbon\Carbon::parse($ledger->date_released)->format('F d, Y') }}</strong></small></p>
                                                <p><small>Next Payment Date: <strong>{{ \Carbon\Carbon::parse($ledger->loanSchedule->date)->format('F d, Y') }}</strong></small></p>
                                                <p><small>Monthly Payable Amount: <strong>&#8369; {{ number_format($ledger->monthly, 2) }}</strong></small></p>
                                            @endif
                                        @endif
                                        <p><small>Daily Payable Amount: <strong>&#8369; {{ number_format($ledger->daily, 2) }}</strong></small></p>
                                        <p><small>Overdue Payable Amount: <strong>&#8369; {{ number_format($ledger->penalty, 2) }}</strong></small></p>
                                    </td>
                                    <td>
                                    @if ($ledger->status === 'pending')
                                        <span class="badge badge-warning">For Approval</span>
                                    @elseif ($ledger->status == 1)
                                        <span class="badge badge-info">Approved</span>
                                    @elseif ($ledger->status == 2)
                                        <span class="badge badge-success">Released</span>
                                    @elseif ($ledger->status == 3)
                                        <span class="badge badge-primary">Completed</span>
                                    @elseif ($ledger->status == 4)
                                        <span class="badge badge-danger">Denied</span>
                                    @endif
                                    </td>
                                    <td>
                                        @if ($ledger->status == 2)
                                            <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#viewSchedule{{ $ledger->id }}">View Payment Schedule</button>
                                        @elseif ($ledger->status == 3)
                                            <button class="btn btn-lg btn-success" readonly="readonly">COMPLETED</button>
                                        @else
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Action
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item bg-warning text-white" href="#" data-toggle="modal" data-target="#updateloan{{ $ledger->id }}">Edit</a>
                                                    <a class="dropdown-item bg-danger text-white" href="#" data-toggle="modal" data-target="#deletemodal{{ $ledger->id }}">Delete</a>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="modal fade" id="viewSchedule{{ $ledger->id }}" tabindex="-1" aria-labelledby="viewScheduleLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-success">
                                                        <h5 class="modal-title text-white">Payment Schedule</h5>
                                                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6 col-xl-6">
                                                                <p>Reference No:</p>
                                                                <p><strong>{{ $ledger->ref_no }}</strong></p>
                                                            </div>
                                                            <div class="col-md-6 col-xl-6">
                                                                <p>Full Name:</p>
                                                                @if($ledger->borrower)
                                                                <p><strong>{{ $ledger->borrower->borrower_name }}</strong></p>
                                                                @elseif($ledger->borrowerForm)
                                                                <p><strong>{{ $ledger->borrowerForm->borrower_name }}</strong></p>
                                                                @else
                                                                <p><small>No borrower or borrower form selected.</small></p>
                                                                @endif

                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 col-xl-6">
                                                                <p>Total Balance</p>
                                                                <p><strong>&#8369; {{ number_format($ledger->payable_amount, 2) }}</strong></p>
                                                                
                                                            </div>
                                                            <div class="col-md-6 col-xl-6">
                                                                <p>Total Months: </p>
                                                                <p><strong>{{ $ledger->loanPlan->loanplan }} months</strong></p>
                                                            </div>
                                                        </div>
                                                        <hr />
                                                        <div class="row">
                                                            <div class="col-md-12 col-xl-12">
                                                                <table class="table table-bordered">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Due Date</th>
                                                                            <th>Daily Payment</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @forelse ($loanSchedules[$ledger->id] as $schedule)
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

       
                                         <div class="modal fade" id="updateloan{{ $ledger->id }}" tabindex="-1" aria-labelledby="updateloanLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Loan</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('loan.update', $ledger->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-row">
                        <div class="form-group col-xl-6 col-md-6">
                            <label>Status</label>
                            <select class="form-control" name="status">
                                <option value="0" {{ $ledger->status == 0 ? 'selected' : '' }}>For Approval</option>
                                <option value="1" {{ $ledger->status == 1 ? 'selected' : '' }}>Approved</option>
                                <option value="2" {{ $ledger->status == 2 ? 'selected' : '' }}>Released</option>
                                <option value="2" {{ $ledger->status == 3 ? 'selected' : '' }}>Completed</option>
                                <option value="4" {{ $ledger->status == 4 ? 'selected' : '' }}>Denied</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-info">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



                                      
<div class="modal fade" id="deletemodal{{ $ledger->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white" id="deleteModalLabel">Delete Loan Application</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('ledgerdestroy', $ledger->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <p>Are you sure you want to delete this loan application?</p>
                        <br>
                        <button type="submit" class="btn btn-danger">Delete</button> 
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $ledgers->links() }}
            </div>
        </div>
    </div>
</div>

   
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title text-white">Add New Loan Application</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('loan.store') }}" method="POST">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-xl-6 col-md-6">
                            <label>Borrower</label>
                            <select name="borrower_id" class="borrow" style="width:100%;">
                                <option value=""></option>
                                @foreach($borrowers as $borrower)
                                    <option value="{{ $borrower->id}}">{{ $borrower->borrower_name}} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-xl-6 col-md-6">
                        <label>Borrower Form</label>
                        <select id="borrower_form_id" name="borrower_form_id" style="width:100%;">
                            <option value=""></option>
                            @foreach($borrowerForms as $borrowerForm)
                                <option value="{{ $borrowerForm->id }}">{{ $borrowerForm->borrower_name }}</option>
                            @endforeach
                        </select>
                    </div>
                        <div class="form-group col-xl-6 col-md-6">
                            <label>Loan Type</label>
                            <select name="loan_type_id" class="loan" required="required" style="width:100%;">
                                <option value=""></option>
                                @foreach($loanTypes as $loanType)
                                    <option value="{{ $loanType->id }}">{{ $loanType->loan_type }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-xl-6 col-md-6">
                            <label>Loan Plan</label>
                            <select name="loan_plan_id" class="form-control" required="required" id="lplan">
                                <option value="">Please select an option</option>
                                @foreach($loanPlans as $loanPlan)
                                    <option value="{{ $loanPlan->id }}" data-interest="{{ $loanPlan->interest }}" data-penalty="{{ $loanPlan->penalty }}" data-months="{{ $loanPlan->loanplan }}">
                                        {{ $loanPlan->loanplan }} months [{{ $loanPlan->interest }}%, {{ $loanPlan->penalty }}%]
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-xl-6 col-md-6">
                            <label>Loan Amount</label>
                            <input type="number" name="amount" class="form-control" id="amount" required="required"/>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-xl-6 col-md-6">
                            <label>Purpose</label>
                            <textarea name="purpose" class="form-control" style="resize:none; height:200px;" required="required"></textarea>
                        </div>
                        <div class="form-group col-xl-6 col-md-6">
                            <button type="button" class="btn btn-success btn-block" id="calculate">Calculate Amount</button>
                        </div>
                    </div>
                    <hr>
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
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="apply" class="btn btn-primary">Apply</button>
                </div>
        </div>
    </div>
</div>   
</div>

    @include('admin.script')
</body>
</html>
