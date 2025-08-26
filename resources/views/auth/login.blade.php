<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }} - Acceso Hospitalario</title>
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

        .login-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
            margin: 20px;
            border: 1px solid var(--border-color);
        }

        .login-header {
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

        .login-header h1 {
            font-size: 24px;
            font-weight: 500;
            margin-bottom: 8px;
            color: var(--text-dark);
        }

        .login-header p {
            font-size: 15px;
            color: var(--text-medium);
        }

        .login-body {
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

        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 25px 0;
        }

        .form-check {
            display: flex;
            align-items: center;
        }

        .form-check-input {
            margin-right: 8px;
            width: 16px;
            height: 16px;
        }

        .form-check-label {
            font-size: 14px;
            color: var(--text-medium);
        }

        .forgot-password {
            font-size: 14px;
        }

        .forgot-password a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        .btn-login {
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
            margin-bottom: 20px;
        }

        .btn-login:hover {
            background-color: var(--primary-dark);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .btn-login:active {
            background-color: var(--primary-dark);
        }

        .register-section {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
            font-size: 14px;
            color: var(--text-medium);
        }

        .register-section a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .register-section a:hover {
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

        .alert-success {
            background: #e6f4ea;
            border: 1px solid #b7e1c3;
            color: var(--success-color);
        }

        .alert ul {
            margin: 8px 0 0 20px;
            padding: 0;
        }

        @media (max-width: 480px) {
            .login-container {
                margin: 10px;
                border: none;
                box-shadow: none;
            }
            
            .login-header, .login-body {
                padding: 30px 20px;
            }
            
            .form-footer {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="hospital-logo">
                <i class="fas fa-hospital"></i>
            </div>
            <h1>Dashboard Rips</h1>
            <p>Acceso a graficas de rips hospitalarios</p>
        </div>

        <div class="login-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Credenciales incorrectas</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

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
                            autofocus
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
                            autocomplete="current-password"
                            placeholder="••••••••"
                        >
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye" id="password-icon"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-footer">
                    <div class="form-check">
                        <input 
                            class="form-check-input" 
                            type="checkbox" 
                            name="remember" 
                            id="remember" 
                            {{ old('remember') ? 'checked' : '' }}
                        >
                        <label class="form-check-label" for="remember">
                            Mantener sesión
                        </label>
                    </div>

                </div>

                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Iniciar sesión
                </button>

                <div class="register-section">
                    ¿Nuevo en el sistema? <a href="{{ route('register') }}">Registrarse</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('password-icon');
            
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
    </script>
</body>
</html>