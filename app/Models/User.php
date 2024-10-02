<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
        'phone',
        'usertype'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    protected static function booted()
    {
        static::created(function ($user) {
            $employeeTypes = ['manager', 'posting clerk', 'collector', 'credit investigator'];
            if (in_array($user->usertype, $employeeTypes)) {
                Employee::create([
                    'user_id' => $user->id,
                    'employee_name' => $user->name, 
                    'position' => $user->usertype, 
                   
                ]);
            }
        });

        static::updated(function ($user) {
            $employeeTypes = ['manager', 'posting clerk', 'collector', 'credit investigator'];
            
            if (in_array($user->usertype, $employeeTypes)) {
                // Update or create the employee record
                Employee::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'employee_name' => $user->name, 
                        'position' => $user->usertype, 
                       
                    ]
                );
            } else {
                // Delete the employee record if the usertype is no longer in the list
                Employee::where('user_id', $user->id)->delete();
            }
        });
    }
}