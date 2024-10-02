<?php
    namespace App\Http\Controllers;

    use App\Models\BorrowerForm;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Log; 
    
    class BorrowerController extends Controller
    {
        public function index ()
        {
            if (auth()->user()->cannot('isBorrower', User::class)) {
                abort(404);
            }
            return view ('borrower.index');
        }

        public function showForm()
        {
            if (auth()->user()->cannot('isBorrower', User::class)) {
                abort(404);
            }
            return view('borrower.application'); 
        }

        public function submitApplication(Request $request)
    {
        if (auth()->user()->cannot('isBorrower', User::class)) {
            abort(404);
        }
        $validatedData = $request->validate([
            'borrower_title' => 'required|string|max:255',
            'borrower_name' => 'required|string|max:255',
            'spouse_title' => 'nullable|string|max:255',
            'spouse_name' => 'nullable|string|max:255',
            'sex' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'marital_status' => 'required|string|max:255',
            'home_address' => 'required|string|max:255',
            'municipality' => 'required|string|max:255',
            'place_of_birth' => 'required|string|max:255',
            'educational_attainment' => 'required|string|max:255',
            'educational_status' => 'required|string|max:255',
            'age' => 'required|integer|min:0|max:150',
            'school' => 'nullable|string|max:255',
            'height' => 'required|integer|min:0',
            'weight' => 'required|integer|min:0',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'email' => 'required|email|max:255',
            'amount_applied' => 'max:255|string',
            'purpose' => 'required|string|max:255',
            'business_name' => 'nullable|string|max:255',
            'business_address' => 'nullable|string|max:255',
            'business_contact_number' => 'nullable|string|max:255',
            'employer_name' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'employer_contact_number' => 'nullable|string|max:255',
            'reference_name' => 'nullable|string|max:255',
            'reference_relationship' => 'nullable|string|max:255',
            'reference_address' => 'nullable|string|max:255',
            'reference_no' => '|string|max:255|unique:borrower_forms,reference_no',
            'status' => 'nullable|string|in:pending,approved,rejected',
        ]);

        $validatedData['reference_no'] = uniqid('TD3-OA-');

        BorrowerForm::create($validatedData);


        return redirect()->back()->with('success', 'Application submitted successfully!');
    }
    
    }