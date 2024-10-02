<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompletedLedger extends Model
{
    use HasFactory;
    protected $table = 'completed_ledgers';

    
    protected $fillable = [
        'first_name',
        'last_name',
        'municipality',
        'home_address',
        'contact_number',
        'loan_type_id',
        'loan_plan_id',
        'amount',
        'start_date',
        'status',
        'reference_number',
    ];


    public function loanType()
    {
        return $this->belongsTo(LoanTypes::class, 'loan_type_id');
    }
    
    public function loanPlan()
    {
        return $this->belongsTo(LoanPlan::class, 'loan_plan_id');
    }
    public function completedSchedule()
{
    return $this->hasOne(CompletedSchedule::class, 'completed_ledger_id');
}
    
}
