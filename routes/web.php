<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BorrowerController;
use App\Http\Controllers\CollectorController;
use App\Http\Controllers\PostingClerkController;
use App\Http\Controllers\CreditInvestigatorController;
use App\Http\Controllers\ManagerController;

Route::get('/', [AdminController::class, 'home']);

route::get('/home', [AdminController::class, 'index'])->name('home');
Route::get('/home-index', [HomeController::class, 'index'])->name('home.index');


Route::get('/index', [BorrowerController::class, 'index'])->name('index');

// Sa application form ni borrower
Route::get('/showForm', [BorrowerController::class, 'showForm'])->name('borrower.application.form');
Route::post('/borrower/submit-application', [BorrowerController::class, 'submitApplication'])->name('borrower.submitApplication');

//ledgers

Route::get('/ledger', [AdminController::class, 'listAllLedgers'])->name('loans.index');
Route::post('/ledger', [AdminController::class, 'storeLoan'])->name('loan.store');
Route::get('/ledger/view-schedule/{id}', [AdminController::class, 'showLedgerDetails'])->name('loan.viewSchedule');
Route::get('/ledger/{id}/edit', [AdminController::class, 'editLedger'])->name('loan.edit');
Route::put('/ledger/ledgerupdate/{id}', [AdminController::class, 'ledgerupdate'])->name('loan.update');
Route::delete('/ledger/destroy/{id}', [AdminController::class, 'deleteLedger'])->name('ledgerdestroy');
Route::get('/ledger/{loanId}', [AdminController::class, 'loanSchedule'])->name('ledger.schedule');
Route::get('/loan/{loanId}/next-payment', [AdminController::class, 'showPaymentDate']);


//Payment
Route::get('/payment', [AdminController::class, 'paymentIndex'])->name('payments.index');
Route::POST('/payment', [AdminController::class, 'paymentStore'])->name('payments.store');
Route::get('/get-loan-details/{id}', [AdminController::class, 'getLoanDetails']);
Route::get('/get-loan-schedule-dates/{ledger_id}', [AdminController::class, 'getLoanScheduleDates']);

// Application form na nakay admin
Route::get('/admin/pending-applications', [AdminController::class, 'showPendingApplications'])->name('admin.pending_applications');
Route::get('/admin/application/{id}', [AdminController::class, 'showApplication'])->name('application.show');
Route::post('/admin/application/{id}/approve', [AdminController::class, 'approveApplication'])->name('application.approve');
Route::post('/admin/application/{id}/reject', [AdminController::class, 'rejectApplication'])->name('application.reject');
Route::get('/admin/application-details', [AdminController::class, 'showApprovedApplications'])->name('admin.application_details');
Route::get('/admin/pending-applications', [AdminController::class, 'showPendingApplications'])->name('admin.pending_applications');
//Loan Type
Route::get('/add_loan_type', [AdminController::class, 'show_loan_type_form'])->name('loan_type_form');
Route::post('/add_loan_type', [AdminController::class, 'add_loan_type'])->name('add_loan_type');
Route::delete('/add_loan_type/{id}', [AdminController::class, 'delete_loan_type'])->name('delete_loan_type');

//Kay Employee
Route::get('/create_employee', [AdminController::class, 'create_employee'])->name('create_employee');
Route::post('/add_employee', [AdminController::class, 'add_employee'])->name('add_employee');
Route::get('/view_employee', [AdminController::class, 'view_employee'])->name('view_employee');
Route::get('/employee_delete/{id}', [AdminController::class, 'employee_delete'])->name('employee_delete');
Route::get('/update_employee/{id}', [AdminController::class, 'update_employee'])->name('update_employee');
Route::post('/edit_employee/{id}', [AdminController::class, 'edit_employee'])->name('edit_employee');

route::get('/loan_plan', [AdminController::class, 'loan_plan']);
route::get('/view_ledger', [AdminController::class, 'view_ledger']);

route::get('/view_user', [AdminController::class, 'view_user'])->name('view_user');
route::get('/dashboard', [AdminController::class, 'dashboard']);

//Kay Borrower
Route::get('/add_borrower', [AdminController::class, 'show_add_borrower_form'])->name('add_borrower_form');
Route::post('/add_borrower', [AdminController::class, 'add_borrower'])->name('add_borrower');
Route::get('/borrowers', [AdminController::class, 'view_borrower'])->name('borrowers.view');
Route::get('/borrower_delete/{id}', [AdminController::class, 'borrower_delete'])->name('borrower_delete');
Route::get('/update_borrower/{id}', [AdminController::class, 'update_borrower'])->name('update_borrower');
Route::post('/edit_borrower/{id}', [AdminController::class, 'edit_borrower'])->name('edit_borrower');

