<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siklus - Create an Account</title>
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

        .register-container {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 45px 60px;
            border-radius: 8px;
            width: 100%;
            max-width: 480px;
            z-index: 1;
        }

        .register-container h2 {
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
        input[type="password"],
        select {
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

        select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23333' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 1.5em;
            padding-right: 38px;
        }

        .row {
            display: flex;
            gap: 12px;
        }

        .row select,
        .row input {
            flex: 1;
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

        .checkbox-container {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-top: 5px;
            font-size: 14px;
        }

        .checkbox-container input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .checkbox-container label {
            color: #cccccc;
            line-height: 1.4;
            cursor: pointer;
        }

        .checkbox-container label a {
            color: white;
            text-decoration: none;
            font-weight: 700;
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

        .login-link {
            text-align: center;
            margin-top: 15px;
            font-size: 16px;
            color: #cccccc;
        }

        .login-link a {
            color: white;
            text-decoration: none;
            font-weight: 700;
            border-bottom: 1px solid white;
            padding-bottom: 1px;
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <img src="{{ asset('images/siklus.png') }}" alt="Siklus Logo" class="logo">

    <div class="register-container">
        <h2>Create an Account</h2>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="error-message">{{ $error }}</div>
            @endforeach
        @endif

        <form action="{{ route('register.submit') }}" method="POST">
            @csrf
            <input type="text" name="name" placeholder="Name" value="{{ old('name') }}" required>
            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>

            <div class="password-wrapper">
                <input type="password" name="password" placeholder="Password" id="password" required>
                <span class="password-toggle-icon" id="togglePassword" style="display: flex; align-items: center; justify-content: center; height: 100%;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#777" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                </span>
            </div>

            <div class="password-wrapper">
                <input type="password" name="password_confirmation" placeholder="Confirm Password" id="password_confirmation" required>
                <span class="password-toggle-icon" id="togglePasswordConfirm" style="display: flex; align-items: center; justify-content: center; height: 100%;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#777" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                </span>
            </div>

            <div class="row">
                <select name="province" required>
                    <option value="" disabled selected>Province</option>
                    <option value="Jawa Barat">Jawa Barat</option>
                    <option value="Jawa Tengah">Jawa Tengah</option>
                    <option value="Jawa Timur">Jawa Timur</option>
                    <option value="DKI Jakarta">DKI Jakarta</option>
                    <option value="Yogyakarta">Yogyakarta</option>
                    <option value="Banten">Banten</option>
                    <option value="Sumatera Utara">Sumatera Utara</option>
                    <option value="Sumatera Barat">Sumatera Barat</option>
                    <option value="Riau">Riau</option>
                    <option value="Jambi">Jambi</option>
                    <option value="Sumatera Selatan">Sumatera Selatan</option>
                    <option value="Lampung">Lampung</option>
                    <option value="Kalimantan Barat">Kalimantan Barat</option>
                    <option value="Kalimantan Tengah">Kalimantan Tengah</option>
                    <option value="Kalimantan Selatan">Kalimantan Selatan</option>
                    <option value="Kalimantan Timur">Kalimantan Timur</option>
                    <option value="Sulawesi Utara">Sulawesi Utara</option>
                    <option value="Sulawesi Tengah">Sulawesi Tengah</option>
                    <option value="Sulawesi Selatan">Sulawesi Selatan</option>
                    <option value="Sulawesi Tenggara">Sulawesi Tenggara</option>
                    <option value="Bali">Bali</option>
                    <option value="Nusa Tenggara Barat">Nusa Tenggara Barat</option>
                    <option value="Nusa Tenggara Timur">Nusa Tenggara Timur</option>
                    <option value="Papua Barat">Papua Barat</option>
                    <option value="Papua">Papua</option>
                </select>
                <input type="text" name="city" placeholder="City" value="{{ old('city') }}" required>
            </div>

            <div class="checkbox-container">
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">I agree to the <a href="#">Terms & Conditions</a></label>
            </div>

            <button type="submit">Create Account</button>
        </form>

        <p class="login-link">Already have an account? <a href="{{ route('login') }}">Log In</a></p>
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

        const togglePasswordConfirm = document.querySelector('#togglePasswordConfirm');
        const passwordConfirm = document.querySelector('#password_confirmation');

        if (togglePasswordConfirm) {
            togglePasswordConfirm.addEventListener('click', function (e) {
                const type = passwordConfirm.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordConfirm.setAttribute('type', type);

                if (passwordConfirm.getAttribute('type') === 'text') {
                    this.innerHTML = eyeClosed;
                } else {
                    this.innerHTML = eyeOpen;
                }
            });
        }
    </script>
</body>
</html>
