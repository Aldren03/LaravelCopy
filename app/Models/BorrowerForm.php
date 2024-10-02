<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowerForm extends Model
{
    use HasFactory;
    protected $fillable = [
        'reference_no',
        'borrower_title',
        'borrower_name',
        'spouse_title',
        'spouse_name',
        'sex',
        'date_of_birth',
        'marital_status',
        'home_address',
        'municipality',
        'place_of_birth',
        'educational_attainment',
        'educational_status',
        'age',
        'school',
        'height',
        'weight',
        'picture',
        'email',
        'amount_applied',
        'purpose',
        'business_name',
        'business_address',
        'business_contact_number',
        'employer_name',
        'position',
        'employer_contact_number',
        'reference_name',
        'reference_relationship',
        'reference_address',
        'status',
    ];
}
