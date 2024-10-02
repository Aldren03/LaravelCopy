<!DOCTYPE html>
<html>
<head>
    @include('admin.css') 
</head>
<header>

</header>

<body>

    <div class="container mt-2">
        <h2 class="text-2xl text-center text-green-600 my-6">Application Details</h2>
        
        <form class="bg-white p-6 border border-gray-300 rounded-lg shadow-md">
            @csrf
            @method('PUT')


            <fieldset disabled class="mb-6">
                <legend class="text-lg font-semibold mb-4">Personal Data</legend>
                <div class="grid grid-cols-2 gap-6 mb-4">
                    <div>
                        <label for="name" class="block text-gray-700">Name</label>
                        <input type="text" id="name" name="name" value="{{ $application->borrower_title }} {{ $application->borrower_name }}" class="form-input mt-1 block w-full" />
                    </div>
                    <div>
                        <label for="spouse_name" class="block text-gray-700">Spouse Name</label>
                        <input type="text" id="spouse_name" name="spouse_name" value="{{ $application->spouse_title }} {{ $application->spouse_name }}" class="form-input mt-1 block w-full" />
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-6 mb-4">
                    <div>
                        <label for="sex" class="block text-gray-700">Sex</label>
                        <input type="text" id="sex" name="sex" value="{{ $application->sex }}" class="form-input mt-1 block w-full" />
                    </div>
                    <div>
                        <label for="date_of_birth" class="block text-gray-700">Date of Birth</label>
                        <input type="text" id="date_of_birth" name="date_of_birth" value="{{ $application->date_of_birth}}" class="form-input mt-1 block w-full" />
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-6 mb-4">
                    <div>
                        <label for="marital_status" class="block text-gray-700">Marital Status</label>
                        <input type="text" id="marital_status" name="marital_status" value="{{ $application->marital_status }}" class="form-input mt-1 block w-full" />
                    </div>
                    <div>
                        <label for="home_address" class="block text-gray-700">Home Address</label>
                        <input type="text" id="home_address" name="home_address" value="Brgy. {{ $application->home_address }}, {{ $application->municipality }}, Tarlac, Philippines "  class="form-input mt-1 block w-full" />
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-6 mb-4">
                    <div>
                        <label for="place_of_birth" class="block text-gray-700">Place of Birth</label>
                        <input type="text" id="place_of_birth" name="place_of_birth" value="{{ $application->place_of_birth }}" class="form-input mt-1 block w-full" />
                    </div>
                    <div>
                        <label for="educational_attainment" class="block text-gray-700">Educational Attainment</label>
                        <input type="text" id="educational_attainment" name="educational_attainment" value="{{ $application->educational_attainment }}" class="form-input mt-1 block w-full" />
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-6 mb-4">
                    <div>
                        <label for="educational_status" class="block text-gray-700">Educational Status</label>
                        <input type="text" id="educational_status" name="educational_status" value="{{ $application->educational_status }}" class="form-input mt-1 block w-full" />
                    </div>
                    <div>
                        <label for="age" class="block text-gray-700">Age</label>
                        <input type="text" id="age" name="age" value="{{ $application->age }}" class="form-input mt-1 block w-full" />
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-6 mb-4">
                    <div>
                        <label for="school" class="block text-gray-700">School</label>
                        <input type="text" id="school" name="school" value="{{ $application->school }}" class="form-input mt-1 block w-full" />
                    </div>
                    <div>
                        <label for="height" class="block text-gray-700">Height (cm)</label>
                        <input type="text" id="height" name="height" value="{{ $application->height }}" class="form-input mt-1 block w-full" />
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-6 mb-4">
                    <div>
                        <label for="weight" class="block text-gray-700">Weight (kg)</label>
                        <input type="text" id="weight" name="weight" value="{{ $application->weight }}" class="form-input mt-1 block w-full" />
                    </div>
                    <div>
                        <label for="picture" class="block text-gray-700">Picture</label>
                        <img src="{{ asset('storage/' . $application->picture) }}" alt="Borrower Picture" width="100" />
                    </div>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email</label> 
                    <input type="email" id="email" name="email" value="{{ $application->email }}" class="form-input mt-1 block w-full" readonly />
                </div>
            </fieldset>
   
            <fieldset disabled class="mb-6">
                <legend class="text-lg font-semibold mb-4">Amount Applied</legend>
                <div class="mb-4">
                    <label for="amount_applied" class="block text-gray-700">Amount Applied</label>
                    <input type="text" id="amount_applied" name="amount_applied" value="{{ $application->amount_applied }}" class="form-input mt-1 block w-full" />
                </div>
                <div class="mb-4">
                    <label for="purpose" class="block text-gray-700">Purpose</label>
                    <input type="text" id="purpose" name="purpose" value="{{ $application->purpose }}" class="form-input mt-1 block w-full" />
                </div>
            </fieldset>

  
            <legend class="text-xl font-bold mb-4">Source of Income</legend>
            <fieldset disabled class="mb-6">
                <legend class="text-lg font-semibold mb-4">If Self-Employed</legend>
                <div class="grid grid-cols-2 gap-6 mb-4">
                    <div>
                        <label for="business_name" class="block text-gray-700">Business Name</label>
                        <input type="text" id="business_name" name="business_name" value="{{ $application->business_name }}" class="form-input mt-1 block w-full" />
                    </div>
                    <div>
                        <label for="business_address" class="block text-gray-700">Business Address</label>
                        <input type="text" id="business_address" name="business_address" value="{{ $application->business_address }}" class="form-input mt-1 block w-full" />
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-6 mb-4">
                    <div>
                        <label for="business_contact_number" class="block text-gray-700">Business Contact Number</label>
                        <input type="text" id="business_contact_number" name="business_contact_number" value="{{ $application->business_contact_number }}" class="form-input mt-1 block w-full" />
                    </div>
                
                </div>
                <legend class="text-lg font-semibold mb-4">If Employed</legend>
                <div class="grid grid-cols-2 gap-6 mb-4">   
                <div>
                        <label for="employer_name" class="block text-gray-700">Employer Name</label>
                        <input type="text" id="employer_name" name="employer_name" value="{{ $application->employer_name }}" class="form-input mt-1 block w-full" />
                    </div>
                    <div>
                        <label for="position" class="block text-gray-700">Position</label>
                        <input type="text" id="position" name="position" value="{{ $application->position }}" class="form-input mt-1 block w-full" />
                    </div>
                    <div>
                        <label for="employer_contact_number" class="block text-gray-700">Employer Contact Number</label>
                        <input type="text" id="employer_contact_number" name="employer_contact_number" value="{{ $application->employer_contact_number }}" class="form-input mt-1 block w-full" />
                    </div>
                </div>
            </fieldset>

       
            <fieldset disabled class="mb-6">
                <legend class="text-lg font-semibold mb-4">Personal/ Credit Reference</legend>
                <div class="grid grid-cols-2 gap-6 mb-4">
                    <div>
                        <label for="reference_name" class="block text-gray-700">Name</label>
                        <input type="text" id="reference_name" name="reference_name" value="{{ $application->reference_name }}" class="form-input mt-1 block w-full" />
                    </div>
                    <div>
                        <label for="reference_relationship" class="block text-gray-700">Relationship</label>
                        <input type="text" id="reference_relationship" name="reference_relationship" value="{{ $application->reference_relationship }}" class="form-input mt-1 block w-full" />
                    </div>
                </div>
                <div class="mb-4">
                    <label for="reference_address" class="block text-gray-700">Address</label>
                    <input type="text" id="reference_address" name="reference_address" value="{{ $application->reference_address }}" class="form-input mt-1 block w-full" />
                </div>
                
            </fieldset>
            <div class="flex justify-between mt-4">
                <a href="{{ route('admin.pending_applications') }}" class="btn btn-secondary">Back to List</a>
        
            </div>
        </form>
    </div>

    @include('admin.script') 
</body>
</html>
