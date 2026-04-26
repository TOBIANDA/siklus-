<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siklus - Log In</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Lato', sans-serif;
        }

        body {
            background-image: url('{{ asset("images/bg.png") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            color: white;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            background-color: black;
            opacity: 40%;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .logo {
            position: absolute;
            top: 30px;
            left: 40px;
            height: 90px;
        }

        .login-container {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 45px 60px;
            border-radius: 8px;
            width: 100%;
            max-width: 480px;
            z-index: 1;
        }

        .login-container h2 {
            text-align: left;
            margin-bottom: 20px;
            font-size: 40px;
            font-weight: 200;
            line-height: 1.1;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            background-color: #e2e2e2;
            font-size: 16px;
            color: #333;
            outline: none;
        }

        input::placeholder {
            color: #777;
        }

        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-wrapper input[type="password"],
        .password-wrapper input[type="text"] {
            width: 100%;
            padding-right: 50px;
        }

        .password-toggle-icon {
            position: absolute;
            right: 15px;
            cursor: pointer;
            height: 24px;
            z-index: 2;
        }

        .error-message {
            color: #ff6b6b;
            font-size: 14px;
            margin-top: -8px;
            margin-bottom: 4px;
        }

        button {
            background-color: #6b70ff;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 4px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 15px;
            transition: background-color 0.2s ease-in-out;
        }

        button:hover {
            background-color: #555be6;
        }

        .register-link {
            text-align: center;
            margin-top: 15px;
            font-size: 16px;
            color: #cccccc;
        }

        .register-link a {
            color: white;
            text-decoration: none;
            font-weight: 700;
            border-bottom: 1px solid white;
            padding-bottom: 1px;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 12px 15px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <img src="{{ asset('images/siklus.png') }}" alt="Siklus Logo" class="logo">

    <div class="login-container">
        <h2>Log In</h2>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="error-message">{{ $error }}</div>
            @endforeach
        @endif

        @if (session('success'))
            <div class="success-message">{{ session('success') }}</div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST">
            @csrf
            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
            <div class="password-wrapper">
                <input type="password" name="password" placeholder="Password" id="password" required>
                <span class="password-toggle-icon" id="togglePassword" style="display: flex; align-items: center; justify-content: center; height: 100%;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#777" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                </span>
            </div>
            <button type="submit">Log in</button>
        </form>

        <p class="register-link">Don't have an account? <a href="{{ route('register') }}">Register Here</a></p>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        const eyeOpen = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#777" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>`;
        const eyeClosed = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#777" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>`;

        if (togglePassword) {
            togglePassword.addEventListener('click', function (e) {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);

                if (password.getAttribute('type') === 'text') {
                    this.innerHTML = eyeClosed;
                } else {
                    this.innerHTML = eyeOpen;
                }
            });
        }
    </script>
</body>
</html>
