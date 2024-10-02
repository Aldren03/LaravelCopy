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
    <form action="{{ route('borrowers.view_all') }}" method="GET">
    <button class="btn btn-info" type="submit">Search</button>
    <input type="text" name="search" placeholder="Search for borrowers" value="{{ request('search') }}">
</form>
        </form>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
    </div>
    <div style="margin-bottom: 20px;">
            <button class="mb-2 btn btn-lg btn-success" data-toggle="modal" data-target="#addModal">
                <span class="fa fa-plus"></span> <i class="lni lni-plus"></i> Add Borrower
            </button>
        </div>
        <h1 style="
            font-size: 1.5rem;
            font-weight: 600; 
            color: #343a40; 
            
            padding: 10px 20px; 
            background-color: #C0E6BA; 
            border-radius: 10px; 
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5); 
            text-align: center;
        ">
            Walk-in Borrowers
        </h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Reference No: </th>
                            <th>Full Name:</th>
                            <th>Contact Number: </th>
                            <th>Address: </th>
                            <th>Email: </th>
                            <th>Action </th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $borrowers)
                <tr>
                    <td>{{ $borrowers->reference_no }}</td>
                    <td>{{ $borrowers->borrower_name }}</td>
                    <td>{{ $borrowers->contact_number }}</td>
                    <td>{{ $borrowers->borrower_address }}</td>
                    <td>{{ $borrowers->email }}</td>
                    <td>
                            <a class="btn btn-primary" href="{{ route('update_borrower', $borrowers->id) }}">Edit</a>
                            <a onclick="return confirm('Are you sure to delete this?');" class="btn btn-danger" href="{{ route('borrower_delete', $borrowers->id) }}">Delete</a>
                        </td>
                </tr>
            @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
    </div>
    <h1 style=" font-size: 1.5rem; font-weight: 600; color: #343a40; padding: 10px 20px; background-color: #C0E6BA; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5); text-align: center;">
        Approved Online Applications
    </h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Reference No</th>
                            <th>Full Name</th>
                            <th>Amount Applied</th>
                            <th>Address</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($applications as $application)
                                <tr>
                                    <td>{{ $application->reference_no }}</td>
                                    <td>{{ $application->borrower_name }}</td>
                                    <td>{{ $application->amount_applied }}</td>
                                    <td>{{ $application->home_address }}</td>
                                    <td>{{ $application->email }}</td>
                                    <td>
                                    <a onclick="return confirm('Are you sure to delete this?');" class="btn btn-danger" href="{{ route('borrower_delete', $application->id) }}">Delete</a>
                                        <a href="{{ route('application.show', ['id' => $application->id]) }}" class="btn btn-info btn-sm">View</a>
                                    </td>
                                </tr>
                            @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Borrower Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('add_new_borrower') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title text-white">Add Borrower</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="lni lni-close"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" id="borrower_name" name="borrower_name" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label>Contact No</label>
                        <input type="tel" id="contact_number" name="contact_number" class="form-control" placeholder="Eg.[0965 567 6544]" pattern="[0-9]{4} [0-9]{3} [0-9]{4}" required />
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" id="borrower_address" name="borrower_address" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" id="email" name="email" class="form-control" required maxlength="30" />
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

</body>
@include('postingclerk.script')
</html>
