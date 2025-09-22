<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noty - SignIn</title>
    <link rel="icon" type="image/x-icon" href="/../../assets/images/notyIcon.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="stylesheet" href="/../../css/root.css">
    <link rel="stylesheet" href="/../../css/register.css">
</head>

<body>
    <main class="main">
        <div class="register-form">
            <h2>Crear cuenta</h2>
            <form method="POST" action="/register">
                <input type="email" name="email" placeholder="Email" required>
                <input type="username" name="username" placeholder="Nombre " required>
                <input type="password" name="password" id="password" placeholder="Contraseña" required>
                <div id="strength-bar"></div>
                <small id="password-hint"
                    style="display:block; margin-top:5px; margin-bottom:10px; text-align: center; color:#888;">
                    Mín. 8 caracteres, 1 Mayúscula, 1 número y 1 símbolo.
                </small>
                <button type="submit">Registrarse</button>
            </form>
            <p>¿Ya tienes una cuenta? <a href="/login">Iniciar sesión</a></p>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');
            const button = form.querySelector('button');
            const originalText = button.textContent;

            form.addEventListener('submit', function (e) {
                button.textContent = 'Creando cuenta...';
                button.disabled = true;
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const passwordInput = document.getElementById("password");
            const strengthBar = document.getElementById("strength-bar");

            passwordInput.addEventListener("input", function () {
                const val = passwordInput.value;
                let strength = 0;

                if (val.length >= 8) strength++;
                if (/[A-Z]/.test(val)) strength++;
                if (/[0-9]/.test(val)) strength++;
                if (/[^A-Za-z0-9]/.test(val)) strength++;

                strengthBar.classList.remove("strength-1", "strength-2", "strength-3", "strength-4");

                if (strength > 0) {
                    strengthBar.classList.add(`strength-${strength}`);
                    strengthBar.style.width = `${strength * 25}%`;
                } else {
                    strengthBar.style.width = "0%";
                }
            });
        });
    </script>

</body>

</html>