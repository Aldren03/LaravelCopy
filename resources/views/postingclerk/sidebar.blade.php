<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

    body {
        font-family: 'Poppins', sans-serif;
    }

    .sidebar-link:hover,
    .sidebar-link.active {
        background-color: rgba(34, 197, 94, 0.075);
        border-left: 8px solid #4ca771;
        display: block;
    }

    .sidebar-collapsed {
        width: 50px;
    }
    .sidebar-footer p {
        margin-bottom: 0;
    }
</style>

<div class="d-flex">
    <aside id="sidebar" class="sidebar" style="background-color: #C0E6BA;">
        <ul class="py-4 flex-1">
            <li class="mb-2">
                <a href="#" class="sidebar-link d-flex align-items-center p-4 text-black {{ request()->is('dashboard') ? 'active' : '' }}">
                    <i class="lni lni-grid-alt mr-3"></i>
                    <span>Home</span>
                </a>
            </li>
            <li class="sidebar-header d-flex align-items-center p-4 text-black">
                <span>Menu</span>
                <hr class="sidebar-divider flex-grow h-px bg-white ml-4">
            </li>
            <li class="mb-2">
                <a href="{{url('add_new_borrower')}}" class="sidebar-link d-flex align-items-center p-4 text-black {{ request()->is('add_new_borrower') ? 'active' : '' }}">
                    <i class="lni lni-notepad mr-3"></i>
                    <span>Borrower</span>
                </a>
            </li>
            <li class="mb-2">
                <a href="#" class="sidebar-link d-flex align-items-center p-4 text-black {{ request()->is('ledger') || request()->is('ledger') ? 'active' : '' }}" onclick="toggleDropdown(event, 'ledger-dropdown')">
                <i class="lni lni-notepad mr-3"></i>
                    <span>Ledger <i class="lni lni-chevron-down ml-auto"></i></span>
                </a>
                <ul id="ledger-dropdown" class="d-none pl-4">
                    <li class="mb-2">
                        <a href="{{url('ongoing_ledger')}}" class="block p-2 text-black hover:bg-green-200 rounded">Ongoing Ledger</a>
                    </li>
                    <li class="mb-2">
                        <a href="{{url('completed_ledger')}}" class="block p-2 text-black hover:bg-green-200 rounded">Completed Ledger</a>
                    </li>
                </ul>
            </li>
            <li class="mb-2">
                <a href="#" class="sidebar-link d-flex align-items-center p-4 text-black {{ request()->is('loan_plan') || request()->is('loan_type') ? 'active' : '' }}" onclick="toggleDropdown(event, 'loan-dropdown')">
                    <i class="lni lni-credit-cards mr-3"></i>
                    <span>Loan Details <i class="lni lni-chevron-down ml-auto"></i></span>
                </a>
                <ul id="loan-dropdown" class="d-none pl-4">
                    <li class="mb-2">
                        <a href="{{route('loanplan')}}" class="block p-2 text-black hover:bg-green-200 rounded">Loan Plans</a>
                    </li>
                    <li class="mb-2">
                        <a href="{{route('loantype')}}" class="block p-2 text-black hover:bg-green-200 rounded">Loan Type</a>
                    </li>
                </ul>
            </li>
            <li class="mb-2">
    <a href="#" class="sidebar-link d-flex align-items-center p-4 text-black {{ request()->is('application_details') || request()->is('pending_applications') ? 'active' : '' }}"  onclick="toggleDropdown(event, 'application-dropdown')">
        <i class="lni lni-popup mr-3"></i>
        <span>Online Application <i class="lni lni-chevron-down ml-auto"></i></span>
    </a>
    <ul id="application-dropdown" class="d-none pl-4">
    <li class="mb-2">
        <a href="{{ route('posting_clerk.approved_requests') }}" class="block p-2 text-black hover:bg-green-200 rounded">Approved Applications</a>
    </li>
    <li class="mb-2">
        <a href="{{ route('posting_clerk.pending_requests') }}" class="block p-2 text-black hover:bg-green-200 rounded">Pending Requests</a>
    </li>
    </ul>
</li>
            <li class="mb-2">
                <a href="{{route('loan_payments.index')}}" class="sidebar-link d-flex align-items-center p-4 text-black {{ request()->is('loan-payment') ? 'active' : '' }}">
                    <i class="lni lni-layout mr-3"></i>
                    <span>Payment Records</span>
                </a>
            </li>
        </ul>
        <div class="sidebar-footer p-4">
            <hr class="sidebar-divider">
            <p class="text-center text-green-500 italic text-xs">
                TD3 SMART FUND CREDIT CORPORATION
            </p>
            <p class="text-center text-green-500 italic text-xs">
                est. 2015
            </p>
        </div>
    </aside>