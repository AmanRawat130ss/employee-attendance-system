<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 1. Create Administrator
        $admin = User::firstOrCreate(
            ['email' => 'admin@ems.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password123'),
                'employee_id' => 'EMP000',
                'role' => 'admin',
                'phone' => '9876543210',
                'department' => 'Management',
                'designation' => 'System Admin',
                'status' => 'active',
            ]
        );

        // 2. Create Sample Employees
        $employeesData = [
            [
                'name' => 'Aman',
                'email' => 'aman@ems.com',
                'employee_id' => 'EMP001',
                'phone' => '9811122233',
                'department' => 'Engineering',
                'designation' => 'Full Stack Developer',
            ],
            [
                'name' => 'Rahul Sharma',
                'email' => 'rahul@ems.com',
                'employee_id' => 'EMP002',
                'phone' => '9811122234',
                'department' => 'Engineering',
                'designation' => 'Backend Developer',
            ],
            [
                'name' => 'Priya Patel',
                'email' => 'priya@ems.com',
                'employee_id' => 'EMP003',
                'phone' => '9822233344',
                'department' => 'Design',
                'designation' => 'UI/UX Designer',
            ],
            [
                'name' => 'Vikram Singh',
                'email' => 'vikram@ems.com',
                'employee_id' => 'EMP004',
                'phone' => '9833344455',
                'department' => 'Marketing',
                'designation' => 'SEO Specialist',
            ],
            [
                'name' => 'Sneha Roy',
                'email' => 'sneha@ems.com',
                'employee_id' => 'EMP005',
                'phone' => '9844455566',
                'department' => 'HR',
                'designation' => 'HR Executive',
            ],
            [
                'name' => 'Amit Verma',
                'email' => 'amit@ems.com',
                'employee_id' => 'EMP006',
                'phone' => '9855566677',
                'department' => 'Engineering',
                'designation' => 'QA Engineer',
            ],
        ];

        $createdEmployees = [];
        foreach ($employeesData as $data) {
            $createdEmployees[] = User::firstOrCreate(
                ['email' => $data['email']],
                array_merge($data, [
                    'password' => Hash::make('password123'),
                    'role' => 'employee',
                    'status' => 'active',
                ])
            );
        }

        // 3. Seed Realistic Attendance Records (Past 7 Days + Today)
        $today = Carbon::today();

        // Seed Past 5 working days
        for ($i = 5; $i >= 1; $i--) {
            $date = $today->copy()->subDays($i);

            // Skip weekends
            if ($date->isWeekend()) {
                continue;
            }

            foreach ($createdEmployees as $idx => $emp) {
                // Variations: mostly present, occasional half-day or leave
                if ($idx === 0) {
                    // Rahul: solid 8.5 hours
                    Attendance::firstOrCreate(
                        ['user_id' => $emp->id, 'date' => $date->toDateString()],
                        [
                            'login_time' => '09:15:00',
                            'logout_time' => '17:45:00',
                            'total_hours' => 8.50,
                            'status' => 'Present',
                            'notes' => 'On-time daily duty',
                        ]
                    );
                } elseif ($idx === 1 && $i === 2) {
                    // Priya had a half-day 2 days ago
                    Attendance::firstOrCreate(
                        ['user_id' => $emp->id, 'date' => $date->toDateString()],
                        [
                            'login_time' => '09:30:00',
                            'logout_time' => '13:00:00',
                            'total_hours' => 3.50,
                            'status' => 'Half Day',
                            'notes' => 'Half day personal leave',
                        ]
                    );
                } elseif ($idx === 2 && $i === 3) {
                    // Vikram on Leave 3 days ago
                    Attendance::firstOrCreate(
                        ['user_id' => $emp->id, 'date' => $date->toDateString()],
                        [
                            'login_time' => null,
                            'logout_time' => null,
                            'total_hours' => 0.00,
                            'status' => 'Leave',
                            'notes' => 'Sick leave',
                        ]
                    );
                } else {
                    Attendance::firstOrCreate(
                        ['user_id' => $emp->id, 'date' => $date->toDateString()],
                        [
                            'login_time' => '09:30:00',
                            'logout_time' => '18:00:00',
                            'total_hours' => 8.50,
                            'status' => 'Present',
                            'notes' => 'Regular working hours',
                        ]
                    );
                }
            }
        }

        // Today's attendance
        $todayStr = $today->toDateString();

        // 1. Rahul: Completed shift today (Present)
        Attendance::firstOrCreate(
            ['user_id' => $createdEmployees[0]->id, 'date' => $todayStr],
            [
                'login_time' => '09:30:00',
                'logout_time' => '18:00:00',
                'total_hours' => 8.50,
                'status' => 'Present',
                'notes' => 'Worked full day',
            ]
        );

        // 2. Priya: Half Day today
        Attendance::firstOrCreate(
            ['user_id' => $createdEmployees[1]->id, 'date' => $todayStr],
            [
                'login_time' => '10:00:00',
                'logout_time' => '13:30:00',
                'total_hours' => 3.50,
                'status' => 'Half Day',
                'notes' => 'Half-day duty',
            ]
        );

        // 3. Vikram: Approved Leave today
        Attendance::firstOrCreate(
            ['user_id' => $createdEmployees[2]->id, 'date' => $todayStr],
            [
                'login_time' => null,
                'logout_time' => null,
                'total_hours' => 0.00,
                'status' => 'Leave',
                'notes' => 'Casual leave',
            ]
        );

        // 4. Sneha: Clocked in today, not clocked out yet
        Attendance::firstOrCreate(
            ['user_id' => $createdEmployees[3]->id, 'date' => $todayStr],
            [
                'login_time' => '09:15:00',
                'logout_time' => null,
                'total_hours' => 0.00,
                'status' => 'Present',
                'notes' => 'Currently logged in',
            ]
        );

        // 5. Amit: Hasn't logged in today (will show as Absent Today automatically on dashboard)
    }
}