//
Route::get('completed_ledger', [AdminController::class, 'completed']);
Route::get('/completed-ledger', [AdminController::class, 'viewCompletedLedger'])->name('completed-ledger.view');
Route::post('/completed-ledger', [AdminController::class, 'addCompletedLedger'])->name('completed-ledger.add');
Route::get('/completed-ledger', [AdminController::class, 'completed'])->name('completed-ledger.show');



//Loan Plans
Route::get('/loan_plan', [AdminController::class, 'loanplan'])->name('loan_plan');
Route::post('/loan_plan', [AdminController::class, 'planstore'])->name('planstore');
Route::get('/loan_plan/edit/{id}', [AdminController::class, 'planedit'])->name('planedit');
Route::put('/loan_plan/update/{id}', [AdminController::class, 'planupdate'])->name('planupdate');
Route::delete('/loan_plan/destroy/{id}', [AdminController::class, 'plandestroy'])->name('plandestroy');


//ledger na nasa posting clerk module
Route::group(['middleware' => ['checkUserType:posting clerk']], function () {
Route::get('/ongoing_ledger', [PostingClerkController::class, 'listAllOngoingLedgers'])->name('ongoing_ledger.index');
Route::post('/ongoing_ledger', [PostingClerkController::class, 'storeOngoingLoan'])->name('ongoing_ledger.store');
Route::get('/ongoing_ledger/view-schedule/{id}', [PostingClerkController::class, 'showOngoingLedgerDetails'])->name('ongoing_ledger.viewSchedule');
Route::get('/ongoing_ledger/{id}/edit', [PostingClerkController::class, 'editOngoingLedger'])->name('ongoing_ledger.edit');
Route::put('/ongoing_ledger/update/{id}', [PostingClerkController::class, 'updateOngoingLedger'])->name('ongoing_ledger.update');
Route::delete('/ongoing_ledger/destroy/{id}', [PostingClerkController::class, 'deleteOngoingLedger'])->name('ongoing_ledger.destroy');
Route::get('/ongoing_ledger/{loanId}', [PostingClerkController::class, 'loanSchedule'])->name('ongoing_ledger.schedule');
Route::get('/loan/{loanId}/next-payment', [PostingClerkController::class, 'showPaymentDate']);
});



//
Route::get('/collector-index', [CollectorController::class, 'collectorindex'])->name('collector.index');



//borrower na nasa posting clerk module 
Route::get('/add_new_borrower', [PostingClerkController::class, 'show_new_borrower_form'])->name('new_borrower_form');
Route::post('/add_new_borrower', [PostingClerkController::class, 'add_new_borrower'])->name('add_new_borrower');
Route::get('/view_borrowers', [PostingClerkController::class, 'view_all_borrowers'])->name('borrowers.view_all');
Route::get('/remove_borrower/{id}', [PostingClerkController::class, 'remove_borrower'])->name('remove_borrower');
Route::get('/edit_borrower/{id}', [PostingClerkController::class, 'edit_borrower_info'])->name('edit_borrower_info');
Route::post('/update_borrower/{id}', [PostingClerkController::class, 'update_borrower_info'])->name('update_borrower_info');


//ledger na nasa posting clerk module
Route::get('/ongoing_ledger', [PostingClerkController::class, 'listAllOngoingLedgers'])->name('ongoing_ledger.index');
Route::post('/ongoing_ledger', [PostingClerkController::class, 'storeOngoingLoan'])->name('ongoing_ledger.store');
Route::get('/ongoing_ledger/view-schedule/{id}', [PostingClerkController::class, 'showOngoingLedgerDetails'])->name('ongoing_ledger.viewSchedule');
Route::get('/ongoing_ledger/{id}/edit', [PostingClerkController::class, 'editOngoingLedger'])->name('ongoing_ledger.edit');
Route::put('/ongoing_ledger/update/{id}', [PostingClerkController::class, 'updateOngoingLedger'])->name('ongoing_ledger.update');
Route::delete('/ongoing_ledger/destroy/{id}', [PostingClerkController::class, 'deleteOngoingLedger'])->name('ongoing_ledger.destroy');
Route::get('/ongoing_ledger/{loanId}', [PostingClerkController::class, 'loanSchedule'])->name('ongoing_ledger.schedule');
Route::get('/loan/{loanId}/next-payment', [PostingClerkController::class, 'showPaymentDate']);

