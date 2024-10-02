<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompletedSchedule extends Model
{
    use HasFactory;

    protected $table = 'completed_schedule';
    protected $fillable = [
        'completed_ledger_id',
        'date',
    ];

    public function completedledger()
    {
        return $this->belongsTo(CompletedLedger::class, 'completed_ledger_id');
    }
}
