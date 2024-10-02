<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Employee;
use App\Models\BorrowerForm;
use App\Models\Borrower;
use App\Models\LoanTypes;
use App\Models\LoanPlan;
use App\Models\LoanSchedule;
use App\Models\Ledger;
use App\Models\Payment;
use App\Models\CompletedLedger;
use App\Models\CompletedSchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Hash;




class AdminController extends Controller
{
    public function index()
    {

        if (Auth::check()) { 
            $usertype = Auth::user()->usertype;
        
            switch ($usertype) {
                case 'user':
                    return view('borrower.index');
                case 'admin':
                    return view('admin.index');
                case 'Manager':
                    return view('manager.index');
                case 'Collector':
                    return view('collector.index');
                case 'Posting Clerk':
                    return view('postingclerk.index');
                case 'Credit Investigator':
                    return view('creditinvestigator.index');
                default:
                    return redirect()->back()->with('error', 'Access denied');
            }
        } else {
            return redirect()->route('login');  
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

        public function view_user(Request $request)
{
   
    $search = $request->input('search');

    
    $data = User::where('name', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->paginate(10);

    return view('admin.view_user', compact('data', 'search'));
}
        public function dashboard()
    {
        $totalEmployees = employee::count();
        $totalUsers = user::count();
        $today = Carbon::today();
        $payments = Payment::with(['borrower', 'ledger'])->get();
        $totalPaymentsToday = Payment::whereDate('created_at', $today)->sum('pay_amount');
        $PaymentsCountToday = Payment::whereDate('created_at', $today)->count();
        $borrowers = Borrower::all();
        $ledgers = Ledger::all();
        $totalAmountToday = Ledger::where('status', '2')->whereDate('created_at', $today)->sum('amount');
        $releasedCountToday = Ledger::where('status', '2')->whereDate('created_at', $today)->count();

        return view('admin.dashboard', compact('totalEmployees', 'totalUsers', 'payments', 'borrowers', 'ledgers', 'totalPaymentsToday', 'totalAmountToday', 'releasedCountToday', 'PaymentsCountToday'));
    }

    public function submitApplication(Request $request)
    {

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
    

        $data = Borrower::when($search, function($query) use ($search) {
            $query->where('borrower_name', 'like', '%' . $search . '%');
        })->get();
    

        $applications = BorrowerForm::when($search, function($query) use ($search) {
            $query->where('borrower_name', 'like', '%' . $search . '%');
        })->get();

        return view('admin.add_borrower', compact('data', 'applications'));
    }
    public function loan_type ()
    {
 
        return view('admin.add_loan_type');
    }

    public function add_loan_type(Request $request)
    {
        
        $request->validate([
            'loantype' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        LoanTypes::create([
            'loan_type' => $request->loantype,
            'description' => $request->description,
        ]);

        return redirect()->route('loan_type_form')->with('success', 'Loan type added successfully!');
    }

    public function show_loan_type_form()
    {
        $loanTypes = LoanTypes::all();
        return view('admin.add_loan_type', compact('loanTypes'));
    }
    public function delete_loan_type($id)
    {
        $loanType = LoanTypes::findOrFail($id);
        $loanType->delete();

        return redirect()->route('loan_type_form')->with('success', 'Loan type deleted successfully!');
    }

    public function loanplan()
    {
  
        $loanPlans = LoanPlan::all();
        return view('admin.loan_plan', compact('loanPlans'));
    }


    public function planstore(Request $request)
    {
        $request->validate([
            'loanplan' => 'required|numeric',
            'interest' => 'required|numeric',
            'penalty' => 'required|numeric',
        ]);

        LoanPlan::create([
            'loanplan' => $request->loanplan,
            'interest' => $request->interest,
            'penalty' => $request->penalty,
        ]);

        return redirect()->route('loan_plan')->with('success', 'Loan plan added successfully.');
    }


    public function planedit($id)
    {
        $loanPlan = LoanPlan::findOrFail($id);
        return view('admin.edit_loan_plan', compact('loanPlan'));
    }

    public function planupdate(Request $request, $id)
    {
        $request->validate([
            'loanplan' => 'required|decimal',
            'interest' => 'required|numeric|between:0,99.99',
            'penalty' => 'required|numeric|between:0,99.99',
        ]);

        $loanPlan = LoanPlan::findOrFail($id);
        $loanPlan->update([
            'loanplan' => $request->loan_plan,
            'interest' => $request->interest,
            'penalty' => $request->penalty,
        ]);

        return redirect()->route('loan_plan')->with('success', 'Loan plan updated successfully.');
    }

    public function plandestroy($id)
    {
        $loanPlan = LoanPlan::findOrFail($id);
        $loanPlan->delete();

        return redirect()->route('loan_plan')->with('success', 'Loan plan deleted successfully.');
    }

    public function listAllLedgers(Request $request)
    {

    
    $borrowers = Borrower::all();
    $borrowerForms = BorrowerForm::where('status', 'approved')->select('id', 'borrower_name')->get();
    $loanTypes = LoanTypes::all();
    $loanPlans = LoanPlan::all();
    
        $search = $request->input('search', '');
        $selectedDate = $request->input('date', now()->toDateString()); 
    
        $ledgers = Ledger::with(['borrower', 'loanType', 'loanPlan', 'loanSchedule', 'borrowerForms'])
            ->when($search, function ($query, $search) {
                $query->whereHas('borrower', function ($q) use ($search) {
                    $q->where('borrower_name', 'like', '%' . $search . '%');
                })->orWhere('ref_no', 'like', '%' . $search . '%');
            })
            ->when($selectedDate, function ($query) use ($selectedDate) {
                $query->whereHas('loanSchedule', function ($q) use ($selectedDate) {
                    $q->whereDate('date', $selectedDate);
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10); 
    
        $loanSchedules = [];
    
        foreach ($ledgers as $ledger) {
            $loanSchedules[$ledger->id] = LoanSchedule::where('ledger_id', $ledger->id)->get();
    
            $monthlyInterest = $ledger->amount * ($ledger->loanPlan->interest / 100);
            $totalInterest = $monthlyInterest * $ledger->loanPlan->loanplan;
            $ledger->totalAmount = $ledger->amount + $totalInterest;
            $ledger->monthly = $ledger->totalAmount / $ledger->loanPlan->loanplan;
            $ledger->penalty = $ledger->monthly * ($ledger->loanPlan->penalty / 100);
            $totalWorkingDays = $ledger->loanPlan->loanplan * 22;
            $ledger->daily = $ledger->totalAmount / $totalWorkingDays;
        }
    
        return view('admin.ledger', compact('borrowers', 'borrowerForms', 'loanTypes', 'loanPlans', 'ledgers', 'loanSchedules', 'search', 'selectedDate'));
    }


    public function showLedgerDetails($id)
    {
        $ledger = Ledger::findOrFail($id);
        return view('admin.ledger', compact('ledger'));
    }

    public function createLoanForm()
    {
        $borrowers = Borrower::all();
        $loanTypes = LoanTypes::all(); 
        $loanPlans = LoanPlan::all();

        return view('admin.create_loan', compact('borrowers', 'loanTypes', 'loanPlans'));
    }

    public function storeLoan(Request $request)
{
    $validated = $request->validate([
        'borrower_id' => 'nullable|exists:borrowers,id',
        'loan_type_id' => 'required|exists:loan_types,id',
        'loan_plan_id' => 'required|exists:loan_plans,id',
        'amount' => 'required|numeric|min:0',
        'purpose' => 'required|string',
        'borrower_form_id' => 'nullable|exists:borrower_forms,id'
    ]);

    $loanPlan = LoanPlan::find($validated['loan_plan_id']);
    
    if (!$loanPlan) {
        return redirect()->back()->with('error', 'Loan plan not found.');
    }

    $loanDuration = $loanPlan->loanplan; 
    $interestRate = $loanPlan->interest / 100;
    $penaltyRate = $loanPlan->penalty / 100;

    
    $monthlyInterest = $validated['amount'] * $interestRate;
    $totalInterest = $monthlyInterest * $loanDuration;
    $totalAmount = $validated['amount'] + $totalInterest; 
    $monthlyPayment = $totalAmount / $loanDuration;
    $penaltyAmount = $monthlyPayment * $penaltyRate;
    $totalWorkingDays = $loanDuration * 22; 
    $dailyPayment = $totalAmount / $totalWorkingDays;

    
    $ledger = Ledger::create([
        'ref_no' => strtoupper(uniqid('Ledger_')),
        'borrower_id' => $validated['borrower_id'],
        'loan_type_id' => $validated['loan_type_id'],
        'loan_plan_id' => $validated['loan_plan_id'],
        'borrower_form_id' => $validated['borrower_form_id'],
        'amount' => $validated['amount'],
        'payable_amount' => $totalAmount, 
        'monthly' => $monthlyPayment,
        'daily' => $dailyPayment,
        'penalty' => $penaltyAmount,
        'purpose' => $validated['purpose'],
        'status' => 'pending',
    ]);

    $this->generatePaymentSchedule($ledger->id, $dailyPayment, $totalAmount, $loanDuration);

    return redirect()->route('loans.index')->with('success', 'Ledger created successfully.');
}

    protected function generatePaymentSchedule($loanId, $dailyPayment, $totalAmount, $loanDuration)
    {
        $dueDate = now()->addMonths($loanDuration);
        $endDate = Carbon::parse($dueDate);
        $startDate = $endDate->copy()->subMonths($loanDuration);
        $start = Carbon::parse($startDate);
        $totalPaid = 0;
    
        while ($start->lte($endDate)) {
            if ($start->isWeekday() && $totalPaid < $totalAmount) {
                $paymentAmount = min($dailyPayment, $totalAmount - $totalPaid);
                LoanSchedule::create([
                    'ledger_id' => $loanId,
                    'date' => $start->format('Y-m-d'),
                ]);
                $totalPaid += $paymentAmount;
            }
            $start->addDay();
        }
    }
    

  
    public function editLedger($id)
    {
        $ledger = Ledger::findOrFail($id);
        return view('admin.edit_loan', compact('ledger'));
    }

    public function ledgerupdate(Request $request, $id)
    {
    $request->validate([

    ]);
    $ledger = Ledger::findOrFail($id);
    $ledger->fill($request->except(['status'])); 

    if ($request->has('status')) {
        $newStatus = $request->status;

        $ledger->status = $newStatus;
        if ($newStatus == 2) { 
            $ledger->date_released = Carbon::now()->format('Y-m-d');
        }
    }
    $ledger->save();

    return redirect()->route('loans.index')->with('success', 'Loan updated successfully.');
    }


    public function deleteLedger($id)
{
    $ledger = Ledger::findOrFail($id);
    $ledger->delete();

    return redirect()->route('loans.index')->with('success', 'Ledger deleted successfully.');
}

    public function paymentIndex(Request $request)
{
    $selectedDate = $request->input('date', now()->toDateString()); 

    $payments = Payment::with(['borrower', 'ledger'])
        ->whereDate('created_at', $selectedDate)
        ->paginate(10);

    $borrowers = Borrower::all();
    $ledgers = Ledger::all();

    return view('admin.payment', compact('payments', 'borrowers', 'ledgers', 'selectedDate'));
}

public function paymentStore(Request $request)
{

    $validated = $request->validate([
        'ledger_id' => 'required|exists:ledger,id',
        'borrower_id' => 'required|exists:borrowers,id',
        'payment_date' => 'required|date',
        'pay_amount' => 'required|numeric|min:0',
        'penalty' => 'nullable|numeric|min:0',
    ]);

    $ledger = Ledger::find($validated['ledger_id']);
    if (!$ledger) {
        return redirect()->back()->with('error', 'Invalid ledger selected.');
    }


    $payment = Payment::create([
        'ref_no' => strtoupper(uniqid('LEDGERS_')),
        'ledger_id' => $validated['ledger_id'],
        'borrower_id' => $validated['borrower_id'],
        'payment_date' => $validated['payment_date'],
        'pay_amount' => $validated['pay_amount'],
        'penalty' => $validated['penalty'] ?? 0,
    ]);

    $ledger->payable_amount-= $validated['pay_amount'];
    $ledger->save();

    LoanSchedule::where('ledger_id', $validated['ledger_id'])
        ->where('date', $validated['payment_date'])
        ->update(['status' => 'paid']);

    return redirect()->route('payments.index')->with('success', 'Payment added successfully.');
}


public function getLoanDetails($id)
{
    $ledger = Ledger::with('borrower', 'loanPlan', 'LoanSchedule')->find($id);

    if ($ledger) {
        $monthlyInterest = $ledger->amount * ($ledger->loanPlan->interest / 100);
        $totalInterest = $monthlyInterest * $ledger->loanPlan->loanplan;
        $ledger->totalAmount = $ledger->amount + $totalInterest;
        $ledger->monthly = $ledger->totalAmount / $ledger->loanPlan->loanplan;
        $ledger->penalty = $ledger->monthly * ($ledger->loanPlan->penalty / 100);
        $totalWorkingDays = $ledger->loanPlan->loanplan * 22;
        $ledger->daily = $ledger->totalAmount / $totalWorkingDays;

        return response()->json([
            'borrower_id' => $ledger->borrower->id,
            'borrower_name' => $ledger->borrower->borrower_name,
            'amount' => number_format($ledger->amount, 2),
            'total_amount' => number_format($ledger->totalAmount, 2),
            'monthly_payment' => number_format($ledger->monthly, 2),
            'penalty' => number_format($ledger->penalty, 2),
            'daily_payment' => number_format($ledger->daily, 2),
        ]);
    }

    return response()->json([]);
}

public function completed()
{
    $loanTypes = LoanTypes::all();
    $loanPlans = LoanPlan::all();

    $completedLedgers = CompletedLedger::with(['loanType', 'loanPlan'])
    ->orderBy('created_at', 'desc')
    ->paginate(10); 

    $completedSchedules = [];

    foreach ($completedLedgers as $ledger) {
        $completedSchedules[$ledger->id] = CompletedSchedule::where('completed_ledger_id', $ledger->id)->get();
        $monthlyInterest = $ledger->amount * ($ledger->loanPlan->interest / 100); 
        $totalInterest = $monthlyInterest * $ledger->loanPlan->loanplan; 
        $ledger->totalAmount = $ledger->amount + $totalInterest;
        $ledger->monthly = $ledger->totalAmount / $ledger->loanPlan->loanplan;
        $ledger->penalty = $ledger->monthly * ($ledger->loanPlan->penalty / 100); 
        $totalWorkingDays = $ledger->loanPlan->loanplan * 22; 
        $ledger->daily = $ledger->totalAmount / $totalWorkingDays; 
    }

    return view('admin.completed_ledger', compact('completedLedgers', 'loanTypes', 'loanPlans', 'completedSchedules'));
}



public function addCompletedLedger(Request $request)
{

    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'loan_type_id' => 'required|exists:loan_types,id',
        'loan_plan_id' => 'required|exists:loan_plans,id',
        'amount' => 'required|numeric|min:0',
        'contact_number' => 'required|string|max:15',
        'municipality' => 'required|string',
        'home_address' => 'required|string',
        'start_date' => 'required|date',
    ]);
    $loanPlan = LoanPlan::find($validated['loan_plan_id']);

    if (!$loanPlan) {
        return redirect()->back()->with('error', 'Loan plan not found.');
    }

    $loanDuration = $loanPlan->loanplan; 
    $interestRate = $loanPlan->interest / 100;
    $penaltyRate = $loanPlan->penalty / 100;

    
    $monthlyInterest = $validated['amount'] * $interestRate;
    $totalInterest = $monthlyInterest * $loanDuration;
    $totalAmount = $validated['amount'] + $totalInterest; 
    $monthlyPayment = $totalAmount / $loanDuration;
    $penaltyAmount = $monthlyPayment * $penaltyRate;
    $totalWorkingDays = $loanDuration * 22; 
    $dailyPayment = $totalAmount / $totalWorkingDays;

    $completedLedger = CompletedLedger::create([
        'first_name' => $validated['first_name'],
        'last_name' => $validated['last_name'],
        'loan_type_id' => $validated['loan_type_id'],
        'loan_plan_id' => $validated['loan_plan_id'],
        'amount' => $validated['amount'],
        'total_payable_amount' => $totalAmount,
        'contact_number' => $validated['contact_number'],
        'municipality' => $validated['municipality'],
        'home_address' => $validated['home_address'],
        'start_date' => $validated['start_date'],
        'reference_number' => strtoupper(uniqid('CMPLTLGDRS_')),
    ]);

    $this->generateCompletedPaymentSchedule($completedLedger->id, $dailyPayment, $totalAmount, $loanDuration, $validated['start_date']);

    return redirect()->route('completed-ledger.show')->with('success', 'Completed Ledger created successfully.');
}

protected function generateCompletedPaymentSchedule($ledgerId, $dailyPayment, $totalAmount, $loanDuration, $startDate)
{
    
    $endDate = Carbon::parse($startDate)->addMonths($loanDuration);
    
    $start = Carbon::parse($startDate);

    $totalPaid = 0; 

    while ($start->lte($endDate) && $totalPaid < $totalAmount) {

       
        if ($start->isWeekday()) {

          
            $paymentAmount = min($dailyPayment, $totalAmount - $totalPaid);

            
            CompletedSchedule::create([
                'completed_ledger_id' => $ledgerId,
                'date' => $start->format('Y-m-d'),  
            ]);

          
            $totalPaid += $paymentAmount;
        }

        $start->addDay();
    }
}




}
