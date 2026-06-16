<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Sungai Sulu Store</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        * { font-family: 'Inter', sans-serif; }
        
        /* Purple Pastel Background - Sama dengan Homepage */
        .bg-pastel-purple {
            background: linear-gradient(135deg, #D8B4FE 0%, #C084FC 50%, #A855F7 100%);
        }
        
        /* Button Gradient - Sama dengan Homepage */
        .btn-primary {
            background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);
            transition: all 0.3s ease;
            box-shadow: 0 4px 14px 0 rgba(139, 92, 246, 0.4);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px 0 rgba(139, 92, 246, 0.5);
        }
        
        .logo-container {
            animation: fadeInUp 0.6s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="bg-pastel-purple min-h-screen flex items-center justify-center p-4">
    
    <!-- Kotak Putih -->
    <div class="w-[360px] bg-white rounded-2xl shadow-2xl p-6">
        
        <!-- LOGO (Gambar) -->
        <div class="logo-container mb-4 text-center">
            <div class="w-20 h-20 bg-gray-100 rounded-xl flex items-center justify-center mx-auto shadow-md overflow-hidden">
                <img src="{{ asset('images/logo.png') }}" 
                     alt="Sungai Sulu Store Logo" 
                     class="w-full h-full object-cover"
                     onerror="this.style.display='none'; this.parentElement.innerHTML='<span class=\'text-4xl\'>🏪</span>'">
            </div>
        </div>
        
        <!-- Title -->
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-2 logo-container" style="animation-delay: 0.1s">
            Sungai Sulu Store
        </h1>
        
        <p class="text-gray-500 text-sm text-center mb-6 logo-container" style="animation-delay: 0.2s">
            Welcome Back!
        </p>
        
        <!-- Login Form -->
        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Email Address</label>
                <input type="email" name="email" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                       placeholder="Enter your email">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
                <input type="password" name="password" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                       placeholder="Enter your password">
            </div>
            
            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>
            </div>
            
            <!-- Button Log In -->
            <button type="submit" class="btn-primary w-full text-white py-2 rounded-lg font-semibold">
                Log In
            </button>
        </form>
        
        <!-- Register Link -->
        <div class="text-center mt-6">
            <p class="text-gray-600 text-sm">
                Don't have an account? 
                <a href="{{ route('register') }}" class="text-indigo-600 font-semibold hover:text-indigo-800">Create Account</a>
            </p>
        </div>
        
        <!-- DEMO ACCOUNT DAH BUANG -->
        
    </div>
    
</body>
</html>