document.addEventListener('DOMContentLoaded', function() {
            var select = document.querySelector('#options-type-select');
            if (select) {
                var glide = new Glide('.carrusel-list', {
                    type: select.value,
                    focusAt: 'center',
                    perView: 1,
                    slidesToScroll: 1,
                    scrollLock: true,
                    dots: '#resp-dots',
                    arrows: {
                        prev: '.glider-prev',
                        next: '.glider-next'
                    },
                    responsive: [
                        {
                            breakpoint: 775,
                            settings: {
                                slidesToShow: 'auto',
                                slidesToScroll: 'auto',
                                itemWidth: 150,
                                duration: 0.25
                            }
                        },
                        {
                            breakpoint: 1024,
                            settings: {
                                slidesToShow: 2,
                                slidesToScroll: 1,
                                itemWidth: 150,
                                duration: 0.25
                            }
                        }
                    ]
                });

                // Event listener for select change
                select.addEventListener('change', function(event) {
                    glide.update({
                        type: event.target.value
                    });
                });

                // Mount the Glide carousel
                glide.mount();
            }

                        $('.content h1').addClass('visible');

                setTimeout(() => {
                    $('.content h3').addClass('visible');
                }, 500);

                function mostrarParrafos() {
                    $('.nosotros .texto p').each(function () {
                        const topElemento = $(this).offset().top;
                        const topVentana = $(window).scrollTop() + $(window).height();

                        if (topElemento < topVentana - 50 && !$(this).hasClass('visible')) {
                            $(this).addClass('visible');
                        }
                    });
                }

                mostrarParrafos(); // al cargar
                $(window).on('scroll', mostrarParrafos);

        });

        function mostrarDetComida(element) {
            const idComida = element.getAttribute('data-id');

            $.ajax({
                url: PathDetalleComida,
                type: 'GET',
                data: { id: idComida },
                success: function (data) {
                    // Actualizar título del modal
                    document.getElementById('modalDetalleComidaTitle').innerText = data.nombre;
                    
                    $('#descp').siblings('p').remove(); // elimina los <p> extra después de descp

                    // Actualizar imagen
                    const imgRuta = imgRutaComida + data.imagen;
                    $('#modalDetalleComidaBody img').attr('src', imgRuta);

                    // Actualizar texto del negocio
                    $('.span_detNe').html('Negocio: <strong>' + data.Negocio + '</strong>');

                    // Actualizar descripción
                    $('#descp').text(data.descripcion);

                    // Agregar complemento si existe
                    let contenidoExtra = '';
                    if (data.complemento) {
                        contenidoExtra += `<p><strong>Complemento:</strong> ${data.complemento}</p>`;
                    }

                    // Agregar contenido adicional (si quieres más info ahí)
                    contenidoExtra += `<p>¡Disfruta esta deliciosa comida!</p>`;
                    $('#descp').after(contenidoExtra);

                    $('#MenuID').val(data.id); // Actualizar el valor del input oculto
                    // Actualizar botón de precio
                    $('#btnAgregar').html(`Comprar $${data.preciofinal} MXN`);

                    // Mostrar el modal
                    $('#modalDetalleComida').modal('show');
                },
                error: function (xhr, status, error) {
                    console.error('Error al cargar el detalle de la comida:', error);
                }
            });
        }

        function mostrarDetRestaurante(element) {
            const idRestaurante = element.getAttribute('data-restaurante');

            $.ajax({
                url: rutaDetalleNegocio,
                type: 'GET',
                data: { id: idRestaurante },
                success: function (data) {
                    // Actualizar título del modal
                    document.getElementById('TituloNegocio').innerText = data.nombre;
                    
                    $('#Descripcion').siblings('p').remove(); // elimina los <p> extra después de Descripcion

                    // Actualizar imagen
                    const imgRuta = imgRutaNegocio + data.imagen;
                    $('#ImagenNegocio img').attr('src', imgRuta);

                    // Actualizar texto del negocio
                    $('.TelefonoNegocio').html('Telefono: <strong>' + data.telefono + '</strong>');

                    // Actualizar descripción
                    $('#Descripcion').text(data.descripcion);

                    // Agregar complemento si existe
                    let contenidoExtra = '';
                    if (data.direccion) {
                        contenidoExtra += `<p><strong>Dirección:</strong> ${data.direccion}</p>`;
                    }

                    // Agregar contenido adicional (si quieres más info ahí)
                    contenidoExtra += `<p>Dirección:</p>`;
                    $('#Direccion').after(contenidoExtra);

                    $('#NegocioID').val(data.id); // Actualizar el valor del input oculto
                    // Actualizar botón de precio
                    // $('#btnAgregar').html(`Comprar $${data.preciofinal} MXN`);

                    // Mostrar el modal
                    $('#ModalDetalleNegocio').modal('show');
                },
                error: function (xhr, status, error) {
                    console.error('Error al cargar el negocio:', error);
                }
            });
        }


        function addCart(idInput) {
            const cantidadInput = 1;

            if (!cantidadInput || !idInput) {
                alert('Faltan datos para agregar al carrito.');
                console.log('Cantidad:', cantidadInput, 'ID:', idInput);
                return;
            }

            $.ajax({
                url: AddToCart,
                type: 'POST',
                data: {
                    id: idInput,
                    cantidad: cantidadInput
                },
                success: function(response) {
                    
                    Swal.fire({
                        title: "Agregado al carrito",
                        text: "Agregaste un producto al carrito",
                        icon: "success"
                    });

                    // También puedes recargar el contenido del carrito si tienes una función:
                    // cargarCarrito(); // <-- si tienes algo así, lo llamas aquí
                },
                error: function(xhr) {
                    Swal.fire({
                        title: "Error",
                        text: "Error al agregar al carrito. Por favor, inténtalo de nuevo.",
                        icon: "error"
                    });
                    
                }
            });
        }

        
        