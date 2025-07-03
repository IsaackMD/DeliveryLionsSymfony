function addKeepBuying() {
    const cantidadInput = $('#cantidad').val();
    const idInput = $('#MenuID').val();

    // Validación simple
    if (!cantidadInput || !idInput) {
        alert('Faltan datos para agregar al carrito.');
        return;
    }
    console.log('Cantidad:', cantidadInput, 'ID:', idInput);
    $.ajax({
        url: '{{ path("add_to_carrito") }}',
        type: 'POST',
        data: {
            id: idInput,
            cantidad: cantidadInput
        },
        success: function(response) {
            console.log('Producto agregado al carrito:', response);
            // Opcional: cerrar modal
            $('#modalDetalleComida').modal('hide');
            // Opcional: notificación de éxito
        },
        error: function(xhr) {
            console.error('Error al agregar al carrito:', xhr);
            alert('Hubo un problema al agregar al carrito.');
        }
    });
}
function DirectBuy(){
    const cantidadInput = $('#cantidad').val();
    const idInput = $('#MenuID').val();

    const directBuy = true;
    // Validación simple
    if (!cantidadInput || !idInput) {
        alert('Faltan datos para agregar al carrito.');
        return;
    }
    console.log('Cantidad:', cantidadInput, 'ID:', idInput);
    $.ajax({
        url: '{{ path("add_to_carrito") }}',
        type: 'POST',
        data: {
            id: idInput,
            cantidad: cantidadInput,
            directBuy: directBuy
        },
        success: function(response) {
            if(response.success && response.redirect){
                // Redirige a la URL especificada
                window.location.href = response.redirect;
            }
        },
        error: function(xhr) {
            console.error('Error al agregar al carrito:', xhr);
            alert('Hubo un problema al agregar al carrito.');
        }
    });
}

