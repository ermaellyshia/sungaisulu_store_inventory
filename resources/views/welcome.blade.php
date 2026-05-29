<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
    <style>
        body {
            font-family: Arial;
            text-align: center;
            margin-top: 100px;
            background: #f5f5f5;
        }

        .box {
            background: white;
            padding: 40px;
            width: 400px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            background: #3490dc;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn:hover {
            background: #2779bd;
        }
    </style>
</head>
<body>

<div class="box">
    <h1>Welcome to System</h1>
    <p>Admin & Employee Management System</p>

    <div>
        @auth
            <a href="{{ url('/dashboard') }}" class="btn">
                Go to Dashboard
            </a>
        @else
            <a href="{{ route('login') }}" class="btn">
                Login
            </a>

            <a href="{{ route('register') }}" class="btn">
                Register
            </a>
        @endauth
    </div>
</div>

</body>
</html>