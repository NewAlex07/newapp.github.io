document.addEventListener('DOMContentLoaded', () => {

    const modal = new bootstrap.Modal(document.getElementById('canal-modal'));
    const modalTitulo = document.getElementById('modal-titulo');
    const form = document.getElementById('canal-form');
    const btnNuevoCanal = document.getElementById('btn-nuevo-canal');
    const tablaBody = document.getElementById('canales-tabla-body');

    // Función para manejar las peticiones Fetch
    const fetchData = async (url, options) => {
        try {
            const response = await fetch(url, options);
            if (!response.ok) throw new Error('Error en la respuesta del servidor.');
            return await response.json();
        } catch (error) {
            console.error('Error de Fetch:', error);
            alert('Ocurrió un error. Revisa la consola para más detalles.');
        }
    };

    // --- ABRIR MODAL PARA CREAR ---
    btnNuevoCanal.addEventListener('click', () => {
        form.reset();
        document.getElementById('canal-id').value = '';
        document.getElementById('canal-accion').value = 'crear';
        modalTitulo.textContent = 'Añadir Nuevo Canal';
        modal.show();
    });

    // --- ABRIR MODAL PARA EDITAR Y ELIMINAR ---
    tablaBody.addEventListener('click', async (e) => {
        // Botón Editar
        if (e.target.closest('.btn-editar')) {
            const id = e.target.closest('.btn-editar').dataset.id;
            const data = await fetchData(`control_canales.php?accion=obtener_uno&id=${id}`);
            
            if (data && data.status === 'success') {
                const canal = data.canal;
                document.getElementById('canal-id').value = canal.id;
                document.getElementById('canal-accion').value = 'actualizar';
                document.getElementById('canal-nombre').value = canal.nombre;
                document.getElementById('canal-descripcion').value = canal.descripcion;
                document.getElementById('canal-stream').value = canal.url_stream;
                document.getElementById('canal-logo').value = canal.url_logo;
                document.getElementById('canal-categoria').value = canal.id_categoria;
                document.getElementById('canal-premium').checked = canal.es_premium == 1;
                document.getElementById('canal-activo').checked = canal.activo == 1;
                
                modalTitulo.textContent = `Editar Canal: ${canal.nombre}`;
                modal.show();
            }
        }

        // Botón Eliminar
        if (e.target.closest('.btn-eliminar')) {
            const id = e.target.closest('.btn-eliminar').dataset.id;
            if (confirm(`¿Estás seguro de que quieres eliminar el canal con ID ${id}?`)) {
                const formData = new FormData();
                formData.append('accion', 'eliminar');
                formData.append('id', id);

                const data = await fetchData('control_canales.php', { method: 'POST', body: formData });
                if (data && data.status === 'success') {
                    alert(data.message);
                    location.reload(); // La forma más simple de actualizar la tabla
                } else {
                    alert(data.message || 'No se pudo eliminar el canal.');
                }
            }
        }
    });

    // --- ENVIAR FORMULARIO (CREAR/ACTUALIZAR) ---
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(form);
        const data = await fetchData('control_canales.php', { method: 'POST', body: formData });

        if (data && data.status === 'success') {
            modal.hide();
            alert(data.message);
            location.reload(); // Recargamos para ver los cambios
        } else {
            alert(data.message || 'Ocurrió un error al guardar.');
        }
    });
});