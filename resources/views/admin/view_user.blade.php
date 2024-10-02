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
        <div class="row">
    <form method="GET" action="{{ route('view_user') }}" class="form-inline mb-2">
                <div class="form-group mr-2">
                    <input type="text" name="search" class="form-control" placeholder="Search users" value="{{ request('search') }}">
                </div>
                <button type="submit" class="btn btn-info">Search</button>
            </form>
    </div>
        <div class="table-responsive">
        @if ($data->count())
            <table class="table table-striped table-bordered custom-table">
                <thead class="thead-green">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Usertype</th>
                        <th>Password</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $user) <!-- Renaming the variable here -->
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>{{ $user->usertype }}</td>
                        <td>{{ $user->password }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $data->links() }} 
        @endif
        </div>
    </div>
</body>
    @include('admin.script')
</html>
