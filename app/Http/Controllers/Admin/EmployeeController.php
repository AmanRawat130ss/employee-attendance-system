<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    // 1. Display list of all employees
    public function index(Request $request)
    {
        $query = User::where('role', 'employee');

        // Optional search by name, email, or employee ID
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        $employees = $query->orderBy('id', 'desc')->get();

        return view('admin.employees.index', [
            'employees' => $employees,
            'search'    => $request->search ?? '',
        ]);
    }

    // 2. Show form to add a new employee
    public function create()
    {
        return view('admin.employees.create');
    }

    // 3. Save new employee in database
    public function store(Request $request)
    {
        // Validate form input
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|min:6',
            'employee_id' => 'required|string|unique:users,employee_id',
            'phone'       => 'nullable|string|max:20',
            'department'  => 'nullable|string|max:100',
            'designation' => 'nullable|string|max:100',
        ]);

        // Create new user with employee role
        User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'employee_id' => $request->employee_id,
            'role'        => 'employee',
            'phone'       => $request->phone,
            'department'  => $request->department,
            'designation' => $request->designation,
            'status'      => 'active',
        ]);

        return redirect()->route('admin.employees.index')
            ->with('success', 'New employee added successfully!');
    }

    // 4. Show form to edit employee
    public function edit($id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);

        return view('admin.employees.edit', [
            'employee' => $employee,
        ]);
    }

    // 5. Update employee details in database
    public function update(Request $request, $id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);

        // Validate form input (ignore current user email & employee_id for unique check)
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $employee->id,
            'employee_id' => 'required|string|unique:users,employee_id,' . $employee->id,
            'phone'       => 'nullable|string|max:20',
            'department'  => 'nullable|string|max:100',
            'designation' => 'nullable|string|max:100',
            'status'      => 'required|in:active,inactive',
        ]);

        // Prepare data to update
        $data = [
            'name'        => $request->name,
            'email'       => $request->email,
            'employee_id' => $request->employee_id,
            'phone'       => $request->phone,
            'department'  => $request->department,
            'designation' => $request->designation,
            'status'      => $request->status,
        ];

        // If a new password was typed, update it
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6']);
            $data['password'] = Hash::make($request->password);
        }

        $employee->update($data);

        return redirect()->route('admin.employees.index')
            ->with('success', 'Employee updated successfully!');
    }

    // 6. Toggle active / inactive status
    public function toggleStatus($id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);

        // Flip status
        if ($employee->status == 'active') {
            $employee->status = 'inactive';
            $msg = "Employee {$employee->name} has been deactivated.";
        } else {
            $employee->status = 'active';
            $msg = "Employee {$employee->name} has been activated.";
        }

        $employee->save();

        return back()->with('success', $msg);
    }

    // 7. Delete employee
    public function destroy($id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);
        $name = $employee->name;
        $employee->delete();

        return redirect()->route('admin.employees.index')
            ->with('success', "Employee {$name} deleted successfully!");
    }
}
