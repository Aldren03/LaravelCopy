<!DOCTYPE html>
<html>
<head>
    <base href="/public">
       @include('manager.css')
</head>
<style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #343a40;
        }
        label {
            font-weight: 600;
            color: #495057;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>

<body>
    @include('manager.header')
    
    @include('manager.sidebar')
    
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1 class="mb-4 text-center">Edit Employee's Data</h1>
                <form action="{{url('employee_edit', $data->id)}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="employeeName" class="form-label">Employee Name</label>
                        <input type="text" id="employeeName" name="employeeName" class="form-control" 
                        value="{{$data->employee_name}}">
                    </div>
                    <div class="mb-3">
                        <label for="position" class="form-label">Position: </label>
                        <select id="position" name="position" class="form-multiselect">
                        <option selected value="{{($data->position)}}">{{($data->position)}}</option>
                            <option>Manager</option>
                            <option>Posting Clerk</option>
                            <option>Credit Investigator</option>
                            <option>Collector</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status: </label>
                        <select id="status" name="status" class="form-select" >
                        <option selected value="{{($data->status)}}">{{($data->status)}}</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Current Image</label>
                        <img width="300" src="/employee/{{$data->image}}">
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Upload Image</label>
                        <input type="file" id="image" name="image" class="form-control">
                    </div>
                    <div class="mb-3">
                        
                        <button type="submit" class="btn btn-primary w-100" onclick="return confirm('Do you really want to update this?');"
                        >Save Changes</button> 
                    </div>
                </form>
            </div>
        </div>
    </div>
        
    @include('manager.script')
</body>

</html>