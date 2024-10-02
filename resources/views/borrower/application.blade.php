<!DOCTYPE html>
<html>
<head>
    @include('borrower.css')
</head>
<header>
    @include('borrower.header')
</header>
@include('borrower.sidebar')
<div class="container mt-2">
<body class="bg-gray-100 text-gray-800 font-roboto">

    <h2 class="text-2xl text-center text-green-600 my-6">APPLICATION FORM</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('borrower.submitApplication') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <!-- Personal Data -->
        <fieldset class="border border-gray-300 mb-6 p-6 rounded-lg">
            <legend><h3 class="text-center text-green-600 mb-4">Personal Data</h3></legend>
            <table class="w-full border-collapse mb-6">
                <tbody>
                    <tr>
                        <td class="py-2">Borrower's Name:</td>
                        <td class="py-2">
                            <select name="borrower_title" id="borrower_title" class="w-full p-2 border border-gray-300 rounded mb-2" required>
                                <option value="">--Select--</option>
                                <option value="Mr.">Mr.</option>
                                <option value="Ms.">Ms.</option>
                                <option value="Mrs.">Mrs.</option>
                                <option value="Others">Others</option>
                            </select>
                            <input type="text" name="borrower_name" id="borrower_name" class="w-full p-2 border border-gray-300 rounded" required>
                        </td>
                        <td class="py-2">Spouse Name:</td>
                        <td class="py-2">
                            <select name="spouse_title" id="spouse_title" class="w-full p-2 border border-gray-300 rounded mb-2">
                                <option value="">--Select--</option>
                                <option value="Mr.">Mr.</option>
                                <option value="Mrs.">Mrs.</option>
                            </select>
                            <input type="text" name="spouse_name" id="spouse_name" class="w-full p-2 border border-gray-300 rounded">
                        </td>
                    </tr>
                    <tr>
                        <td class="py-2">Sex:</td>
                        <td class="py-2">
                            <select name="sex" id="sex" class="w-full p-2 border border-gray-300 rounded" required>
                                <option value="">--Select--</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Others">Others</option>
                            </select>
                        </td>
                        <td class="py-2">Date of Birth:</td>
                        <td class="py-2">
                            <input type="date" name="date_of_birth" id="date_of_birth" class="w-full p-2 border border-gray-300 rounded" required>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-2">Marital Status:</td>
                        <td class="py-2">
                            <select name="marital_status" id="marital_status" class="w-full p-2 border border-gray-300 rounded" required>
                                <option value="">--Select--</option>
                                <option>Single</option>
                                <option>Married</option>
                                <option>Divorced</option>
                                <option>Widowed</option>
                            </select>
                        </td>
                        <tr>
    <td class="py-2"><label for="municipality">Municipality:</label></td>
    <td class="py-2">
        <select name="municipality" id="municipality" class="w-full p-2 border border-gray-300 rounded">
            <option value="">Select Municipality</option>
            <option value="Anao">Anao</option>
            <option value="Bamban">Bamban</option>
            <option value="Camiling">Camiling</option>
            <option value="Capas">Capas</option>
            <option value="Concepcion">Concepcion</option>
            <option value="Gerona">Gerona</option>
            <option value="La Paz">La Paz</option>
            <option value="Mayantoc">Mayantoc</option>
            <option value="Moncada">Moncada</option>
            <option value="Paniqui">Paniqui</option>
            <option value="Pura">Pura</option>
            <option value="Ramos">Ramos</option>
            <option value="San Clemente">San Clemente</option>
            <option value="San Jose">San Jose</option>
            <option value="San Manuel">San Manuel</option>
            <option value="Santa Ignacia">Santa Ignacia</option>
            <option value="Tarlac City">Tarlac City</option>
            <option value="Victoria">Victoria</option>
        </select>
    </td>
</tr>

<tr id="home-address-row" class="hidden">
    <td class="py-2"><label for="home_address">Barangay:</label></td>
    <td class="py-2">
        <select name="home_address" id="home_address" class="w-full p-2 border border-gray-300 rounded">
            <option value="">Select Barangay</option>
        </select>
    </td>
