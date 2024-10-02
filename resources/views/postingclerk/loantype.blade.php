<!DOCTYPE html>
<html lang="en">
<head>
    @include('postingclerk.css')
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
        .container {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body>
    @include('postingclerk.header')
    @include('postingclerk.sidebar')

    <div class="container mt-3">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Loan Type</h1>
        </div>
        <button class="mb-2 btn btn-lg btn-success" data-toggle="modal" data-target="#addModal">
            <span class="fa fa-plus"></span> <i class="lni lni-plus"></i> Loan Type
        </button>
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Loan Type</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($loanTypes as $loanType)
                                <tr>
                                    <td>{{ $loanType->loan_type }}</td>
                                    <td>{{ $loanType->description }}</td>
                                    <td>
                                        <form action="{{ route('delete_loantype', $loanType->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Loan type Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('add_loantype') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title text-white">Loan Type</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="loantype">Loan Type</label>
                            <select class="form-control" id="loantype" name="loantype" required>
                                <option>Agricultural Loan</option>
                                <option>Business Loan</option>
                                <option>Commercial Loan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" id="description" name="description" class="form-control" required />
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

    @include('postingclerk.script')
</body>
</html>
