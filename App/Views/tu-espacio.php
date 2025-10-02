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
                <button class="add-notebook" id="add-notebook-button">
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
                <button class="add-note" id="add-note-button">
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

    <div id="notebook-modal" class="modal hidden">
        <div class="modal-content">
            <div class="modal-header">
                <div class="close-modal">
                    <span class="material-icons-round">close</span>
                </div>

                <h2>Nuevo cuaderno</h2>
            </div>

            <div class="body-modal">
                <form id="notebook-form">
                    <label for="name">Nombre:</label>
                    <input type="text" id="name" name="name" required />

                    <div class="color-picker">
                        <div class="color-red"></div>
                        <div class="color-blue"></div>
                        <div class="color-green"></div>
                        <div class="color-yellow"></div>
                        <div class="color-purple"></div>
                        <div class="color-pink"></div>
                        <div class="color-orange"></div>
                        <div class="color-brown"></div>
                        <div class="color-gray"></div>
                        <div class="color-custom"> <span class="material-icons-round">palette</span>
                        </div>
                    </div>
                    <button type="submit">Crear cuaderno</button>
                    <input type="hidden" name="color" id="selected-color" />
                </form>
            </div>
        </div>
    </div>


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
        // ===== VARIABLES GLOBALES =====
        const holderId = 'note-content-display';
        let editor = null; // Inicializar explícitamente como null
        let autosaveTimer;
        const AUTOSAVE_DELAY = 1500;

        // ===== FUNCIONES UTILITARIAS PARA EL EDITOR =====

        // Función para verificar si el editor es válido
        function isValidEditor(editorInstance) {
            return editorInstance &&
                typeof editorInstance === 'object' &&
                typeof editorInstance.destroy === 'function';
        }

        // Función para destruir el editor de forma segura
        function safeDestroyEditor() {
            if (isValidEditor(editor)) {
                try {
                    editor.destroy();
                } catch (error) {
                    console.warn('Error al destruir editor:', error);
                }
            }
            editor = null;
        }

        // Función helper para obtener datos válidos del editor
        function getValidEditorData(content) {
            if (!content || content.trim() === '') {
                return {
                    blocks: []
                };
            }

            try {
                const parsed = JSON.parse(content);
                return (parsed && Array.isArray(parsed.blocks)) ? parsed : {
                    blocks: []
                };
            } catch (error) {
                console.warn('Error al parsear contenido JSON:', error);
                return {
                    blocks: []
                };
            }
        }

        // ===== FUNCIONES PRINCIPALES =====

        // Inicialización del editor de texto
        function initEditor(content = null) {
            const holder = document.getElementById(holderId);

            if (!holder) {
                console.error('No se encontró el elemento holder para el editor');
                return;
            }

            holder.replaceChildren();
            safeDestroyEditor();

            try {
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
                    data: getValidEditorData(content),
                    onChange: () => triggerAutosave(),
                    onReady: () => {
                        console.log('Editor inicializado correctamente');
                    }
                });
            } catch (error) {
                console.error('Error al inicializar EditorJS:', error);
                editor = null;
            }
        }

        // Función para cerrar/limpiar la nota activa
        function closeActiveNote() {
            clearTimeout(autosaveTimer);

            // Remover clase activa de todas las notas
            document.querySelectorAll('.note-item').forEach(item => {
                item.classList.remove('note-item-active');
            });

            // Limpiar el título de la nota
            const titleContainer = document.querySelector('.title-note');
            if (titleContainer) {
                titleContainer.replaceChildren();
            }

            // Limpiar la información de la nota
            const noteNotebook = document.getElementById('note-notebook');
            const noteCreated = document.getElementById('note-created');
            const noteUpdated = document.getElementById('note-updated');

            if (noteNotebook) noteNotebook.textContent = '';
            if (noteCreated) noteCreated.textContent = '';
            if (noteUpdated) noteUpdated.textContent = '';

            // Destruir el editor de forma segura
            safeDestroyEditor();

            // Limpiar el holder
            const holder = document.getElementById(holderId);
            if (holder) {
                holder.replaceChildren();
            }
        }

        // Función de autoguardado
        function triggerAutosave() {
            clearTimeout(autosaveTimer);
            autosaveTimer = setTimeout(() => {
                const noteId = document.querySelector('.note-item-active')?.dataset.id;
                if (!noteId) {
                    console.warn('No hay nota activa para guardar');
                    return;
                }

                if (!isValidEditor(editor)) {
                    console.warn('Editor no válido, no se puede guardar');
                    return;
                }

                editor.save().then((outputData) => {
                    fetch('/save-note-content', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
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
                    console.error('Error al obtener contenido del editor:', error);
                });
            }, AUTOSAVE_DELAY);
        }

        // Función para cargar notas de un cuaderno
        function loadNotesForNotebook(notebookId) {
            closeActiveNote(); // Cerrar nota activa antes de cargar nuevas notas

            fetch(`/get-notes?notebook_id=${notebookId}`)
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('notes-container');
                    container.replaceChildren();

                    if (data.length === 0) {
                        const emptyMsg = document.createElement('p');
                        emptyMsg.classList.add('empty-message');
                        emptyMsg.textContent = 'No hay notas en este cuaderno.';
                        container.appendChild(emptyMsg);
                        return;
                    }

                    const fragment = document.createDocumentFragment();

                    data.forEach(note => {
                        const noteDiv = document.createElement('div');
                        noteDiv.classList.add('note-item');
                        noteDiv.dataset.id = note.id;

                        const icon = document.createElement('span');
                        icon.classList.add('material-icons-round');
                        icon.textContent = 'sticky_note_2';

                        const title = document.createElement('h3');
                        title.textContent = note.title;

                        noteDiv.appendChild(icon);
                        noteDiv.appendChild(title);
                        fragment.appendChild(noteDiv);
                    });

                    container.appendChild(fragment);
                })
                .catch(error => {
                    console.error('Error al cargar notas:', error);
                });
        }

        // Función para crear elemento de cuaderno
        function createNotebookElement(notebook) {
            const div = document.createElement('div');
            div.className = 'notebook-item';
            div.dataset.id = notebook.id;

            const icon = document.createElement('span');
            icon.className = 'material-icons-round';
            icon.style.color = notebook.color;
            icon.textContent = 'book';

            const title = document.createElement('span');
            title.className = 'notebook-name';
            title.textContent = notebook.title;

            div.appendChild(icon);
            div.appendChild(title);

            // Event listener para el cuaderno
            div.addEventListener('click', () => {
                // Remover clase activa de todos los cuadernos
                document.querySelectorAll('.notebook-item').forEach(el => {
                    el.classList.remove('notebook-item-active');
                });

                // Activar el cuaderno actual
                div.classList.add('notebook-item-active');

                // Cargar las notas del cuaderno
                loadNotesForNotebook(notebook.id);
            });

            return div;
        }

        // Función para formatear fechas
        function formatDateShort(dateString) {
            const date = new Date(dateString);
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = String(date.getFullYear()).slice(-2);
            return `${day}/${month}/${year}`;
        }

        // ===== EVENT LISTENERS =====

        // Event listener para cuadernos existentes al cargar la página
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.notebook-item').forEach(item => {
                item.addEventListener('click', () => {
                    const notebookId = item.dataset.id;

                    // Remover clase activa de todos los cuadernos
                    document.querySelectorAll('.notebook-item').forEach(el => {
                        el.classList.remove('notebook-item-active');
                    });

                    // Activar cuaderno actual
                    item.classList.add('notebook-item-active');

                    // Cargar notas
                    loadNotesForNotebook(notebookId);
                });
            });
        });

        // Event listener para cargar contenido de una nota
        document.addEventListener('click', (event) => {
            const noteItem = event.target.closest('.note-item');
            if (!noteItem) return;

            // Solo procesar si el click fue dentro del contenedor de notas
            const notesContainer = document.getElementById('notes-container');
            if (!notesContainer.contains(noteItem)) return;

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

                    // Inicializar editor con manejo de errores
                    try {
                        initEditor(data.content);
                    } catch (error) {
                        console.error('Error al inicializar editor:', error);
                        initEditor(); // Fallback a editor vacío
                    }

                    document.getElementById('note-notebook').textContent = `Cuaderno: ${data.notebook_title || 'Sin cuaderno'}`;
                    document.getElementById('note-created').textContent = `Creado: ${formatDateShort(data.created_at)}`;
                    document.getElementById('note-updated').textContent = `Última actualización: ${formatDateShort(data.updated_at)}`;
                })
                .catch(error => {
                    console.error('Error al cargar la nota:', error);
                });
        });

        document.querySelectorAll('.color-picker div').forEach(el => {
            el.addEventListener('click', () => {
                document.querySelectorAll('.color-picker div').forEach(c => c.classList.remove('selected'));
                el.classList.add('selected');

                // Extraer el color directamente del estilo (RGB. Pasar a hex)
                const selectedColor = window.getComputedStyle(el).backgroundColor;
                document.getElementById('selected-color').value = selectedColor;
            });
        });

        document.getElementById('add-notebook-button').addEventListener('click', () => {
            document.getElementById('notebook-modal').classList.remove('hidden');
        });

        document.querySelector('.close-modal').addEventListener('click', () => {
            document.getElementById('notebook-modal').classList.add('hidden');
        });

        document.getElementById('notebook-form').addEventListener('submit', e => {
            e.preventDefault();

            const name = document.getElementById('name').value.trim();
            const color = document.getElementById('selected-color').value.trim();

            if (!/^[\p{L}0-9_ ]+$/u.test(name)) {
                alert('Nombre inválido');
                return;
            }

            const match = color.match(/^rgb\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*\)$/i);
            if (!match || match.slice(1).some(n => n < 0 || n > 255)) {
                alert('Color inválido');
                return;
            }

            const formData = new FormData();
            formData.append('name', name);
            formData.append('color', color);

            fetch('/create-notebook', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.notebook) {
                        document.getElementById('notebook-modal').classList.add('hidden');
                        const container = document.querySelector('.notebooks-list');
                        const newNotebook = createNotebookElement(data.notebook);
                        container.appendChild(newNotebook);
                        document.getElementById('notebook-form').reset();
                        document.querySelectorAll('.color-picker div').forEach(c => c.classList.remove('selected'));
                    } else {
                        alert('Error al crear cuaderno: ' + (data.error || 'Respuesta inválida'));
                    }
                })
                .catch(err => {
                    console.error('Error de red:', err);
                    alert('No se pudo conectar con el servidor.');
                });
        });


        // Event listener para crear nota
        document.getElementById('add-note-button').addEventListener('click', () => {
            const activeNotebook = document.querySelector('.notebook-item-active');
            if (!activeNotebook) {
                alert('Selecciona un cuaderno primero.');
                return;
            }

            const notebookId = activeNotebook.dataset.id;
            const title = 'Nueva Nota';

            const formData = new FormData();
            formData.append('title', title.trim());
            formData.append('notebook_id', notebookId);

            fetch('/create-note', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.note) {
                        const container = document.getElementById('notes-container');

                        // Eliminar mensaje vacío si existe
                        const emptyMessage = container.querySelector('.empty-message');
                        if (emptyMessage) {
                            emptyMessage.remove();
                        }

                        // Crear la nueva nota
                        const noteDiv = document.createElement('div');
                        noteDiv.classList.add('note-item');
                        noteDiv.dataset.id = data.note.id;

                        const titleElem = document.createElement('h3');
                        titleElem.textContent = data.note.title;
                        noteDiv.appendChild(titleElem);

                        container.appendChild(noteDiv);
                    } else {
                        alert('Error al crear nota: ' + (data.error || 'Respuesta inválida'));
                    }
                })
                .catch(err => {
                    console.error('Error de red:', err);
                    alert('No se pudo conectar con el servidor.');
                });
        });
    </script>



</body>

</html>