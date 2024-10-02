<!DOCTYPE html>
<html>
<head>
    @include('admin.css')
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
        .status-active {
            background-color: #d4edda;
            color: #155724;
            padding: 5px 10px;
            border-radius: 5px;
            display: inline-block;
        }
        .status-inactive {
            background-color: #f8d7da;
            color: #721c24;
            padding: 5px 10px;
            border-radius: 5px;
            display: inline-block;
        }
        .custom-table {
            margin-top: 30px;
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body>
    @include('admin.header')
    @include('admin.sidebar')
    <div class="container mt-2">
    <div class="container mt-3">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Loan Plan</h1>
        </div>
        <button class="mb-2 btn btn-lg btn-success" data-toggle="modal" data-target="#addModal">
            <span class="fa fa-plus"></span> <i class="lni lni-plus"></i> Loan Plan
        </button>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Plan</th>
                                <th>Interest (%)</th>
                                <th>Overdue Penalty</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($loanPlans as $loanPlan)
                                <tr>
                                    <td>{{ $loanPlan->loanplan }}</td>
                                    <td>{{ $loanPlan->interest }}</td>
                                    <td>{{ $loanPlan->penalty }}</td>
                                    <td>
                                        <a href="{{ route('planedit', $loanPlan->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                        <form action="{{ route('plandestroy', $loanPlan->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this loan type?')">Delete</button>
                                    </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('planstore') }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header bg-success">
                            <h5 class="modal-title text-white">Loan Plan</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="loanplan">Loan Plan (Month)</label>
                                <input type="number" class="form-control" id="loanplan" name="loanplan" required>
                            </div>
                            <div class="form-group">
                                <label>Interest (%)</label>
                                <input type="number" id="interest" name="interest" class="form-control" required />
                            </div>
                            <div class="form-group">
                                <label>Overdue Penalty (%)</label>
                                <input type="number" id="penalty" name="penalty" class="form-control" required />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                            <button type="submit" name="save" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('admin.script')
</body>
</html>
