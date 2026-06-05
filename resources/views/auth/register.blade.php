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
        .inline-error {
            color: #ff6b6b;
            font-size: 13px;
            margin-top: 4px;
            margin-bottom: 0;
            display: none;
            align-items: center;
            gap: 4px;
        }
        .inline-error.show { display: flex; }

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

        <form action="{{ route('register.submit') }}" method="POST" id="registerForm" novalidate>
            @csrf
            <div>
                <input type="text" name="name" id="regName" placeholder="Name" value="{{ old('name') }}">
                <p class="inline-error" id="nameError">⚠ Nama tidak boleh kosong</p>
            </div>
            <div>
                <input type="email" name="email" id="regEmail" placeholder="Email" value="{{ old('email') }}">
                <p class="inline-error" id="emailInlineError">⚠ Email tidak boleh kosong</p>
            </div>
            <div class="error-message" id="emailWarning" style="display:none; margin-top: -4px;">Email harus memiliki domain yang valid (contoh: .com, .id, .co.id)</div>

            <div class="password-wrapper">
                <input type="password" name="password" placeholder="Password" id="password">
                <span class="password-toggle-icon" id="togglePassword" style="display: flex; align-items: center; justify-content: center; height: 100%;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#777" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                </span>
            </div>
            <p class="inline-error" id="passwordError">⚠ Password tidak boleh kosong</p>

            <div class="password-wrapper">
                <input type="password" name="password_confirmation" placeholder="Confirm Password" id="password_confirmation">
                <span class="password-toggle-icon" id="togglePasswordConfirm" style="display: flex; align-items: center; justify-content: center; height: 100%;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#777" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                </span>
            </div>
            <p class="inline-error" id="confirmError">⚠ Konfirmasi password tidak boleh kosong</p>

            <div class="row">
                <select name="province" id="regProvince" required>
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
                <input type="text" name="city" id="regCity" placeholder="City" value="{{ old('city') }}">
            </div>
            <p class="inline-error" id="locationError" style="margin-top:4px;">⚠ Provinsi dan kota wajib diisi</p>

            <div class="checkbox-container">
                <input type="checkbox" id="terms" name="terms">
                <label for="terms">I agree to the <a href="#">Terms &amp; Conditions</a></label>
            </div>
            <p class="inline-error" id="termsError">⚠ Anda harus menyetujui syarat dan ketentuan</p>

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

        // Email validation for TLD
        const emailInput = document.querySelector('input[name="email"]');
        const emailWarning = document.querySelector('#emailWarning');
        if (emailInput) {
            emailInput.addEventListener('blur', function() {
                const email = this.value.trim();
                const hasValidTLD = /\.(com|co\.id|id|org|net|edu|io)$/i.test(email);
                if (email && !hasValidTLD) {
                    emailWarning.style.display = 'block';
                } else {
                    emailWarning.style.display = 'none';
                }
            });
        }

        // ===== CLIENT-SIDE VALIDATION =====
        function setError(inputEl, errorEl, msg) {
            if (inputEl) inputEl.classList.add('input-error');
            if (errorEl) { errorEl.textContent = '⚠ ' + msg; errorEl.classList.add('show'); }
        }
        function clearError(inputEl, errorEl) {
            if (inputEl) inputEl.classList.remove('input-error');
            if (errorEl) errorEl.classList.remove('show');
        }

        // Clear on input
        const fieldMap = [
            { input: document.getElementById('regName'),     error: document.getElementById('nameError') },
            { input: document.getElementById('regEmail'),    error: document.getElementById('emailInlineError') },
            { input: document.getElementById('password'),    error: document.getElementById('passwordError') },
            { input: document.getElementById('password_confirmation'), error: document.getElementById('confirmError') },
            { input: document.getElementById('regCity'),     error: document.getElementById('locationError') },
            { input: document.getElementById('regProvince'), error: document.getElementById('locationError') },
        ];
        fieldMap.forEach(({ input, error }) => {
            if (input) input.addEventListener('input', () => clearError(input, error));
            if (input) input.addEventListener('change', () => clearError(input, error));
        });

        document.getElementById('registerForm').addEventListener('submit', function (e) {
            let valid = true;

            const name     = document.getElementById('regName');
            const email    = document.getElementById('regEmail');
            const pwd      = document.getElementById('password');
            const pwdConf  = document.getElementById('password_confirmation');
            const province = document.getElementById('regProvince');
            const city     = document.getElementById('regCity');
            const terms    = document.getElementById('terms');

            const nameErr     = document.getElementById('nameError');
            const emailErr    = document.getElementById('emailInlineError');
            const pwdErr      = document.getElementById('passwordError');
            const confirmErr  = document.getElementById('confirmError');
            const locationErr = document.getElementById('locationError');
            const termsErr    = document.getElementById('termsError');

            // Name
            if (!name.value.trim()) {
                setError(name, nameErr, 'Nama tidak boleh kosong');
                valid = false;
            } else { clearError(name, nameErr); }

            // Email
            if (!email.value.trim()) {
                setError(email, emailErr, 'Email tidak boleh kosong');
                valid = false;
            } else { clearError(email, emailErr); }

            // Password
            if (!pwd.value) {
                setError(pwd, pwdErr, 'Password tidak boleh kosong');
                valid = false;
            } else { clearError(pwd, pwdErr); }

            // Confirm Password
            if (!pwdConf.value) {
                setError(pwdConf, confirmErr, 'Konfirmasi password tidak boleh kosong');
                valid = false;
            } else if (pwdConf.value !== pwd.value) {
                setError(pwdConf, confirmErr, 'Password dan konfirmasi tidak cocok');
                valid = false;
            } else { clearError(pwdConf, confirmErr); }

            // Province & City
            if (!province.value || !city.value.trim()) {
                if (!province.value) province.classList.add('input-error');
                if (!city.value.trim()) city.classList.add('input-error');
                locationErr.classList.add('show');
                locationErr.textContent = '⚠ Provinsi dan kota wajib diisi';
                valid = false;
            } else {
                province.classList.remove('input-error');
                city.classList.remove('input-error');
                locationErr.classList.remove('show');
            }

            // Terms
            if (!terms.checked) {
                termsErr.classList.add('show');
                valid = false;
            } else {
                termsErr.classList.remove('show');
            }

            if (!valid) {
                e.preventDefault();
                // Scroll to first error
                const firstErr = document.querySelector('.input-error, .inline-error.show');
                if (firstErr) firstErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    </script>
</body>
</html>
