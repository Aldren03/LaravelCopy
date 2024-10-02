<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Employee;
use App\Models\BorrowerForm;
use App\Models\Borrower;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Hash;

class ManagerController extends Controller
{
    public function addEmployee(Request $request)
{
    if (auth()->user()->cannot('isManager', User::class)) {
        abort(404);
    }
    $request->validate([
        'employeeName' => 'required|string|max:255',
        'position' => 'required|string|max:255',
        'status' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);


    $user = User::create([
        'name' => $request->employeeName,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'usertype' => $request->position,
        'last_name' => 'notavailable',
    ]);

  
    $employee = new Employee();
    $employee->employee_name = $request->employeeName;
    $employee->position = $request->position;
    $employee->status = $request->status;
    $employee->user_id = $user->id;

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('employee'), $imageName);
        $employee->image = $imageName;
    }


    $employee->save();

    return redirect()->back()->with('success', 'Employee and user account created successfully.');
}

public function viewEmployee(Request $request)
{
    if (auth()->user()->cannot('isManager', User::class)) {
        abort(404);
    }
    $search = $request->input('search');

    $data = Employee::when($search, function ($query) use ($search) {
        $query->where('employee_name', 'like', '%' . $search . '%');
    })->get();

    return view('manager.show_employee', compact('data'));
}

public function deleteEmployee($id)
{
    if (auth()->user()->cannot('isManager', User::class)) {
        abort(404);
    }
    $data = Employee::findOrFail($id);
    $data->delete();

    return redirect()->back()->with('success', 'Employee deleted successfully.');
}

public function updateEmployee($id)
{
    if (auth()->user()->cannot('isManager', User::class)) {
        abort(404);
    }
    $data = Employee::findOrFail($id);
    return view('manager.update_employee', compact('data'));
}

public function editEmployee(Request $request, $id)
{
    if (auth()->user()->cannot('isManager', User::class)) {
        abort(404);
    }
    $data = Employee::findOrFail($id);
    $data->employee_name = $request->employeeName;
    $data->position = $request->position;
    $data->status = $request->status;

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('employee'), $imageName);
        $data->image = $imageName;
    }

    $data->save();

    return redirect()->route('manager.view_employee')->with('success', 'Employee updated successfully.');
}

public function submitApplication(Request $request)
{
    if (auth()->user()->cannot('isManager', User::class)) {
        abort(404);
    }
    $validatedData = $request->validate([
        'reference_no' => 'string|max:255|unique:borrower_forms,reference_no',
        'borrower_title' => 'required|string',
        'borrower_name' => 'required|string',
        'spouse_title' => 'nullable|string',
        'spouse_name' => 'nullable|string',
        'sex' => 'required|string',
        'date_of_birth' => 'required|date',
        'marital_status' => 'required|string',
        'home_address' => 'required|string',
        'place_of_birth' => 'required|string',
        'educational_attainment' => 'required|string',
        'educational_status' => 'required|string',
        'age' => 'required|integer',
        'school' => 'nullable|string',
        'height' => 'required|integer',
        'weight' => 'required|integer',
        'picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'email' => 'required|email',
        'amount_applied' => 'required|string',
        'purpose' => 'required|string',
        'business_name' => 'nullable|string',
        'business_address' => 'nullable|string',
        'business_contact_number' => 'nullable|numeric',
        'employer_name' => 'nullable|string',
        'position' => 'nullable|string',
        'employer_contact_number' => 'nullable|numeric',
        'reference_name' => 'nullable|string|max:255',
        'reference_relationship' => 'nullable|string|max:255',
        'reference_address' => 'nullable|string|max:255',
    ]);

    if ($request->hasFile('picture')) {
        $picturePath = $request->file('picture')->store('public/pictures');
        $validatedData['picture'] = $picturePath;
    }

    Borrower::create($validatedData);

    return redirect()->route('manager.application.success');
}

public function showPendingApplications()
{
    if (auth()->user()->cannot('isManager', User::class)) {
        abort(404);
    }
    $applications = BorrowerForm::where('status', 'pending')->get();
    return view('manager.pend_req', compact('applications'));
}

public function showApplication($id)
{
    if (auth()->user()->cannot('isManager', User::class)) {
        abort(404);
    }
    $application = BorrowerForm::findOrFail($id);
    return view('manager.app_dets', compact('application'));
}

public function approveApplication(Request $request, $id)
{
    if (auth()->user()->cannot('isManager', User::class)) {
        abort(404);
    }
    $application = BorrowerForm::findOrFail($id);
    $application->status = 'approved';
    $application->save();

    return redirect()->route('manager.pending_requests')->with('success', 'Application approved successfully.');
}

public function rejectApplication(Request $request, $id)
{
    if (auth()->user()->cannot('isManager', User::class)) {
        abort(404);
    }
    $application = BorrowerForm::findOrFail($id);
    $application->status = 'rejected';
    $application->save();

    return redirect()->route('manager.pending_requests')->with('success', 'Application rejected successfully.');
}

public function showApprovedApplications()
{
    if (auth()->user()->cannot('isManager', User::class)) {
        abort(404);
    }
    $applications = BorrowerForm::where('status', 'approved')->get();
    return view('manager.app_req', compact('applications'));
}

    
}
