@extends('layouts.app')

@section('title', 'Add New Employee')
@section('header', 'Add New Employee')
@section('subheader', 'Create a new employee account')

@section('content')
    <div class="max-w-2xl mx-auto">

        <div class="bg-purple-50 dark:bg-gray-800 rounded-2xl shadow-lg p-8">

            <!-- Back Button -->
            <a href="{{ route('admin.employees') }}"
                class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-800 mb-6 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Employees
            </a>

            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Employee Information</h2>

            <form method="POST" action="{{ route('admin.employees.store') }}" id="employeeForm">
                @csrf

                <div class="space-y-5">
                    <!-- Full Name -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" required
                            class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-gray-900 text-gray-800 dark:text-white"
                            placeholder="Enter full name">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" required
                            class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-gray-900 text-gray-800 dark:text-white"
                            placeholder="Enter email address">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Phone Number <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="phone" id="phone" required
                            class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-gray-900 text-gray-800 dark:text-white"
                            placeholder="Enter phone number (e.g., 012-3456789)">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Role -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <select name="role" id="role" required
                            class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-gray-900 text-gray-800 dark:text-white">
                            <option value="employee">Employee</option>
                            <option value="admin">Admin</option>
                        </select>
                        @error('role')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Password
                        </label>
                        <input type="password" name="password" id="password"
                            class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-gray-900 text-gray-800 dark:text-white"
                            placeholder="Leave blank for default 'password123'">
                        <p class="text-xs text-gray-500 mt-1">Minimum 8 characters. Leave blank to use default password.</p>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Preview Section -->
                    <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl">
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Preview:</p>
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full flex items-center justify-center">
                                <span id="preview_initial" class="text-white font-semibold text-sm">?</span>
                            </div>
                            <div>
                                <p id="preview_name" class="font-medium text-gray-800 dark:text-white">-</p>
                                <p id="preview_email" class="text-sm text-gray-500 dark:text-gray-400">-</p>
                            </div>
                            <div class="ml-auto">
                                <span id="preview_role"
                                    class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">-</span>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-3 pt-4">
                        <a href="{{ route('admin.employees') }}"
                            class="flex-1 text-center border border-gray-300 dark:border-gray-600 py-3 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            Cancel
                        </a>
                        <button type="submit"
                            class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 rounded-xl font-semibold hover:from-indigo-700 hover:to-purple-700 transition shadow-lg">
                            Add Employee
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Live preview
        const nameInput = document.getElementById('name');
        const emailInput = document.getElementById('email');
        const roleSelect = document.getElementById('role');

        function updatePreview() {
            const name = nameInput.value || '-';
            const email = emailInput.value || '-';
            const role = roleSelect.value === 'admin' ? 'Admin' : 'Employee';
            const initial = name !== '-' ? name.charAt(0).toUpperCase() : '?';

            document.getElementById('preview_name').textContent = name;
            document.getElementById('preview_email').textContent = email;
            document.getElementById('preview_role').textContent = role;
            document.getElementById('preview_initial').textContent = initial;

            // Change role badge color
            const roleBadge = document.getElementById('preview_role');
            if (roleSelect.value === 'admin') {
                roleBadge.className = 'px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800';
            } else {
                roleBadge.className = 'px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800';
            }
        }

        nameInput.addEventListener('input', updatePreview);
        emailInput.addEventListener('input', updatePreview);
        roleSelect.addEventListener('change', updatePreview);

        // Initialize
        updatePreview();
    </script>
@endsection
