<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noty - LogIn</title>
    <link rel="icon" type="image/x-icon" href="/../../assets/images/notyIcon.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="stylesheet" href="/../../css/root.css">
    <link rel="stylesheet" href="/../../css/register.css">
</head>

<body>
    <main class="main">
        <div class="register-form">
            <h2>Iniciar sesión</h2>
            <form method="POST" action="/login">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" id="password" placeholder="Contraseña" required>
                <button type="submit">Acceder</button>
            </form>
            <p>¿Olvidaste tu contraseña? <a href="/recuperar">Recuperar</a></p>
        </div>
    </main>
</body>

</html>