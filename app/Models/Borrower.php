<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrower extends Model
{
    use HasFactory;
    protected $fillable = [
        'reference_no',
        'borrower_name',
        'borrower_address',
        'contact_number',
        'email'
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->reference_no = self::generateReferenceNo();
        });
    }

    private static function generateReferenceNo()
    {
        return 'TD3-' . strtoupper(uniqid());
    }
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
    
}
