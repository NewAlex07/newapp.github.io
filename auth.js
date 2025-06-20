document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('login-form');
    const registroForm = document.getElementById('registro-form');
    const errorMessageDiv = document.getElementById('error-message');

    const handleFormSubmit = async (form) => {
        const formData = new FormData(form);
        
        try {
            const response = await fetch('controladores/controlador_auth.php', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) throw new Error('Error en la respuesta del servidor.');

            const data = await response.json();

            if (data.status === 'success') {
                // Si hay una URL de redirección, la usamos.
                if (data.redirect) {
                    window.location.href = data.redirect;
                }
            } else {
                // Mostramos el mensaje de error del servidor.
                errorMessageDiv.textContent = data.message;
                errorMessageDiv.classList.remove('d-none');
            }
        } catch (error) {
            errorMessageDiv.textContent = 'Ocurrió un error de conexión. Inténtalo de nuevo.';
            errorMessageDiv.classList.remove('d-none');
            console.error('Error de Fetch:', error);
        }
    };

    if (loginForm) {
        loginForm.addEventListener('submit', (e) => {
            e.preventDefault();
            handleFormSubmit(loginForm);
        });
    }

    if (registroForm) {
        registroForm.addEventListener('submit', (e) => {
            e.preventDefault();
            handleFormSubmit(registroForm);
        });
    }
});