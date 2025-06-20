// ============================================== //
// LÓGICA DE FILTRADO PARA LA CANCHA TV           //
// ============================================== //
document.addEventListener('DOMContentLoaded', function () {
    const categoryButtons = document.querySelectorAll('.category-button');
    const videoCards = document.querySelectorAll('.video-card');

    categoryButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Manejar el estado 'active' del botón
            categoryButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');

            const selectedCategoryId = button.dataset.categoryId;

            // Filtrar las tarjetas de video/canal
            videoCards.forEach(card => {
                const cardCategoryId = card.dataset.categoryId;

                // Si se selecciona "Todos" (no tiene data-category-id) o si la categoría coincide
                if (!selectedCategoryId || selectedCategoryId === cardCategoryId) {
                    card.style.display = 'block'; // Mostrar la tarjeta
                } else {
                    card.style.display = 'none'; // Ocultar la tarjeta
                }
            });
        });
    });
});