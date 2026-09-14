<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 1. Temporarily disable foreign key checks to safely refresh
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('attendances')->truncate();
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Create Administrator
        User::create([
            'id' => 1,
            'name' => 'System Administrator',
            'email' => 'admin@ems.com',
            'password' => Hash::make('password123'),
            'employee_id' => 'EMP000',
            'role' => 'admin',
            'phone' => '9876543210',
            'department' => 'Management',
            'designation' => 'System Admin',
            'status' => 'active',
        ]);

        // 3. Create Exact Employees (Aman, Chandler, Joey, Ross, Monica, Phoebe)
        $employees = [
            [
                'id' => 13,
                'name' => 'Aman Rawat',
                'email' => 'aman@ems.com',
                'employee_id' => 'EMP001',
                'phone' => '9811122233',
                'department' => 'Engineering',
                'designation' => 'Full Stack Developer',
                'role' => 'employee',
                'status' => 'active',
                'password' => Hash::make('password123'),
            ],
            [
                'id' => 14,
                'name' => 'Chandler Bing',
                'email' => 'chandler@ems.com',
                'employee_id' => 'EMP002',
                'phone' => '9822233344',
                'department' => 'Statistical Analysis',
                'designation' => 'IT & Data Lead',
                'role' => 'employee',
                'status' => 'active',
                'password' => Hash::make('password123'),
            ],
            [
                'id' => 15,
                'name' => 'Joey Tribbiani',
                'email' => 'joey@ems.com',
                'employee_id' => 'EMP003',
                'phone' => '9833344455',
                'department' => 'Marketing',
                'designation' => 'Brand Specialist',
                'role' => 'employee',
                'status' => 'active',
                'password' => Hash::make('password123'),
            ],
            [
                'id' => 16,
                'name' => 'Ross Geller',
                'email' => 'ross@ems.com',
                'employee_id' => 'EMP004',
                'phone' => '9844455566',
                'department' => 'Research',
                'designation' => 'Senior Paleontologist',
                'role' => 'employee',
                'status' => 'active',
                'password' => Hash::make('password123'),
            ],
            [
                'id' => 17,
                'name' => 'Monica Geller',
                'email' => 'monica@ems.com',
                'employee_id' => 'EMP005',
                'phone' => '9855566677',
                'department' => 'Operations',
                'designation' => 'Quality & Food Lead',
                'role' => 'employee',
                'status' => 'active',
                'password' => Hash::make('password123'),
            ],
            [
                'id' => 18,
                'name' => 'Phoebe Buffay',
                'email' => 'phoebe@ems.com',
                'employee_id' => 'EMP006',
                'phone' => '9866677788',
                'department' => 'Human Resources',
                'designation' => 'Wellness Specialist',
                'role' => 'employee',
                'status' => 'active',
                'password' => Hash::make('password123'),
            ],
        ];

        foreach ($employees as $emp) {
            User::create($emp);
        }

        // 4. Load Complete Attendance History (456 records from July to September)
        $attendanceSqlFile = base_path('personal/insert_attendance.sql');
        if (file_exists($attendanceSqlFile)) {
            $sql = file_get_contents($attendanceSqlFile);
            DB::unprepared($sql);
        }
    }
}
