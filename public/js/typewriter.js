document.addEventListener("DOMContentLoaded", function () {
  const words = ["ideas.", "proyectos.", "tareas.", "recetas.", "notas.", "planes."];
  const el = document.getElementById("typewriter");

  if (!el) {
    console.error("Elemento typewriter no encontrado");
    return;
  }

  let wordIndex = 0;
  let charIndex = 0;
  let isDeleting = false;

  function type() {
    const currentWord = words[wordIndex];

    if (!isDeleting) {
      el.textContent = currentWord.substring(0, charIndex + 1);
      charIndex++;

      if (charIndex === currentWord.length) {
        setTimeout(() => {
          isDeleting = true;
          type();
        }, 2000);
      } else {
        setTimeout(type, 120);
      }
    } else {
      el.textContent = currentWord.substring(0, charIndex);
      charIndex--;

      if (charIndex < 0) {
        isDeleting = false;
        charIndex = 0;
        wordIndex = (wordIndex + 1) % words.length;
        setTimeout(type, 500);
      } else {
        setTimeout(type, 60);
      }
    }
  }

  type();
});
