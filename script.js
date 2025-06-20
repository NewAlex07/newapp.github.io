// Lógica para el menú lateral (sidebar) y la búsqueda móvil
const body = document.querySelector("body");
const menuButton = document.querySelector(".menu-button");
const screenOverlay = document.querySelector(".screen-overlay");
const searchButton = document.getElementById("search-button");
const searchBackButton = document.getElementById("search-back-button");

// Toggle para el menú lateral
if(menuButton && screenOverlay) {
  menuButton.addEventListener("click", () => {
    body.classList.toggle("sidebar-hidden");
  });
  
  screenOverlay.addEventListener("click", () => {
    body.classList.add("sidebar-hidden");
  });
}

// Toggle para la búsqueda en móviles
if(searchButton && searchBackButton) {
  searchButton.addEventListener("click", () => {
    body.classList.add("show-mobile-search");
  });
  
  searchBackButton.addEventListener("click", () => {
    body.classList.remove("show-mobile-search");
  });
}


// -----------------------------------------------------------------
// --- LÓGICA CORREGIDA PARA EL MENÚ DE USUARIO PERSONALIZADO ---
// -----------------------------------------------------------------

// --- LÓGICA DEL MENÚ DE USUARIO ---
const userMenuButton = document.getElementById('user-menu-button');
const userMenu = document.getElementById('user-menu');

// Guardián: Solo ejecuta si ambos elementos existen
if (userMenuButton && userMenu) {
  userMenuButton.addEventListener('click', (e) => {
    e.stopPropagation(); 
    userMenu.classList.toggle('active');
  });

  document.addEventListener('click', (e) => {
    if (userMenu.classList.contains('active')) {
      if (!userMenu.contains(e.target) && !userMenuButton.contains(e.target)) {
        userMenu.classList.remove('active');
      }
    }
  });
}

// --- LÓGICA PARA EL MODO OSCURO (DARK MODE) ---

document.addEventListener('DOMContentLoaded', () => {
    const themeButton = document.querySelector(".theme-button");
    const body = document.querySelector("body");

    // Guardián: Solo ejecuta si el botón de tema existe
    if (themeButton && body) {
        
        // Función para cambiar el ícono del botón
        const updateThemeIcon = () => {
            if (body.classList.contains("dark-mode")) {
                themeButton.innerHTML = '<i class="uil uil-sun"></i>'; // Cambia a ícono de sol
            } else {
                themeButton.innerHTML = '<i class="uil uil-moon"></i>'; // Cambia a ícono de luna
            }
        };

        // 1. Comprobar si hay una preferencia guardada en localStorage
        const currentTheme = localStorage.getItem("theme");
        if (currentTheme === "dark") {
            body.classList.add("dark-mode");
        }
        
        // Actualizar el ícono al cargar la página
        updateThemeIcon();

        // 2. Añadir el evento de clic al botón
        themeButton.addEventListener("click", () => {
            // Alterna la clase 'dark-mode' en el body
            body.classList.toggle("dark-mode");

            // 3. Guardar la preferencia del usuario en localStorage
            if (body.classList.contains("dark-mode")) {
                localStorage.setItem("theme", "dark");
            } else {
                localStorage.setItem("theme", "light");
            }

            // Actualizar el ícono después de hacer clic
            updateThemeIcon();
        });
    }
});