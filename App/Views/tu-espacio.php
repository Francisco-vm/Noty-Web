<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noty - Inicio</title>
    <link rel="icon" type="image/x-icon" href="/../../assets/icons/stickyNote.svg">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Icons+Round" />
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
                <?php foreach ($Notebooks as $notebook): ?>
                    <div class="notebook-item" data-id="<?= htmlspecialchars($notebook['id']) ?>">
                        <span class="material-icons-round"
                            style="color: <?= htmlspecialchars($notebook['color']) ?>;">book</span>
                        <span class="notebook-name"><?= htmlspecialchars($notebook['title']) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="actions">
                <a href="/logout">Cerrar sesión</a>
            </div>
        </div>

        <div class="note-panel">
            <div class="head-notes">
                <h2>Notas</h2>
                <button class="add-note">
                    +
                </button>
            </div>

            <div class="search-filters">
            </div>

            <div class="notes-list" id="notes-container">
            </div>
        </div>

        <div class="content">
            <div class="title-note">

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



    <script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/header@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/list@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/quote@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/checklist@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/delimiter@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/table@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/underline@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/inline-code@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/editorjs-color-picker@1.0.8/dist/index.umd.js"></script>


    

    <script>
        
        const holderId = 'note-content-display';
        let editor;
        let autosaveTimer;
        const AUTOSAVE_DELAY = 1500;

        // Inicializacion del editor de texto //

        function initEditor(content = null) {
            const holder = document.getElementById(holderId);
            holder.replaceChildren();

            if (editor) {
                editor.destroy();
            }

            editor = new EditorJS({
                holder: holderId,
                autofocus: true,
                tools: {
                    header: Header,
                    table: Table,
                    inlineCode: InlineCode,
                    underline: Underline,
                    list: EditorjsList,
                    quote: Quote,
                    checklist: Checklist,
                    delimiter: Delimiter,
                    ColorPicker: {
                        class: window.ColorPicker.ColorPickerWithoutSanitize,
                    },
                },
                data: content ? JSON.parse(content) : { blocks: [] },
                onChange: () => triggerAutosave()
            });
        }

        // Funcion de autoguardado cada 1.5 segundos //
        function triggerAutosave() {
            clearTimeout(autosaveTimer);
            autosaveTimer = setTimeout(() => {
                const noteId = document.querySelector('.note-item-active')?.dataset.id;
                if (!noteId) {
                    console.warn('No hay nota activa para guardar');
                    return;
                }

                editor.save().then((outputData) => {
                    fetch('/save-note-content', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            note_id: noteId,
                            content: JSON.stringify(outputData)
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log('Nota guardada automáticamente');
                            } else {
                                console.warn('Error al guardar:', data.error);
                            }
                        })
                        .catch(error => {
                            console.error('Error de red al guardar:', error);
                        });
                }).catch((error) => {
                    console.error('Error al obtener contenido:', error);
                });
            }, AUTOSAVE_DELAY);
        }

        // Script para cargar las notas que pertenecen a un cuaderno //
        document.querySelectorAll('.notebook-item').forEach(item => {
            item.addEventListener('click', () => {
                const notebookId = item.dataset.id;

                document.querySelectorAll('.notebook-item').forEach(el => {
                    el.classList.remove('notebook-item-active');
                });

                item.classList.add('notebook-item-active');

                fetch(`/get-notes?notebook_id=${notebookId}`)
                    .then(response => response.json())
                    .then(data => {
                        const container = document.getElementById('notes-container');
                        container.replaceChildren();

                        if (data.length === 0) {
                            const emptyMsg = document.createElement('p');
                            emptyMsg.textContent = 'No hay notas en este cuaderno.';
                            container.appendChild(emptyMsg);
                            return;
                        }

                        const fragment = document.createDocumentFragment();

                        data.forEach(note => {
                            const noteDiv = document.createElement('div');
                            noteDiv.classList.add('note-item');
                            noteDiv.dataset.id = note.id;

                            const title = document.createElement('h3');
                            title.textContent = note.title;

                            noteDiv.appendChild(title);
                            fragment.appendChild(noteDiv);
                        });

                        container.appendChild(fragment);
                    })
                    .catch(error => {
                        console.error('Error al cargar notas:', error);
                    });
            });
        });


        // Script para cargar el contenido de una nota //
        document.getElementById('notes-container').addEventListener('click', (event) => {
            const noteItem = event.target.closest('.note-item');
            if (!noteItem) return;

            document.querySelectorAll('.note-item').forEach(item => {
                item.classList.remove('note-item-active');
            });
            noteItem.classList.add('note-item-active');

            const noteId = noteItem.dataset.id;
            if (!noteId || isNaN(noteId)) {
                console.warn('ID de nota inválido');
                return;
            }

            fetch(`/get-note-content?note_id=${noteId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error('Error del servidor:', data.error);
                        return;
                    }

                    const titleContainer = document.querySelector('.title-note');
                    titleContainer.replaceChildren();
                    const titleElement = document.createElement('h1');
                    titleElement.textContent = data.title;
                    titleContainer.appendChild(titleElement);

                    initEditor(data.content);

                    function formatDateShort(dateString) {
                        const date = new Date(dateString);
                        const day = String(date.getDate()).padStart(2, '0');
                        const month = String(date.getMonth() + 1).padStart(2, '0');
                        const year = String(date.getFullYear()).slice(-2);
                        return `${day}/${month}/${year}`;
                    }

                    document.getElementById('note-notebook').textContent = `Cuaderno: ${data.notebook_title || 'Sin cuaderno'}`;
                    document.getElementById('note-created').textContent = `Creado: ${formatDateShort(data.created_at)}`;
                    document.getElementById('note-updated').textContent = `Última actualización: ${formatDateShort(data.updated_at)}`;

                })
                .catch(error => {
                    console.error('Error al cargar la nota:', error);
                });
        });
    </script>

</body>

</html>