//loan type sa posting clerk module
Route::get('/add_loantype', [PostingClerkController::class, 'show_loantype_form'])->name('loantype');
Route::post('/add_loantype', [PostingClerkController::class, 'add_loantype'])->name('add_loantype');
Route::delete('/add_loantype/{id}', [PostingClerkController::class, 'delete_loantype'])->name('delete_loantype');

//loan plan sa posting clerk module
Route::get('/loanplan', [PostingClerkController::class, 'loan_plan'])->name('loanplan');
Route::post('/loanplan', [PostingClerkController::class, 'loanplan_store'])->name('loanplanstore');
Route::get('/loanplan/edit/{id}', [PostingClerkController::class, 'loanplan_edit'])->name('loanplanedit');
Route::put('/loanplan/update/{id}', [PostingClerkController::class, 'loanplan_update'])->name('loanplanupdate');
Route::delete('/loanplan/destroy/{id}', [PostingClerkController::class, 'loanplan_destroy'])->name('loanplandestroy');

//payment sa posting clerk module
Route::get('/loan-payment', [PostingClerkController::class, 'loanPaymentIndex'])->name('loan_payments.index');
Route::post('/loan-payment', [PostingClerkController::class, 'loanPaymentStore'])->name('loan_payments.store');
Route::get('/get-loan-details/{id}', [PostingClerkController::class, 'getLoanDetails']);
Route::get('/get-loan-schedule-dates/{ledger_id}', [PostingClerkController::class, 'getLoanScheduleDates']);

//application na nakay posting clerk
Route::get('/posting-clerk/pending-requests', [PostingClerkController::class, 'showPendingApplications'])->name('posting_clerk.pending_requests');
Route::post('/posting-clerk/application/{id}/approve', [PostingClerkController::class, 'approveApplication'])->name('posting_clerk.apps.approve');
Route::post('/posting-clerk/application/{id}/reject', [PostingClerkController::class, 'rejectApplication'])->name('posting_clerk.apps.reject');
Route::get('/posting-clerk/approved-requests', [PostingClerkController::class, 'showApprovedApplications'])->name('posting_clerk.approved_requests');
Route::get('/posting-clerk/application/{id}', [PostingClerkController::class, 'showApplication'])->name('postingclerk.app_details');


//application na nakay credit
Route::get('/credit-investigator/pending-requests', [CreditInvestigatorController::class, 'showPendingApplications'])->name('credit_investigator.pending_requests');
Route::get('/credit-investigator/application/{id}', [CreditInvestigatorController::class, 'showApplication'])->name('credit_investigator.application.show');
Route::post('/credit-investigator/application/{id}/approve', [CreditInvestigatorController::class, 'approveApplication'])->name('credit_investigator.application.approve');
Route::post('/credit-investigator/application/{id}/reject', [CreditInvestigatorController::class, 'rejectApplication'])->name('credit_investigator.application.reject');
Route::get('/credit-investigator/approved-requests', [CreditInvestigatorController::class, 'showApprovedApplications'])->name('credit_investigator.approved_requests');


//employee na nakay manager
Route::get('/createemployee', [ManagerController::class, 'createEmployee'])->name('manager.employee_create');
Route::post('/addemployee', [ManagerController::class, 'addEmployee'])->name('manager.employee_add');
Route::get('/viewemployee', [ManagerController::class, 'viewEmployee'])->name('manager.employee_view');
Route::get('/employeedelete/{id}', [ManagerController::class, 'deleteEmployee'])->name('manager.employee_delete');
Route::get('/updateemployee/{id}', [ManagerController::class, 'updateEmployee'])->name('manager.employee_update');
Route::post('/editemployee/{id}', [ManagerController::class, 'editEmployee'])->name('manager.employee_edit');

// application kay manager
Route::get('/manager/pending-requests', [ManagerController::class, 'showPendingApplications'])->name('manager.pending_requests');
Route::post('/manager/application/{id}/approve', [ManagerController::class, 'approveApplication'])->name('manager.application.approve');
Route::post('/manager/application/{id}/reject', [ManagerController::class, 'rejectApplication'])->name('manager.application.reject');
Route::get('/manager/approved-requests', [ManagerController::class, 'showApprovedApplications'])->name('manager.approved_requests');
Route::get('/manager/application/{id}', [ManagerController::class, 'showApplication'])->name('manager.application_detail');

