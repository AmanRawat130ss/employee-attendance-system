<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    // Database table columns that are mass assignable
    protected $fillable = [
        'user_id',
        'date',
        'login_time',
        'logout_time',
        'total_hours',
        'status',
        'notes',
    ];

    // Belongs to relationship: every attendance belongs to an employee (User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Logic: Calculate total working hours from login and logout times
    public static function calculateHours($loginTime, $logoutTime)
    {
        // If either time is missing, return 0 hours
        if (!$loginTime || !$logoutTime) {
            return 0;
        }

        $start = Carbon::parse($loginTime);
        $end = Carbon::parse($logoutTime);

        // If logout time is earlier than login time, return 0
        if ($end < $start) {
            return 0;
        }

        // Calculate difference in minutes and divide by 60 to get decimal hours
        $minutes = $start->diffInMinutes($end);
        $hours = round($minutes / 60, 2);

        return $hours;
    }

    // Logic: Determine attendance status based on total working hours
    public static function determineStatus($hours)
    {
        // If employee worked 8 hours or more: Present
        if ($hours >= 8) {
            return 'Present';
        }
        // If employee worked less than 8 hours but greater than 0: Half Day
        else if ($hours > 0 && $hours < 8) {
            return 'Half Day';
        }
        // If no work hours recorded
        else {
            return 'Absent';
        }
    }
}
