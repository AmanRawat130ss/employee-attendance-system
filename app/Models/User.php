<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'employee_id',
        'role',
        'phone',
        'department',
        'designation',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Check if current user is an administrator
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Check if current user is an employee
    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }

    // Has many relationship: an employee has many attendance records
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // Has one relationship: get today's attendance record for this employee
    public function todayAttendance()
    {
        return $this->hasOne(Attendance::class)->where('date', now()->toDateString());
    }
}
