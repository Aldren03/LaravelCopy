<!DOCTYPE html>
<html>
<head>
       @include('collector.css')
       
</head>
<header>
    @include('collector.header')
</header>

<body>
    @include('collector.sidebar')

    <div class="container mt-2">
    <div class="table-responsive">
        
        <table class="table table-striped table-bordered custom-table">
            
            <thead class="thead-green">
                <tr>
                    <th>Borrower Name</th>
                    <th>Status</th>
                    <th>Profile Picture</th>
                    <th>Operation</th>
                </tr>
            </thead>
</div>

</body>
@include('collector.script')
</html>