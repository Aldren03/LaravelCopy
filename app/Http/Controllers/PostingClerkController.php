<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\BorrowerForm;
use App\Models\Borrower;
use App\Models\LoanTypes;
use App\Models\LoanPlan;
use App\Models\LoanSchedule;
use App\Models\Ledger;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PostingClerkController extends Controller
{
    public function add_new_borrower(Request $request)
{
    if (auth()->user()->cannot('isPosting', User::class)) {
        abort(404);
    }
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

    return redirect()->route('new_borrower_form')->with('success', 'Borrower added successfully!');
}

public function show_new_borrower_form()
{
    if (auth()->user()->cannot('isPosting', User::class)) {
        abort(404);
    }
    $data = Borrower::all();
    $applications = BorrowerForm::where('status', 'approved')->get();
    return view('postingclerk.add_new_borrower', compact('data', 'applications'));
}

public function remove_borrower($id)
{
    $data = Borrower::findOrFail($id);
    $applications = BorrowerForm::findOrFail($id);
    $data->delete();
    $applications->delete();

    return redirect()->back()->with('success', 'Borrower removed successfully.');
}

public function edit_borrower_info($id)
{
    if (auth()->user()->cannot('isPosting', User::class)) {
        abort(404);
    }
    $data = Borrower::findOrFail($id);
    return view('postingclerk.borrower_update', compact('data'));
}

public function update_borrower_info(Request $request, $id)
{
    if (auth()->user()->cannot('isPosting', User::class)) {
        abort(404);
    }
    $data = Borrower::findOrFail($id);
    $data->borrower_name = $request->borrower_name;
    $data->contact_number = $request->contact_number;
    $data->borrower_address = $request->borrower_address;
    $data->email = $request->email;

    $data->save();

    return redirect()->route('borrowers.view_all')->with('success', 'Borrower information updated successfully.');
}

public function view_all_borrowers(Request $request)
{
    if (auth()->user()->cannot('isPosting', User::class)) {
        abort(404);
    }
    $search = $request->input('search');

    $data = Borrower::when($search, function($query) use ($search) {
        $query->where('borrower_name', 'like', '%' . $search . '%');
    })->get();

    $applications = BorrowerForm::when($search, function($query) use ($search) {
        $query->where('borrower_name', 'like', '%' . $search . '%');
    })->get();

    return view('postingclerk.add_new_borrower', compact('data', 'applications'));
}
public function listAllOngoingLedgers(Request $request)
{
    if (auth()->user()->cannot('isPosting', User::class)) {
        abort(404);
    }
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
        ->get();

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

    return view('postingclerk.ongoing_ledger', compact('borrowers', 'borrowerForms', 'loanTypes', 'loanPlans', 'ledgers', 'loanSchedules', 'search', 'selectedDate'));
}

public function showOngoingLedgerDetails($id)
{
    if (auth()->user()->cannot('isPosting', User::class)) {
        abort(404);
    }
    $ledger = Ledger::findOrFail($id);
    return view('postingclerk.ongoing_ledger', compact('ledger'));
}

public function createLoanForm()
{
    if (auth()->user()->cannot('isPosting', User::class)) {
        abort(404);
    }
    $borrowers = Borrower::all();
    $loanTypes = LoanTypes::all(); 
    $loanPlans = LoanPlan::all();

    return view('postingclerk.create_loan', compact('borrowers', 'loanTypes', 'loanPlans'));
}

public function storeOngoingLoan(Request $request)
{
    if (auth()->user()->cannot('isPosting', User::class)) {
        abort(404);
    }
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

    return redirect()->route('ongoing_ledger.index')->with('success', 'Ongoing ledger created successfully.');
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
public function editOngoingLedger($id)
{
    if (auth()->user()->cannot('isPosting', User::class)) {
        abort(404);
    }
    $ledger = Ledger::findOrFail($id);
    return view('postingclerk.edit_loan', compact('ledger'));
}

public function updateOngoingLedger(Request $request, $id)
{
    if (auth()->user()->cannot('isPosting', User::class)) {
        abort(404);
    }
    $request->validate([
        'borrower_id' => 'nullable|exists:borrowers,id',
        'loan_type_id' => 'required|exists:loan_types,id',
        'loan_plan_id' => 'required|exists:loan_plans,id',
        'amount' => 'required|numeric|min:0',
        'purpose' => 'required|string',
        'status' => 'required|string',
    ]);

    $ledger = Ledger::findOrFail($id);

    $loanPlan = LoanPlan::find($request->input('loan_plan_id'));
    if (!$loanPlan) {
        return redirect()->back()->with('error', 'Loan plan not found.');
    }

    $loanDuration = $loanPlan->loanplan; 
    $interestRate = $loanPlan->interest / 100;
    $penaltyRate = $loanPlan->penalty / 100;

    $monthlyInterest = $request->input('amount') * $interestRate;
    $totalInterest = $monthlyInterest * $loanDuration;
    $totalAmount = $request->input('amount') + $totalInterest; 
    $monthlyPayment = $totalAmount / $loanDuration;
    $penaltyAmount = $monthlyPayment * $penaltyRate;
    $totalWorkingDays = $loanDuration * 22; 
    $dailyPayment = $totalAmount / $totalWorkingDays;

    $ledger->update([
        'borrower_id' => $request->input('borrower_id'),
        'loan_type_id' => $request->input('loan_type_id'),
        'loan_plan_id' => $request->input('loan_plan_id'),
        'amount' => $request->input('amount'),
        'payable_amount' => $totalAmount, 
        'monthly' => $monthlyPayment,
        'daily' => $dailyPayment,
        'penalty' => $penaltyAmount,
        'purpose' => $request->input('purpose'),
        'status' => $request->input('status'),
    ]);

    if ($request->input('status') == 'released') {
        $ledger->update(['date_released' => Carbon::now()->format('Y-m-d')]);
    }

    return redirect()->route('ongoing_ledger.index')->with('success', 'Ongoing ledger updated successfully.');
}


public function deleteOngoingLedger($id)
{
    if (auth()->user()->cannot('isPosting', User::class)) {
        abort(404);
    }
    $ledger = Ledger::findOrFail($id);
    $ledger->delete();

    return redirect()->route('ongoing_ledger.index')->with('success', 'Ongoing ledger deleted successfully.');
}

public function loantype ()
    {
        if (auth()->user()->cannot('isPosting', User::class)) {
            abort(404);
        }
        return view('postingclerk.loantype');
    }

public function add_loantype(Request $request)
    {
        if (auth()->user()->cannot('isPosting', User::class)) {
            abort(404);
        }
        $request->validate([
            'loantype' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        LoanTypes::create([
            'loan_type' => $request->loantype,
            'description' => $request->description,
        ]);

        return redirect()->route('loantype')->with('success', 'Loan type added successfully!');
    }

    public function show_loantype_form()
    {
        if (auth()->user()->cannot('isPosting', User::class)) {
            abort(404);
        }
        $loanTypes = LoanTypes::all();
        return view('postingclerk.loantype', compact('loanTypes'));
    }
    public function delete_loantype($id)
    {
        if (auth()->user()->cannot('isPosting', User::class)) {
            abort(404);
        }
        $loanType = LoanTypes::findOrFail($id);
        $loanType->delete();
    
        return redirect()->route('loantype')->with('success', 'Loan type deleted successfully!');
    }
    public function loan_plan()
    {
        if (auth()->user()->cannot('isPosting', User::class)) {
            abort(404);
        }
        $loanPlans = LoanPlan::all();
        return view('postingclerk.loanplan', compact('loanPlans'));
    }


    public function loanplan_store(Request $request)
    {
        if (auth()->user()->cannot('isPosting', User::class)) {
            abort(404);
        }
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

        return redirect()->route('loanplan')->with('success', 'Loan plan added successfully.');
    }


    public function loanplan_edit($id)
    {
        if (auth()->user()->cannot('isPosting', User::class)) {
            abort(404);
        }
        $loanPlan = LoanPlan::findOrFail($id);
        return view('postingclerk.loanplanedit', compact('loanPlan'));
    }

    public function loanplan_update(Request $request, $id)
    {
        if (auth()->user()->cannot('isPosting', User::class)) {
            abort(404);
        }
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

        return redirect()->route('loanplan')->with('success', 'Loan plan updated successfully.');
    }

    public function loanplan_destroy($id)
    {
        if (auth()->user()->cannot('isPosting', User::class)) {
            abort(404);
        }
        $loanPlan = LoanPlan::findOrFail($id);
        $loanPlan->delete();

        return redirect()->route('loanplan')->with('success', 'Loan plan deleted successfully.');
    }
    
    public function loanPaymentIndex(Request $request)
{
    if (auth()->user()->cannot('isPosting', User::class)) {
        abort(404);
    }
    $selectedDate = $request->input('date', now()->toDateString()); 

    $payments = Payment::with(['borrower', 'ledger'])
        ->whereDate('created_at', $selectedDate)
        ->get();

    $borrowers = Borrower::all();
    $ledgers = Ledger::all();

    return view('postingclerk.loan_payment', compact('payments', 'borrowers', 'ledgers', 'selectedDate'));
}

public function loanPaymentStore(Request $request)
{
    if (auth()->user()->cannot('isPosting', User::class)) {
        abort(404);
    }
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

    $ledger->payable_amount -= $validated['pay_amount'];
    $ledger->save();

    LoanSchedule::where('ledger_id', $validated['ledger_id'])
        ->where('date', $validated['payment_date'])
        ->update(['status' => 'paid']);

    return redirect()->route('loan_payments.index')->with('success', 'Payment added successfully.');
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

public function getLoanScheduleDates($ledger_id)
{
   
    $dates = LoanSchedule::where('ledger_id', $ledger_id)->pluck('date');
    return response()->json($dates);
}

public function submitApplication(Request $request)
{
    if (auth()->user()->cannot('isPosting', User::class)) {
        abort(404);
    }
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

    return redirect()->route('posting_clerk.application.success');
}

public function showPendingApplications()
{
    if (auth()->user()->cannot('isPosting', User::class)) {
        abort(404);
    }
    $applications = BorrowerForm::where('status', 'pending')->get();
    return view('postingclerk.pending_request', compact('applications'));
}

public function showApplication($id)
{
    if (auth()->user()->cannot('isPosting', User::class)) {
        abort(404);
    }
    $application = BorrowerForm::findOrFail($id);
    return view('postingclerk.apps_details', compact('application'));
}

public function approveApplication(Request $request, $id)
{
    if (auth()->user()->cannot('isPosting', User::class)) {
        abort(404);
    }
    $application = BorrowerForm::findOrFail($id);
    $application->status = 'approved';
    $application->save();

    return redirect()->route('posting_clerk.pending_requests')->with('success', 'Application approved successfully.');
}

public function rejectApplication(Request $request, $id)
{
    if (auth()->user()->cannot('isPosting', User::class)) {
        abort(404);
    }
    $application = BorrowerForm::findOrFail($id);
    $application->status = 'rejected';
    $application->save();

    return redirect()->route('posting_clerk.pending_requests')->with('success', 'Application rejected successfully.');
}

public function showApprovedApplications()
{
    if (auth()->user()->cannot('isPosting', User::class)) {
        abort(404);
    }
    $applications = BorrowerForm::where('status', 'approved')->get();
    return view('postingclerk.approved_request', compact('applications'));
}



}


