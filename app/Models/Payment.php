<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';
    
    protected $fillable = [
        'ref_no',
        'borrower_id',
        'ledger_id',
        'pay_amount',
        'penalty',
        'overdue',
        'payment_date',
    ];

    public function borrower()
    {
        return $this->belongsTo(Borrower::class);
    }

    public function ledger()
    {
        return $this->belongsTo(Ledger::class);
    }
}
