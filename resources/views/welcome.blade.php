<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SungaiSulu Store</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        * { font-family: 'Inter', sans-serif; }
        
        .bg-pastel-purple {
            background: linear-gradient(135deg, #D8B4FE 0%, #C084FC 50%, #A855F7 100%);
        }
        
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
        
        .title-purple {
            background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
    </style>
</head>
<body class="bg-pastel-purple min-h-screen flex items-center justify-center p-4">
    
    <!-- KOTAK PUTIH - Saiz sederhana, tak perlu scroll -->
    <div class="w-[360px] bg-white rounded-2xl shadow-2xl p-6">
        
        <!-- LOGO -->
        <div class="logo-container mb-4 text-center">
            <div class="w-20 h-20 bg-gray-100 rounded-xl flex items-center justify-center mx-auto shadow-md overflow-hidden">
                <img src="{{ asset('images/logo.png') }}" 
                     alt="Sungai Sulu Store Logo" 
                     class="w-full h-full object-cover"
                     onerror="this.style.display='none'; this.parentElement.innerHTML='<span class=\'text-4xl\'>🏪</span>'">
            </div>
        </div>
        
        <!-- Title -->
        <h1 class="text-2xl font-bold text-center mb-2 logo-container title-purple" style="animation-delay: 0.1s">
            SungaiSulu Store
        </h1>
        
        <!-- Subtitle -->
        <p class="text-gray-700 text-sm text-center mb-6 logo-container" style="animation-delay: 0.2s">
            Admin & Employee Management System
        </p>
        
        @auth
            @if(auth()->user()->role === 'admin')
                <div class="text-center logo-container" style="animation-delay: 0.3s">
                    <a href="{{ url('/admin/dashboard') }}" 
                       class="btn-primary inline-block text-white px-6 py-2 rounded-lg font-semibold">
                        Go to Admin Dashboard →
                    </a>
                </div>
            @else
                <div class="text-center logo-container" style="animation-delay: 0.3s">
                    <a href="{{ url('/employee/dashboard') }}" 
                       class="btn-primary inline-block text-white px-6 py-2 rounded-lg font-semibold">
                        Go to Employee Dashboard →
                    </a>
                </div>
            @endif
        @else
            <div class="flex gap-4 justify-center logo-container" style="animation-delay: 0.3s">
                <a href="{{ route('login') }}" 
                   class="btn-primary text-white px-6 py-2 rounded-lg font-semibold">
                    Login
                </a>
                <a href="{{ route('register') }}" 
                   class="btn-primary text-white px-6 py-2 rounded-lg font-semibold">
                    Register
                </a>
            </div>
        @endauth
    </div>
    
</body>
</html>