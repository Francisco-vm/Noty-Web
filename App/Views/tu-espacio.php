<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noty - Inicio</title>
    <link rel="icon" type="image/x-icon" href="/../../assets/icons/stickyNote.svg">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="stylesheet" href="/../../css/root.css">
    <link rel="stylesheet" href="/../../css/space.css">
</head>

<body>
    <main class="main">

        <div class="notebooks-panel">
            <div class="head-notebooks">
                <h2>Cuadernos</h2>
                <button class="add-notebook">
                    +
                </button>
            </div>

            <div class="notebooks-list">
            </div>

            <div class="actions">
            </div>
        </div>

        <div class="note-panel">
            <div class="head-notes">
                <h2>Notas</h2>
            </div>

            <div class="search-filters">
            </div>

            <div class="notes-list" id="notes-container">
            </div>
        </div>

        <div class="content">
            <div class="title-note">
                <h1 id="note-title"></h1>
            </div>
            <div class="note-content">
                <div id="note-content-display">
                </div>
            </div>

            <div class="info-note">
                <div class="notebook-pattern">
                    <span id="note-notebook"></span>
                </div>
                <div class="created-at">
                    <span id="note-created"></span>
                </div>
                <div class="last-updated">
                    <span id="note-updated"></span>
                </div>
            </div>
        </div>

    </main>

    <a href="/logout">Cerrar sesión</a>
</body>

</html>