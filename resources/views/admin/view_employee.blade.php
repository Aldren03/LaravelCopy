<!DOCTYPE html>
<html>
<head>
    @include('admin.css')
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
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
        }
        .modal-content {
            border-radius: 0.5rem;
        }
        .small-search-form {
            max-width: 300px;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(212, 237, 218, 0.5); /* Light green background for table rows */
        }
    </style>
</head>
<body>
    @include('admin.header')
    @include('admin.sidebar')
    


    <div class="container mt-4">
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
        
        <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 style=" font-size: 1.5rem; font-weight: 600; color: #343a40; padding: 10px 20px; background-color: #C0E6BA; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5); text-align: center;">
            Employee List
        </h1>
            <button class="btn btn-lg btn-primary" data-toggle="modal" data-target="#addEmployeeModal"><i class="lni lni-plus"></i> Add Employee</button>
        </div>

        <form action="{{ route('view_employee') }}" method="GET" class="mb-3 small-search-form">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search by Employee Name" name="search" value="{{ request('search') }}">
                <div class="input-group-append">
                    <button class="btn btn-info" type="submit">Search</button>
                </div>
            </div>
        </form>
        <div class="table-responsive">
        
            <table class="table table-striped table-bordered custom-table">
                
                <thead class="thead-green">
                    <tr>
                        <th>Employee Name</th>
                        <th>Position</th>
                        <th>Status</th>
                        <th>Profile Picture</th>
                        <th>Operation</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $employee)
                    <tr>
                        <td>{{ $employee->employee_name }}</td>
                        <td>{{ $employee->position }}</td>
                        <td>
                            @if($employee->status == 'active')
                            <span class="status-active">Active</span>
                            @else
                            <span class="status-inactive">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <img width="100" class="img-fluid" src="{{ asset('employee/'.$employee->image) }}">
                        </td>
                        <td>
                            <a class="btn btn-primary" href="{{ route('update_employee', $employee->id) }}">Edit</a>
                            <a onclick="return confirm('Are you sure to delete this?');" class="btn btn-danger" href="{{ route('employee_delete', $employee->id) }}">Delete</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('add_employee') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="addEmployeeModalLabel">Add Employee</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="lni lni-close"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="employeeName">Employee Name</label>
                            <input type="text" class="form-control" id="employeeName" name="employeeName" required>
                        </div>
                        <div class="form-group">
                            <label for="position">Position</label>
                            <select class="form-control" id="position" name="position" required>
                                <option>Manager</option>
                                <option>Posting Clerk</option>
                                <option>Credit Investigator</option>
                                <option>Collector</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                        <div class="form-group">
                            <label for="image">Upload Image</label>
                            <input type="file" class="form-control-file" id="image" name="image">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Add Employee</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('admin.script')
</body>
</html>
