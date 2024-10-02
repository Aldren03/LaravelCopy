<!DOCTYPE html>
<html lang="en">
<head>
<base href="/public">
    @include('admin.css')
</head>
<body>
    @include('admin.header')
    @include('admin.sidebar')
    
    <div class="container mt-4">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        <h1 class="h3 mb-4 text-green-600">Approved Applications</h1>

        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-striped table-bordered">
                    <thead class="thead-white">
                        <tr>
                            <th scope="col">Reference Number</th>
                            <th scope="col">Borrower Name</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $application)
                            <tr>
                                <td>{{ $application->reference_no }}</td>
                                <td>{{ $application->borrower_name }}</td>
                                <td>
                                    <a href="{{ route('application.show', ['id' => $application->id]) }}" class="btn btn-primary btn-sm">View Details</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('admin.script')
</body>
</html>
