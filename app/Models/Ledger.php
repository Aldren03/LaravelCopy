<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    protected $table = 'ledger';

    protected $fillable = [
        'ref_no',
        'borrower_id',
        'loan_type_id',
        'loan_plan_id',
        'amount',
        'payable_amount',
        'purpose',
        'status',
        'date_released',
        'borrower_form_id',
    ];



    public function borrower()
{
    return $this->belongsTo(Borrower::class, 'borrower_id');
}

    public function borrowerForm()
{
    return $this->belongsTo(BorrowerForm::class, 'borrower_form_id');
}

    public function loanType()
    {
        return $this->belongsTo(LoanTypes::class, 'loan_type_id');
    }

    public function loanPlan()
    {
        return $this->belongsTo(LoanPlan::class, 'loan_plan_id');
    }
    public function borrowerForms()
    {
        return $this->belongsTo(BorrowerForm::class);
    }

    public function loanSchedule()
{
    return $this->hasOne(LoanSchedule::class, 'ledger_id');
}

public function payment()
{
    return $this->belongsTo(Payment::class);
}

}
