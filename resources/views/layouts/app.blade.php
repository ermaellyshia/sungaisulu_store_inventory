<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sungai Sulu Store') - Inventory System</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        * { font-family: 'Inter', sans-serif; }
        
        [x-cloak] { display: none !important; }
        
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: #c7d2fe;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #818cf8;
        }
        
        .hover-lift {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
    </style>
    
    <script>
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 transition-colors duration-200">
    <div x-data="{ sidebarOpen: true, darkMode: localStorage.getItem('darkMode') === 'true' }" 
         x-init="$watch('darkMode', val => {
             localStorage.setItem('darkMode', val);
             if (val) {
                 document.documentElement.classList.add('dark');
             } else {
                 document.documentElement.classList.remove('dark');
             }
         })"
         :class="{ 'dark': darkMode }"
         class="min-h-screen">
        
        @include('components.sidebar')
        
        <div class="transition-all duration-300" :class="sidebarOpen ? 'ml-64' : 'ml-0'">
            @include('components.navbar')
            
            <main class="p-6">
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    
    @stack('scripts')
</body>
</html>