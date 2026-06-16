<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - Sungai Sulu Store</title>
    
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
        
        .password-requirement {
            font-size: 11px;
            margin-top: 6px;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }
        
        .requirement-item {
            display: flex;
            align-items: center;
            gap: 4px;
            color: #9ca3af;
            transition: color 0.2s ease;
        }
        
        .requirement-item.valid {
            color: #10b981;
        }
        
        .requirement-item .check {
            font-weight: bold;
        }
        
        .match-message {
            font-size: 11px;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
            color: #4b5563;
        }
        
        .match-message.valid {
            color: #10b981;
        }
        
        .password-wrapper {
            position: relative;
        }
        
        .password-wrapper input {
            padding-right: 40px;
        }
        
        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #9ca3af;
            font-size: 14px;
            user-select: none;
        }
        
        .toggle-password:hover {
            color: #6366f1;
        }
    </style>
</head>
<body class="bg-pastel-purple min-h-screen flex items-center justify-center p-4">
    
    <div class="w-[700px] bg-white rounded-2xl shadow-2xl p-6">
        
        <div class="logo-container mb-4 text-center">
            <div class="w-20 h-20 bg-gray-100 rounded-xl flex items-center justify-center mx-auto shadow-md overflow-hidden">
                <img src="{{ asset('images/logo.png') }}" 
                     alt="Sungai Sulu Store Logo" 
                     class="w-full h-full object-cover"
                     onerror="this.style.display='none'; this.parentElement.innerHTML='<span class=\'text-4xl\'>🏪</span>'">
            </div>
        </div>
        
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-2 logo-container" style="animation-delay: 0.1s">
            Create Account
        </h1>
        
        <p class="text-gray-500 text-sm text-center mb-6 logo-container" style="animation-delay: 0.2s">
            Register to get started
        </p>
        
        <form method="POST" action="{{ route('register.post') }}" id="registerForm">
            @csrf
            
            <div class="grid grid-cols-2 gap-4">
                
                <div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Full Name</label>
                        <input type="text" name="name" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                               placeholder="Enter your full name">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Email Address</label>
                        <input type="email" name="email" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                               placeholder="Enter your email">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Phone Number</label>
                        <input type="tel" name="phone" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                               placeholder="Enter your phone number">
                    </div>
                </div>
                
                <div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
                        <div class="password-wrapper">
                            <input type="password" name="password" id="password" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                   placeholder="Create a password">
                            <span class="toggle-password" onclick="togglePassword('password')">👁️</span>
                        </div>
                        
                        <div class="password-requirement">
                            <div class="requirement-item" id="req-length">
                                <span class="check">✓</span>
                                <span>Min 8 aksara</span>
                            </div>
                            <div class="requirement-item" id="req-upper">
                                <span class="check">✓</span>
                                <span>Huruf besar</span>
                            </div>
                            <div class="requirement-item" id="req-lower">
                                <span class="check">✓</span>
                                <span>Huruf kecil</span>
                            </div>
                            <div class="requirement-item" id="req-number">
                                <span class="check">✓</span>
                                <span>Nombor</span>
                            </div>
                            <div class="requirement-item" id="req-symbol">
                                <span class="check">✓</span>
                                <span>Simbol</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Confirm Password</label>
                        <div class="password-wrapper">
                            <input type="password" name="password_confirmation" id="confirmPassword" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                   placeholder="Confirm your password">
                            <span class="toggle-password" onclick="togglePassword('confirmPassword')">👁️</span>
                        </div>
                        <div id="match-message" class="match-message hidden">
                            <span>✓</span> Password match
                        </div>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn-primary w-full text-white py-2 rounded-lg font-semibold mt-2">
                Create Account
            </button>
        </form>
        
        <div class="text-center mt-6">
            <p class="text-gray-600 text-sm">
                Already have an account? 
                <a href="{{ route('login') }}" class="text-indigo-600 font-semibold hover:text-indigo-800">Sign In</a>
            </p>
        </div>
        
    </div>
    
    <script>
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('confirmPassword');
        const matchMessage = document.getElementById('match-message');
        
        const reqs = {
            length: { regex: /.{8,}/, element: document.getElementById('req-length') },
            upper: { regex: /[A-Z]/, element: document.getElementById('req-upper') },
            lower: { regex: /[a-z]/, element: document.getElementById('req-lower') },
            number: { regex: /[0-9]/, element: document.getElementById('req-number') },
            symbol: { regex: /[!@#$%^&*(),.?":{}|<>]/, element: document.getElementById('req-symbol') }
        };
        
        function updateRequirements(password) {
            for (const [key, req] of Object.entries(reqs)) {
                const isValid = req.regex.test(password);
                if (isValid) {
                    req.element.classList.add('valid');
                } else {
                    req.element.classList.remove('valid');
                }
            }
        }
        
        function checkPasswordMatch() {
            const password = passwordInput.value;
            const confirm = confirmInput.value;
            
            if (confirm.length > 0 && password === confirm) {
                matchMessage.classList.remove('hidden');
                matchMessage.classList.add('valid');
            } else {
                matchMessage.classList.add('hidden');
                matchMessage.classList.remove('valid');
            }
        }
        
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
            field.setAttribute('type', type);
        }
        
        passwordInput.addEventListener('input', function() {
            updateRequirements(this.value);
            checkPasswordMatch();
        });
        
        confirmInput.addEventListener('input', checkPasswordMatch);
        
        updateRequirements('');
    </script>
    
</body>
</html>