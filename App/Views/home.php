<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noty - Inicio</title>
    <link rel="icon" type="image/x-icon" href="/../../assets/images/notyIcon.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="stylesheet" href="/../../css/root.css">
    <link rel="stylesheet" href="/../../css/home.css">
</head>

<body>
    <header class="top-bar">
        <nav class="auth-links">
            <a href="/login" class="btn">Iniciar sesión</a>
            <a href="/register" class="btn btn-accent">Registrarse</a>
        </nav>
    </header>

    <main class="main">
        <section class="hero">
            <div class="hero-icon">
                <img src="/../../assets/icons/stickyNote.svg" alt="Icon Noty" width="128" height="128">
            </div>
            <h1>Bienvenido a Noty</h1>
            <div class="writer-container">
                <span class="writer" id="typewriter"></span>
                <span class="ghost" id="ghost"></span>
            </div>
            <div class="hero-actions">
                <a href="/register" class="btn btn-accent">Comenzar</a>
                <a href="/about" class="btn">Saber más</a>
            </div>
        </section>

        <section class="cards">
            <div class="card">
                <div class="icon">
                    <span class="material-symbols-outlined">book</span>
                </div>
                <h2>Crea Cuadernos</h2>
                <p>Agrupa tus notas en cuadernos para mantener todo perfectamente ordenado.</p>
            </div>

            <div class="card">
                <div class="icon">
                    <span class="material-symbols-outlined">search</span>
                </div>
                <h2>Búsqueda Inteligente</h2>
                <p>Encuentra cualquier nota al instante con la búsqueda avanzada y filtrado.</p>
            </div>

            <div class="card">
                <div class="icon">
                    <span class="material-symbols-outlined">bolt</span>
                </div>
                <h2>Rápida y Sencilla</h2>
                <p>Diseño minimalista y optimizado que te permite concentrarte en lo importante.</p>
            </div>
        </section>

        <footer class="footer-links">
            <p>© 2025 Noty · <a href="/about" class="link">Acerca</a></p>
        </footer>
    </main>

    <script src="/js/typewriter.js" defer></script>
</body>
</html>