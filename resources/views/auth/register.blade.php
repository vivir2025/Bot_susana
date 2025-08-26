<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }} - Registro Hospitalario</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1a73e8;
            --primary-dark: #0d47a1;
            --primary-light: #4285f4;
            --secondary-color: #f1f3f4;
            --accent-color: #34a853;
            --text-dark: #202124;
            --text-medium: #5f6368;
            --text-light: #80868b;
            --border-color: #dadce0;
            --success-color: #34a853;
            --error-color: #ea4335;
            --background-light: #f8f9fa;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Google Sans', Roboto, Arial, sans-serif;
            background-color: var(--background-light);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1.5;
            color: var(--text-dark);
        }

        .register-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            width: 100%;
            max-width: 480px;
            margin: 20px;
            border: 1px solid var(--border-color);
        }

        .register-header {
            background-color: white;
            padding: 40px 40px 20px;
            text-align: center;
            border-bottom: 1px solid var(--border-color);
        }

        .hospital-logo {
            width: 80px;
            height: 80px;
            margin-bottom: 15px;
            background-color: var(--primary-color);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
        }

        .register-header h1 {
            font-size: 24px;
            font-weight: 500;
            margin-bottom: 8px;
            color: var(--text-dark);
        }

        .register-header p {
            font-size: 15px;
            color: var(--text-medium);
        }

        .register-body {
            padding: 30px 40px 40px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-dark);
            font-size: 14px;
        }

        .input-container {
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: 13px 15px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 15px;
            transition: all 0.2s ease;
            background: white;
            height: 48px;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(26, 115, 232, 0.2);
        }

        .form-control.is-invalid {
            border-color: var(--error-color);
        }

        .invalid-feedback {
            color: var(--error-color);
            font-size: 13px;
            margin-top: 5px;
            display: block;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--text-light);
            font-size: 18px;
            background: none;
            border: none;
            padding: 5px;
        }

        .btn-register {
            width: 100%;
            padding: 12px;
            background-color: var(--primary-color);
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            height: 48px;
            margin: 25px 0 20px;
        }

        .btn-register:hover {
            background-color: var(--primary-dark);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .btn-register:active {
            background-color: var(--primary-dark);
        }

        .login-section {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
            font-size: 14px;
            color: var(--text-medium);
        }

        .login-section a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .login-section a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 14px;
            line-height: 1.4;
        }

        .alert-danger {
            background: #fce8e6;
            border: 1px solid #f5b7b1;
            color: var(--error-color);
        }

        .alert ul {
            margin: 8px 0 0 20px;
            padding: 0;
        }

        .password-strength {
            margin-top: 10px;
            font-size: 13px;
            color: var(--text-medium);
        }

        .strength-bar {
            height: 4px;
            background: var(--border-color);
            border-radius: 2px;
            overflow: hidden;
            margin-top: 8px;
        }

        .strength-fill {
            height: 100%;
            transition: all 0.3s ease;
            width: 0%;
        }

        .strength-weak { background: var(--error-color); width: 25%; }
        .strength-fair { background: #fbbc05; width: 50%; }
        .strength-good { background: #34a853; width: 75%; }
        .strength-strong { background: var(--success-color); width: 100%; }

        .strength-text {
            margin-top: 5px;
            font-size: 12px;
        }

        @media (max-width: 480px) {
            .register-container {
                margin: 10px;
                border: none;
                box-shadow: none;
            }
            
            .register-header, .register-body {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <div class="hospital-logo">
                <i class="fas fa-hospital"></i>
            </div>
            <h1>Dashboard Rips</h1>
            <p>Crear cuenta para acceso al dashboard</p>
        </div>

        <div class="register-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Datos incorrectos</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group">
                    <label for="name" class="form-label">Nombre completo</label>
                    <div class="input-container">
                        <input 
                            id="name" 
                            type="text" 
                            class="form-control @error('name') is-invalid @enderror" 
                            name="name" 
                            value="{{ old('name') }}" 
                            required 
                            autocomplete="name" 
                            autofocus
                            placeholder="Ej. Juan Pérez"
                        >
                    </div>
                    @error('name')
                        <span class="invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Correo </label>
                    <div class="input-container">
                        <input 
                            id="email" 
                            type="email" 
                            class="form-control @error('email') is-invalid @enderror" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autocomplete="email"
                            placeholder="usuario@gmail.com"
                        >
                    </div>
                    @error('email')
                        <span class="invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Contraseña</label>
                    <div class="input-container">
                        <input 
                            id="password" 
                            type="password" 
                            class="form-control @error('password') is-invalid @enderror" 
                            name="password" 
                            required 
                            autocomplete="new-password"
                            placeholder="Mínimo 8 caracteres"
                            oninput="checkPasswordStrength(this.value)"
                        >
                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                            <i class="fas fa-eye" id="password-icon"></i>
                        </button>
                    </div>
                    <div class="password-strength">
                        <div class="strength-bar">
                            <div class="strength-fill" id="strength-fill"></div>
                        </div>
                        <div class="strength-text" id="strength-text">Seguridad de la contraseña</div>
                    </div>
                    @error('password')
                        <span class="invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password-confirm" class="form-label">Confirmar contraseña</label>
                    <div class="input-container">
                        <input 
                            id="password-confirm" 
                            type="password" 
                            class="form-control" 
                            name="password_confirmation" 
                            required 
                            autocomplete="new-password"
                            placeholder="Repite tu contraseña"
                        >
                        <button type="button" class="password-toggle" onclick="togglePassword('password-confirm')">
                            <i class="fas fa-eye" id="password-confirm-icon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-register">
                    <i class="fas fa-user-plus"></i> Registrar cuenta
                </button>

                <div class="login-section">
                    ¿Ya tienes cuenta? <a href="{{ route('login') }}">Iniciar sesión</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const passwordIcon = document.getElementById(fieldId + '-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            }
        }

        function checkPasswordStrength(password) {
            const strengthFill = document.getElementById('strength-fill');
            const strengthText = document.getElementById('strength-text');
            
            // Reset classes
            strengthFill.className = 'strength-fill';
            
            if (password.length === 0) {
                strengthText.textContent = 'Seguridad de la contraseña';
                return;
            }
            
            let strength = 0;
            
            // Criterios de fortaleza
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^A-Za-z0-9]/)) strength++;
            
            switch (strength) {
                case 0:
                case 1:
                    strengthFill.classList.add('strength-weak');
                    strengthText.textContent = 'Muy débil - No cumple los requisitos';
                    strengthText.style.color = 'var(--error-color)';
                    break;
                case 2:
                    strengthFill.classList.add('strength-fair');
                    strengthText.textContent = 'Débil - Deberías mejorarla';
                    strengthText.style.color = '#fbbc05';
                    break;
                case 3:
                case 4:
                    strengthFill.classList.add('strength-good');
                    strengthText.textContent = 'Buena - Cumple los requisitos';
                    strengthText.style.color = '#34a853';
                    break;
                case 5:
                    strengthFill.classList.add('strength-strong');
                    strengthText.textContent = 'Muy fuerte - Excelente seguridad';
                    strengthText.style.color = 'var(--success-color)';
                    break;
            }
        }
    </script>
</body>
</html>