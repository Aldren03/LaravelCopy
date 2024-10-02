<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BorrowerForm;

class CreditInvestigatorController extends Controller
{
   
    public function submitApplication(Request $request)
    {
        if (auth()->user()->cannot('isCredit', User::class)) {
            abort(404);
        }
        $validatedData = $request->validate([
            'reference_no' => 'required|string|max:255|unique:borrower_forms,reference_no',
            'borrower_title' => 'required|string',
            'borrower_name' => 'required|string',
            
        ]);

        if ($request->hasFile('picture')) {
            $picturePath = $request->file('picture')->store('public/pictures');
            $validatedData['picture'] = $picturePath;
        }

        Borrower::create($validatedData);

        return redirect()->route('credit_investigator.application.success');
    }

  
    public function showPendingApplications()
    {
        if (auth()->user()->cannot('isCredit', User::class)) {
            abort(404);
        }
        $applications = BorrowerForm::where('status', 'pending')->get();
        return view('creditinvestigator.app_pending', compact('applications'));
    }

   
    public function showApplication($id)
    {
        if (auth()->user()->cannot('isCredit', User::class)) {
            abort(404);
        }
        $application = BorrowerForm::findOrFail($id);
        return view('creditinvestigator.appli_details', compact('application'));
    }


    public function approveApplication(Request $request, $id)
    {
        if (auth()->user()->cannot('isCredit', User::class)) {
            abort(404);
        }
        $application = BorrowerForm::findOrFail($id);
        $application->status = 'approved';
        $application->save();

        return redirect()->route('credit_investigator.pending_requests')->with('success', 'Application approved successfully.');
    }

  
    public function rejectApplication(Request $request, $id)
    {
        if (auth()->user()->cannot('isCredit', User::class)) {
            abort(404);
        }
        $application = BorrowerForm::findOrFail($id);
        $application->status = 'rejected';
        $application->save();

        return redirect()->route('credit_investigator.pending_requests')->with('success', 'Application rejected successfully.');
    }

    public function showApprovedApplications()
    {
        if (auth()->user()->cannot('isCredit', User::class)) {
            abort(404);
        }
        $applications = BorrowerForm::where('status', 'approved')->get();
        return view('creditinvestigator.app_request', compact('applications'));
    }
}
