<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - SIPENGMAS</title>
    <link rel="icon" href="{{ asset('images/cropped_circle_sipengmas.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 1rem;
        }

        .login-card {
            background-color: white;
            padding: 2.5rem 3rem;
            border-radius: 1rem;
            box-shadow: 0 10px 25px -5px rgb(0 0 0 / 0.1);
            width: 100%;
            max-width: 480px;
        }

        .logo {
            width: 90px;
            height: 90px;
            margin-bottom: 1.5rem;
        }

        .login-card h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.5rem;
        }

        .login-card .subtitle {
            color: #6b7280;
            margin-bottom: 2rem;
        }

        .login-form {
            text-align: left;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #374151;
        }

        .form-control {
            width: 100%;
            padding: 0.85rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            box-sizing: border-box;
        }

        .form-control:focus {
            outline: none;
            border-color: #8BC3B4;
            box-shadow: 0 0 0 3px rgba(139, 195, 180, 0.3);
        }

        .password-input-wrapper {
            position: relative;
        }

        .form-control[type="password"] {
            padding-right: 45px !important;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6b7280;
        }

        .form-check {
            display: flex;
            align-items: center;
            justify-content: flex-start;
        }

        .form-check-input {
            margin-right: 0.5rem;
        }

        .btn {
            width: 100%;
            padding: 0.85rem;
            border: none;
            border-radius: 0.5rem;
            color: #FFFFFF;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
        }

        .btn-primary {
            background-color: #8BC3B4;
        }

        .btn-primary:hover {
            background-color: #7aa899;
        }

        .footer-text {
            text-align: center;
            margin-top: 1.5rem;
            color: #6b7280;
            font-size: 0.5rem;
        }
    </style>
</head>

<body>
    <div>
        {{ $slot }}
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.querySelector('.toggle-password');
            if (togglePassword) {
                togglePassword.addEventListener('click', function(e) {
                    const passwordInput = document.getElementById('password');
                    const eyeOpen = this.querySelector('.eye-open');
                    const eyeSlash = this.querySelector('.eye-slash');

                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);

                    if (type === 'password') {
                        eyeOpen.style.display = 'block';
                        eyeSlash.style.display = 'none';
                    } else {
                        eyeOpen.style.display = 'none';
                        eyeSlash.style.display = 'block';
                    }
                });
            }
        });
    </script>
</body>

</html>
