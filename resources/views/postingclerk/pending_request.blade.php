<!DOCTYPE html>
<html lang="en">
<head>
<base href="/public">
    @include('postingclerk.css')
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    @include('postingclerk.header')
    @include('postingclerk.sidebar')
    
    <div class="container mt-3">
        <h1 class="h3 mb-0 text-gray-800">Pending Applications</h1>

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
                                <th>Borrower Name</th>
                                <th>Spouse Name</th>
                                <th>Sex</th>
                                <th>Date of Birth</th>
                                <th>Amount Applied</th>
                                <th>Purpose</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($applications as $application)
                                <tr>
                                    <td>{{ $application->borrower_name }}</td>
                                    <td>{{ $application->spouse_name }}</td>
                                    <td>{{ $application->sex }}</td>
                                    <td>{{ $application->date_of_birth }}</td>
                                    <td>{{ $application->amount_applied }}</td>
                                    <td>{{ $application->purpose }}</td>
                                    <td>
                                    <a href="{{ route('postingclerk.app_details', ['id' => $application->id]) }}" class="btn btn-primary btn-sm">View</a>
<form action="{{ route('posting_clerk.apps.approve', $application->id) }}" method="POST" style="display:inline;">
    @csrf
    @method('POST')
    <button type="submit" class="btn btn-success btn-sm">Approve</button>
</form>
<form action="{{ route('posting_clerk.apps.reject', $application->id) }}" method="POST" style="display:inline;">
    @csrf
    @method('POST')
    <button type="submit" class="btn btn-danger btn-sm">Reject</button>
</form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No loan application requests for now</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('postingclerk.script')
</body>
</html>
