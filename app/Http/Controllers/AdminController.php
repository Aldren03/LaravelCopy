<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Employee;
use App\Models\BorrowerForm;
use App\Models\Borrower;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;



class AdminController extends Controller
{
    public function index()
    {

        if(Auth::id())
        
            $usertype = Auth()->user()->usertype;


        if ($usertype == 'user')
        {
            return view ('borrower.index');
        }
        elseif ($usertype == 'admin')
        {
            return view ('admin.index');
        }
        else {
            return redirect()->back();
        }
    }

    public function home ()
    {
        return view ('home.index');
    }

    public function add_employee(Request $request)
    {
        $request->validate([
            'employeeName' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        // Create user account
        $user = User::create([
            'name' => $request->employeeName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'usertype' => $request->position,
        ]);
    
        // Create employee and link to user
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
    
        // Save the employee record
        $employee->save();
    
        return redirect()->back()->with('success', 'Employee and user account created successfully.');
    }
    

    public function view_employee(Request $request)
    {
        $search = $request->input('search');
        
        $data = Employee::when($search, function($query) use ($search) {
            $query->where('employee_name', 'like', '%' . $search . '%');
        })->get();
        
        return view('admin.view_employee', compact('data'));
    }
    
    
        public function employee_delete($id)
        {
            $data = Employee::findOrFail($id);
            $data->delete();
    
            return redirect()->back()->with('success', 'Employee deleted successfully.');
        }
    
        public function update_employee($id)
        {
            $data = Employee::findOrFail($id);
            return view('admin.emp_update', compact('data'));
        }
    
        public function edit_employee(Request $request, $id)
        {
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
    
            return redirect()->route('view_employee')->with('success', 'Employee updated successfully.');
        }

        public function view_user()
        {
            $data = User::all();
            return view('admin.view_user', compact('data'));
        }
        public function dashboard()
    {
        $totalEmployees = employee::count();
        $totalUsers = user::count();
        return view('admin.dashboard', compact('totalEmployees', 'totalUsers'));
    }

    public function submitApplication(Request $request)
    {
        // Validate request data
        $validatedData = $request->validate([
            'reference_no' => '|string|max:255|unique:borrower_forms,reference_no',
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

        return redirect()->route('admin.application.success'); 
    }

    

    public function showPendingApplications()
    {
        $applications = BorrowerForm::where('status', 'pending')->get();
        return view('admin.pending_application', compact('applications'));
    }

    public function showApplication($id)
    {
        $application = BorrowerForm::findOrFail($id);
        return view('admin.application_details', compact('application'));
    }

    public function approveApplication(Request $request, $id)
    {
        $application = BorrowerForm::findOrFail($id);
        $application->status = 'approved';
        $application->save();

        return redirect()->route('admin.pending_applications')->with('success', 'Application approved successfully.');
    }

    public function rejectApplication(Request $request, $id)
    {
        $application = BorrowerForm::findOrFail($id);
        $application->status = 'rejected';
        $application->save();

        return redirect()->route('admin.pending_applications')->with('success', 'Application rejected successfully.');
    }

    public function showApprovedApplications()
    {
        $applications = BorrowerForm::where('status', 'approved')->get();
        return view('admin.approved_application_details', compact('applications'));
    }

    public function add_borrower(Request $request)
    {
        $request->validate([
            'borrower_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:255',
            'borrower_address' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);
        $borrower = new Borrower();
        $borrower->borrower_name = $request->borrower_name;
        $borrower->contact_number = $request->contact_number;
        $borrower->borrower_address = $request->borrower_address;
        $borrower->email = $request->email;


        $borrower->save();
        return redirect()->route('add_borrower_form')->with('success', 'Borrower added successfully!');
    }

    public function show_add_borrower_form()
    {
        $data = Borrower::all();
        $applications = BorrowerForm::all();
        $applications = BorrowerForm::where('status', 'approved')->get();
        return view('admin.add_borrower', compact('data', 'applications'));
    }
    public function borrower_delete($id)
    {
        $data = Borrower::findOrFail($id);
        $applications = BorrowerForm::findOrFail($id);
        $data->delete();
        $applications->delete();

        return redirect()->back()->with('success', 'Borrower deleted successfully.');
    }
    public function update_borrower($id)
    {
        $data = Borrower::findOrFail($id);
        return view('admin.borrower_update', compact('data'));
    }

    public function edit_borrower(Request $request, $id)
    {
        $data = Borrower::findOrFail($id);
        $data->borrower_name = $request->borrower_name;
        $data->contact_number = $request->contact_number;
        $data->borrower_address = $request->borrower_address;
        $data->email = $request->email;

        $data->save();

        return redirect()->route('add_borrower')->with('success', 'Borrower updated successfully.');
    }


    public function view_borrower(Request $request)
    {
        $search = $request->input('search');
    
        // Fetch data from Borrower model
        $data = Borrower::when($search, function($query) use ($search) {
            $query->where('borrower_name', 'like', '%' . $search . '%');
        })->get();
    
        // Fetch data from BorrowerForm model
        $applications = BorrowerForm::when($search, function($query) use ($search) {
            $query->where('borrower_name', 'like', '%' . $search . '%');
        })->get();

        return view('admin.add_borrower', compact('data', 'applications'));
    }
    


}
