<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanSchedule extends Model
{
    use HasFactory;
    protected $table = 'loan_schedule';
    protected $fillable = [
        'ledger_id',
        'date',
        'paid',
        'payment_date',
        'payment_amount',
        'penalty',
    ];

    public function ledger()
{
    return $this->belongsTo(Ledger::class, 'ledger_id');
}



public function markAsPaid($paymentAmount, $penalty = 0)
{
    $this->update([
        'paid' => 1,
        'payment_date' => Carbon::now(),
        'payment_amount' => $paymentAmount,
        'penalty' => $penalty,
    ]);
}
}
