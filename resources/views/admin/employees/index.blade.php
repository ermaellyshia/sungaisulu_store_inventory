@extends('layouts.app')

@section('title', 'Employee Management')
@section('header', 'Employee Management')
@section('subheader', 'Manage your store employees')

@section('content')
<div class="space-y-6">
    
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Employee List</h2>
        <a href="{{ route('admin.employees.create') }}" 
           class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-5 py-2.5 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            Add Employee
        </a>
    </div>
    
    <div class="bg-purple-50 dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full table-auto divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                <thead class="bg-purple-100 dark:bg-gray-900/50">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Name</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Email</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Phone</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Address</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Joined</th>
                        <th class="px-3 py-2 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($employees as $employee)
                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700/50 transition">
                        <td class="px-3 py-3">
                            <span class="font-medium text-gray-800 dark:text-white">{{ $employee->name }}</span>
                        </td>
                        <td class="px-3 py-3 text-gray-600 dark:text-gray-400 truncate max-w-[150px]">{{ $employee->email }}</td>
                        <td class="px-3 py-3 text-gray-600 dark:text-gray-400">{{ $employee->phone ?? '-' }}</td>
                        <td class="px-3 py-3 text-gray-600 dark:text-gray-400 truncate max-w-[120px]">{{ $employee->address ?? '-' }}</td>
                        <td class="px-3 py-3 text-gray-500 dark:text-gray-400 text-xs">{{ $employee->created_at->format('d M Y') }}</td>
                        <td class="px-3 py-3 text-right">
                            <button onclick='editEmployee({{ json_encode($employee) }})' 
                                    class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 transition text-sm">
                                Edit
                            </button>
                            <form action="{{ route('admin.employees.destroy', $employee->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 dark:text-red-400 hover:text-red-800 transition text-sm ml-2"
                                        onclick="return confirm('Delete {{ $employee->name }}?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            No employees found
                            <p class="text-sm mt-1">Click "Add Employee" to create your first employee</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Employee Modal -->
<div id="editEmployeeModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" style="display: none;">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditModal()"></div>
    <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800 dark:text-white">Edit Employee</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <form method="POST" id="editEmployeeForm">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name</label>
                    <input type="text" name="name" id="edit_name" required
                           class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-gray-900">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
                    <input type="email" name="email" id="edit_email" required
                           class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-gray-900">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone Number</label>
                    <input type="tel" name="phone" id="edit_phone"
                           class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-gray-900"
                           placeholder="Enter phone number">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address</label>
                    <textarea name="address" id="edit_address" rows="2"
                           class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-gray-900"
                           placeholder="Enter address"></textarea>
                </div>
            </div>
            <div class="mt-6 flex gap-3">
                <button type="button" onclick="closeEditModal()"
                        class="flex-1 px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl px-4 py-2.5 hover:from-indigo-700 hover:to-purple-700 transition shadow-lg">
                    Update Employee
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function editEmployee(employee) {
        document.getElementById('edit_name').value = employee.name || '';
        document.getElementById('edit_email').value = employee.email || '';
        document.getElementById('edit_phone').value = employee.phone || '';
        document.getElementById('edit_address').value = employee.address || '';
        document.getElementById('editEmployeeForm').action = '/admin/employees/' + employee.id;
        document.getElementById('editEmployeeModal').style.display = 'flex';
    }
    
    function closeEditModal() {
        document.getElementById('editEmployeeModal').style.display = 'none';
    }
</script>
@endsection