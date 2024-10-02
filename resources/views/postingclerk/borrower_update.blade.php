<!DOCTYPE html>
<html>
<head>
       @include('postingclerk.css')
       
</head>
<header>
    @include('postingclerk.header')
</header>

<body>
    
    @include('postingclerk.sidebar')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1 class="mb-4 text-center">Edit Employee's Data</h1>
                <form action="{{url('edit_borrower', $data->id)}}" method="POST">
            @csrf
                <div class="modal-body">
                    <label>Reference Number: <strong>{{ $data->reference_no }}</strong></label>
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" id="borrower_name" name="borrower_name" class="form-control" value="{{$data->borrower_name}}" />
                    </div>
                    <div class="form-group">
                        <label>Contact No</label>
                        <input type="tel" id="contact_number" name="contact_number" class="form-control" placeholder="Eg.[0965 567 6544]" pattern="[0-9]{4} [0-9]{3} [0-9]{4}" value="{{$data->contact_number}}" />
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" id="borrower_address" name="borrower_address" class="form-control" value="{{$data->borrower_address}}" />
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" id="email" name="email" class="form-control" required maxlength="30" value="{{$data->email}}" />
                    </div>
                </div>
                <div class="modal-footer">
                <button type="submit" class="btn btn-primary w-100" onclick="return confirm('Do you really want to update this?');"
                >Save Changes</button> 
                </div>
            </div>
        </form>
</div>
</div>
        

</body>
@include('postingclerk.script')
</html>