</tr>

                    <tr>
                        <td class="py-2">Place of Birth:</td>
                        <td class="py-2">
                            <input type="text" name="place_of_birth" id="place_of_birth" class="w-full p-2 border border-gray-300 rounded">
                        </td>
                        <td class="py-2">Educational Attainment:</td>
                        <td class="py-2">
                            <select name="educational_attainment" id="educational_attainment" class="w-full p-2 border border-gray-300 rounded mb-2">
                                <option>--Select--</option>
                                <option>Elementary</option>
                                <option>High School</option>
                                <option>College</option>
                            </select>
                            <select name="educational_status" id="educational_status" class="w-full p-2 border border-gray-300 rounded">
                                <option>--Select--</option>
                                <option>Graduate</option>
                                <option>Undergraduate</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-2">Age:</td>
                        <td class="py-2">
                            <input type="number" name="age" id="age" class="w-full p-2 border border-gray-300 rounded">
                        </td>
                        <td class="py-2">School:</td>
                        <td class="py-2">
                            <input type="text" name="school" id="school" class="w-full p-2 border border-gray-300 rounded">
                        </td>
                    </tr>
                    <tr>
                        <td class="py-2">Height (Centimeter):</td>
                        <td class="py-2">
                            <input type="number" name="height" id="height" min="0" max="500" class="w-full p-2 border border-gray-300 rounded">
                        </td>
                        <td class="py-2">Weight (Kilogram):</td>
                        <td class="py-2">
                            <input type="number" name="weight" id="weight" min="0" max="500" class="w-full p-2 border border-gray-300 rounded">
                        </td>
                    </tr>
                    <tr>
                        <td class="py-2">Upload 2x2 Picture:</td>
                        <td class="py-2">
                            <input type="file" name="picture" id="picture" accept="image/*" class="w-full p-2 border border-gray-300 rounded">
                        </td>
                        <td class="py-2">Email:</td>
                        <td class="py-2">
                            <input type="email" name="email" id="email" class="w-full p-2 border border-gray-300 rounded">
                        </td>
                    </tr>
                </tbody>
            </table>
        </fieldset>

        <!-- Amount Applied -->
        <fieldset class="border border-gray-300 mb-6 p-6 rounded-lg">
            <legend><h3 class="text-center text-green-600 mb-4">Amount Applied</h3></legend>
            <table class="w-full border-collapse mb-6">
                <tbody>
                    <tr>
                        <td class="py-2">Amount Applied:</td>
                        <td class="py-2">
                            <select name="amount_applied" id="amount_applied" class="w-full p-2 border border-gray-300 rounded">
                                <option>--Select--</option>
                                <option>₱10,000</option>
                                <option>₱15,000</option>
                                <option>₱20,000</option>
                                <option>₱25,000</option>
                                <option>₱30,000</option>
                                <option>₱35,000</option>
                                <option>₱40,000</option>
                                <option>₱45,000</option>
                                <option>₱50,000</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-2">Purpose:</td>
                        <td class="py-2">
                            <input type="text" name="purpose" id="purpose" class="w-full p-2 border border-gray-300 rounded">
                        </td>
                    </tr>
                </tbody>
            </table>
        </fieldset>

        <!-- Business Details -->
        <fieldset class="border border-gray-300 mb-6 p-6 rounded-lg">
            <legend><h3 class="text-center text-green-600 mb-4">Business Details</h3></legend>
            <table class="w-full border-collapse mb-6">
                <tbody>
                    <tr>
                        <td class="py-2">Business Name:</td>
                        <td class="py-2">
                            <input type="text" name="business_name" id="business_name" class="w-full p-2 border border-gray-300 rounded">
                        </td>
                        <td class="py-2">Business Address:</td>
                        <td class="py-2">
                            <input type="text" name="business_address" id="business_address" class="w-full p-2 border border-gray-300 rounded">
                        </td>
                    </tr>
                    <tr>
                        <td class="py-2">Business Contact Number:</td>
                        <td class="py-2">
                            <input type="text" name="business_contact_number" id="business_contact_number" class="w-full p-2 border border-gray-300 rounded">
                        </td>
                    </tr>
                </tbody>
            </table>
        </fieldset>

        <!-- Employment Details -->
        <fieldset class="border border-gray-300 mb-6 p-6 rounded-lg">
            <legend><h3 class="text-center text-green-600 mb-4">Employment Details</h3></legend>
            <table class="w-full border-collapse mb-6">
                <tbody>
                    <tr>
                        <td class="py-2">Employer Name:</td>
                        <td class="py-2">
                            <input type="text" name="employer_name" id="employer_name" class="w-full p-2 border border-gray-300 rounded">
                        </td>
                        <td class="py-2">Position:</td>
                        <td class="py-2">
                            <input type="text" name="position" id="position" class="w-full p-2 border border-gray-300 rounded">
                        </td>
                    </tr>
                    <tr>
                        <td class="py-2">Employer Contact Number:</td>
                        <td class="py-2">
                            <input type="text" name="employer_contact_number" id="employer_contact_number" class="w-full p-2 border border-gray-300 rounded">
                        </td>
                    </tr>
                </tbody>
            </table>
        </fieldset>

        <!-- Reference Details -->
        <fieldset class="border border-gray-300 mb-6 p-6 rounded-lg">
            <legend><h3 class="text-center text-green-600 mb-4">Reference Details</h3></legend>
            <table class="w-full border-collapse mb-6">
                <tbody>
                    <tr>
                        <td class="py-2">Name:</td>
                        <td class="py-2">
                            <input type="text" name="reference_name" id="reference_name" class="w-full p-2 border border-gray-300 rounded">
                        </td>
                        <td class="py-2">Relationship:</td>
                        <td class="py-2">
                            <input type="text" name="reference_relationship" id="reference_relationship" class="w-full p-2 border border-gray-300 rounded">
                        </td>
                    </tr>
                    <tr>
                        <td class="py-2">Address: </td>
                        <td class="py-2">
                            <input type="text" name="reference_address" id="reference_address" class="w-full p-2 border border-gray-300 rounded">
                        </td>
                    </tr>
                </tbody>
            </table>
        </fieldset>

        <div class="text-center">
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-full">Submit</button>
        </div>
       
    </form>
</body>
</div>
<script>
    @include('borrower.script')
</script>
</html>
