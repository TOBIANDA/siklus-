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

        /* Sembunyikan icon mata bawaan browser (Edge, Chrome) */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear,
        input[type="password"]::-webkit-credentials-auto-fill-button {
            display: none !important;
            visibility: hidden;
            pointer-events: none;
        }
        input[type="password"] {
            -webkit-appearance: none;
        }

        .error-message {
            color: #ff6b6b;
            font-size: 14px;
            margin-top: -8px;
            margin-bottom: 4px;
        }

        .inline-error {
            color: #ff6b6b;
            font-size: 13px;
            margin-top: 4px;
            display: none;
            align-items: center;
            gap: 5px;
        }
        .inline-error.show { display: flex; }

        .input-error {
            border: 2px solid #ff6b6b !important;
            background-color: #fff0f0 !important;
            animation: shake .35s ease;
        }
        @keyframes shake {
            0%,100% { transform: translateX(0); }
            20%      { transform: translateX(-6px); }
            40%      { transform: translateX(6px); }
            60%      { transform: translateX(-4px); }
            80%      { transform: translateX(4px); }
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
    <img src="{{ asset('images/sikklus.png') }}" alt="Siklus Logo" class="logo">

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

        <form action="{{ route('login.submit') }}" method="POST" id="loginForm" novalidate>
            @csrf
            <div>
                <input type="email" name="email" id="loginEmail" placeholder="Email" value="{{ old('email') }}" autocomplete="email">
                <p class="inline-error" id="emailError">⚠ Email tidak boleh kosong</p>
            </div>
            <div class="password-wrapper">
                <input type="password" name="password" placeholder="Password" id="password" autocomplete="current-password">
                <span class="password-toggle-icon" id="togglePassword" style="display: flex; align-items: center; justify-content: center; height: 100%;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#777" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                </span>
            </div>
            <p class="inline-error" id="passwordError">⚠ Password tidak boleh kosong</p>
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
                this.innerHTML = password.getAttribute('type') === 'text' ? eyeClosed : eyeOpen;
            });
        }

        // ===== CLIENT-SIDE VALIDATION =====
        const loginForm   = document.getElementById('loginForm');
        const emailInput  = document.getElementById('loginEmail');
        const pwdInput    = document.getElementById('password');
        const emailError  = document.getElementById('emailError');
        const pwdError    = document.getElementById('passwordError');

        function setError(input, errorEl, msg) {
            input.classList.add('input-error');
            errorEl.textContent = '⚠ ' + msg;
            errorEl.classList.add('show');
        }
        function clearError(input, errorEl) {
            input.classList.remove('input-error');
            errorEl.classList.remove('show');
        }

        // Clear on type
        emailInput.addEventListener('input', () => clearError(emailInput, emailError));
        pwdInput.addEventListener('input',   () => clearError(pwdInput, pwdError));

        loginForm.addEventListener('submit', function (e) {
            let valid = true;

            if (!emailInput.value.trim()) {
                setError(emailInput, emailError, 'Email tidak boleh kosong');
                valid = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value.trim())) {
                setError(emailInput, emailError, 'Format email tidak valid');
                valid = false;
            } else {
                clearError(emailInput, emailError);
            }

            if (!pwdInput.value) {
                setError(pwdInput, pwdError, 'Password tidak boleh kosong');
                valid = false;
            } else {
                clearError(pwdInput, pwdError);
            }

            if (!valid) e.preventDefault();
        });
    </script>
</body>
</html>
