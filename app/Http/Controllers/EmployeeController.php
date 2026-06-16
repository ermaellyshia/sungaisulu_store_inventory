<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = User::where('role', 'employee')->get();
        return view('admin.employees.index', compact('employees'));
    }
    
    public function create()
    {
        return view('admin.employees.create');
    }
    
    public function edit($id)
    {
        $employee = User::findOrFail($id);
        return view('admin.employees.edit', compact('employee'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|min:8',
            'role' => 'required|in:admin,employee',
        ]);
        
        if (empty($validated['password'])) {
            $validated['password'] = Hash::make('password123');
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }
        
        User::create($validated);
        
        return redirect()->route('admin.employees')->with('success', 'Employee created successfully');
    }
    
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|min:8',
            'role' => 'sometimes|in:admin,employee',
        ]);
        
        if (isset($validated['password']) && !empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        $user->update($validated);
        
        return redirect()->route('admin.employees')->with('success', 'Employee updated successfully');
    }
    
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.employees')->with('error', 'Cannot delete your own account');
        }
        
        $user->delete();
        
        return redirect()->route('admin.employees')->with('success', 'Employee deleted successfully');
    }
}