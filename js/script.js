
document.addEventListener('DOMContentLoaded', () => {

        // Lógica para el navbar burger
        const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

        // Añadir un evento de clic a cada uno de los elementos con la clase "navbar-burger"
        $navbarBurgers.forEach(el => {
            el.addEventListener('click', () => {

                // Get the target from the "data-target" attribute
                const target = el.dataset.target;
                const $target = document.getElementById(target);

                //
                el.classList.toggle('is-active');
                $target.classList.toggle('is-active');

            });
        });

        // Inicialización de Tippy.js
        tippy('.view-icon', {
            content: 'Ver',
            theme: 'light',
            animation: 'scale',
            placement: 'bottom',
    });

        tippy('.edit-icon', {
            content: 'Editar',
            theme: 'light',
            animation: 'scale',
            placement: 'bottom',
    });

        tippy('.delete-icon', {
            content: 'Eliminar',
            theme: 'light',
            animation: 'scale',
            placement: 'bottom',
    });

        tippy('.image-icon', {
            content: 'Imagen',
            theme: 'light',
            animation: 'scale',
            placement: 'bottom',
    });



        // Confirmación eliminar USUARIO
        document.querySelectorAll('.confirm-delete-user').forEach(link => {
            link.addEventListener('click', function(e) {
                if (!confirm('¿Estás seguro de eliminar este USUARIO?')) {
                    e.preventDefault();
                }
            });
        });

        // Confirmación eliminar CATEGORÍA
        document.querySelectorAll('.confirm-delete-category').forEach(link => {
            link.addEventListener('click', function(e) {
                if (!confirm('¿Estás seguro de eliminar esta CATEGORÍA?')) {
                    e.preventDefault();
                }
            });
        });

    });