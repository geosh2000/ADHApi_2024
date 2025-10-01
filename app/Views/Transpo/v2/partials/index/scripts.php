<script>
$(document).ready(function(){

    function updateQueryString(newQueryString) {
        // Selecciona todos los enlaces <a> en el documento
        const links = document.querySelectorAll('a');
        
        // Itera sobre cada enlace y actualiza su href
        links.forEach(link => {
            // Obtiene el href actual
            const currentHref = link.getAttribute('href');
            
            // Verifica que currentHref no sea null
            if (currentHref) {
                // Si el href ya tiene un query string, lo reemplaza
                if (currentHref.includes('?')) {
                    const baseUrl = currentHref.split('?')[0];
                    link.setAttribute('href', `${baseUrl}?${newQueryString}`);
                } else {
                    // Si no hay un query string, solo añade el nuevo
                    link.setAttribute('href', `${currentHref}?${newQueryString}`);
                }
            }
        });

        queryString = newQueryString;
    }

    let queryString = '<?= $_SERVER['QUERY_STRING'] ?>';

    // Guardar el query_string actual
    let currentQueryString = new URLSearchParams(window.location.search).toString();

    // Función para verificar si el query_string ha cambiado
    function checkQueryStringChange() {
        const newQueryString = new URLSearchParams(window.location.search).toString();
        if (newQueryString !== currentQueryString) {
            currentQueryString = newQueryString;
            updateQueryString(currentQueryString);
            console.log('updating url');
        }
    }

    // Comprobar periódicamente si el query_string ha cambiado
    setInterval(checkQueryStringChange, 1000); // Ajusta el intervalo según sea necesario

    // Recuperar y aplicar la posición del scroll almacenada
    if (localStorage.getItem('scrollPosition') !== null) {
        $(window).scrollTop(localStorage.getItem('scrollPosition'));
        localStorage.removeItem('scrollPosition'); // Limpiar el valor almacenado
    }

    // Guardar la posición del scroll antes de recargar
    $(window).on('beforeunload', function() {
        localStorage.setItem('scrollPosition', $(window).scrollTop());
    });

    let params = new URLSearchParams(window.location.search);
    let page = params.get('page') || 1;
    let length = params.get('length') || 10;
    console.log('page', page, 'length', length);

    $('#transferTable').DataTable({
        "order": [], // No hay un orden inicial
        "ordering": true, // Permitir ordenamiento
        "dom": '<"top"f>rt<"bottom"lp><"clear">', // Custom DOM positioning
        "stateSave": true, // Guardar el estado del datatable (paginación, orden, etc.)
        "pageLength": length, // Establecer el número de elementos por página
        "lengthMenu": [[10, 25, 50, 100, 500], [10, 25, 50, 100, 500]], // Definir los bloques de cantidad de registros
        "stateSaveCallback": function(settings, data) {
            // Guarda el estado en la URL cuando cambie
            let params = new URLSearchParams(window.location.search);
            params.set('page', data.start / data.length + 1); // Guardar el número de página
            params.set('length', data.length); // Guardar la cantidad de elementos por página
            window.history.replaceState({}, '', `${window.location.pathname}?${params}`);
        },
        "stateLoadParams": function (settings, data) {
            // Recuperar el estado desde la URL
            let params = new URLSearchParams(window.location.search);
            let page = params.get('page') || 1;
            let length = params.get('length') || 10;
            console.log('page', page, 'length', length);
            data.start = (page - 1) * length;
            data.length = length;
        }
    });

    // Mueve el botón de exportación junto al cuadro de búsqueda
    $("#exportXls").insertBefore("#transferTable_filter label");

    $(document).on('click', '#exportXls', function() {
        // Obtén la referencia de la tabla
        var table = $('#transferTable');
        
        // Convierte la tabla a una hoja de cálculo
        var wb = XLSX.utils.table_to_book(table[0], {sheet: "Sheet1"});
        
        // Genera el archivo XLSX
        XLSX.writeFile(wb, 'transportaciones.xlsx');
    });

    function startLoader( v = true ){
        if( v ){
            $('#loader').css('display', 'flex');
        }else{
            $('#loader').css('display', 'none');
        }
    }

    $(document).on('click', '.loadbtn', function() {
        startLoader();    
    });
    
    $(document).on('click', '.history-button', function() {
        startLoader();
        var id = $(this).attr('id').split('-')[1]; // Obtiene el ID después del guion
        var url = '<?= site_url('transpo/history/') ?>' + id;

        $.ajax({
            url: url,
            method: 'GET',
            success: function(data) {
                // Asegúrate de destruir cualquier instancia existente de DataTable
                if ($.fn.DataTable.isDataTable('#historyTable')) {
                    $('#historyTable').DataTable().destroy();
                }

                // Limpia la tabla antes de cargar los nuevos datos
                $('#historyTable').empty().html(data);

                $('#historyTable').html(data); // Carga los datos en la tabla

                console.log(data);
                $('#historyTable').DataTable({
                    "order": [], // No hay un orden inicial
                    "ordering": true // Permitir ordenamiento
                });
    // Bootstrap 5: show modal with data-bs-toggle
    var historyModal = new bootstrap.Modal(document.getElementById('historyModal'), {
        backdrop: 'static',
        keyboard: false
    });
    historyModal.show();
                startLoader(false);
            },
            error: function() {
                alert('Hubo un error al cargar los datos.');
                startLoader(false);
            }
        });
    });
    
    $(document).on('click', '.sendRequest', function() {
        startLoader();
        var id = $(this).attr('data-id'); // Obtiene el ID después del guion
        var url = '<?= site_url('transpo/sendNewRequest/') ?>' + id;

        $.ajax({
            url: url,
            method: 'POST',
            success: function(data) {
                location.reload();
                startLoader(false);
            },
            error: function( err ) {
                startLoader(false);
                alert( err.responseJSON.msg );
            }
        });
    });
    
    $(document).on('click', '.sendConfirm', function() {
        startLoader();
        var id = $(this).attr('data-id'); // Obtiene el ID después del guion
        var url = '<?= site_url('transpo/conf') ?>';

        var params = {
            id1: id
        };

        $.ajax({
            url: url,
            method: 'POST',
            contentType: 'application/x-www-form-urlencoded', // Indica que los datos se envían en formato de formulario
            data: $.param(params), // Convierte el objeto params a una cadena de consulta
            success: function(data) {
                location.reload();
                startLoader(false);
            },
            error: function( err ) {
                startLoader(false);
                alert( err.responseJSON.msg );
            }
        });
    });
    
    $(document).on('click', '.delete-button', function() {
        startLoader();
        var id = $(this).attr('id').split('-')[1]; // Obtiene el ID después del guion
        var url = '<?= site_url('transpo/confirmDelete/') ?>' + id + '?' + queryString;

        $.ajax({
            url: url,
            method: 'GET',
            success: function(data) {
                $('#deleteContent').html(data); // Carga los datos en la tabla
                // Bootstrap 5: show modal with data-bs-toggle
                var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'), {
                    backdrop: 'static',
                    keyboard: false
                });
                deleteModal.show();
                startLoader(false);
            },
            error: function() {
                alert('Hubo un error al cargar los datos.');
                startLoader(false);
            }
        });
    });

    var editTicket = "";

    function create( e, v = false ){
        startLoader();

        var id;
        var url;
        if( !v ){
            id = e.attr('id').split('-')[1]; // Obtiene el ID después del guion
            url = '<?= site_url('transpo/edit/') ?>' + id + '?' + queryString;
            editTicket = id;
        }else{
            url = '<?= site_url('transpo/create/') ?>?' + queryString;
        }

        $.ajax({
            url: url,
            method: 'GET',
            success: function(data) {
                $('#editContent').html(data); // Carga los datos en la tabla
                if( !v ){
                    $('#newTicket').hide();
                }
                // Bootstrap 5: show modal with data-bs-toggle
                var editModal = new bootstrap.Modal(document.getElementById('editModal'), {
                    backdrop: 'static',
                    keyboard: false
                });
                editModal.show();
                startLoader(false);
            },
            error: function() {
                alert('Hubo un error al cargar los datos.');
                startLoader(false);
            }
        });
    }
    
    $(document).on('click', '.edit-button', function() {
        create($(this));
    });
    
    $(document).on('click', '.create-button', function() {
        create($(this), true);
    });

    // Delegación de eventos para el botón "Cancelar" usando Bootstrap 5
    $(document).on('click', '#cancelModal', function() {
        // Cierra todos los modals abiertos usando Bootstrap 5
        document.querySelectorAll('.modal.show').forEach(function(modalEl) {
            var modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (modalInstance) modalInstance.hide();
        });
    });

    // Agregar Ticket
    $(document).on('click', '.add-ticket-button', function() {
        $('#newTicket').show();
        $(this).hide();
    });

    // Duplicar Reserva
    $(document).on('click', '.clone-button', function() {
        startLoader();
        var id = $(this).attr('id').split('-')[1]; // Obtiene el ID después del guion
        var url = '<?= site_url('transpo/duplicate/') ?>' + id + '?' + queryString;

        $.ajax({
            url: url,
            method: 'POST',
            success: function(data) {
                location.reload();
                startLoader(false);
            },
            error: function() {
                alert('Hubo un error al cargar los datos.');
                startLoader(false);
            }
        });
    });

    $(document).on('submit', 'form', function(event) {
        console.log("form submitted");
        startLoader();
    });

    // confirm remove ticket
    $(document).on('click', '.remove-button', function() {
        var ticketId = this.id.replace('tkt-', '');
        var spanId = 'span-' + ticketId;
        var spanAId = 'span-a-' + ticketId;
        var $spanElement = $('#' + spanId);
        var $spanAElement = $('#' + spanAId);

        // Crear el alert de confirmación
        var $alertElement = $('<div>', { class: 'ticket-alert' }).html(`
            <p>¿Desea eliminar ticket #` + ticketId + `?</p>
            <button type="button" class="confirm-yes"><i class="fas fa-check"></i></button>
            <button type="button" class="confirm-no"><i class="fas fa-times"></i></button>
        `);

        // Ocultar <a>
        $spanAElement.hide();

        // Añadir el alert al span
        $spanElement.append($alertElement);

        // Mostrar el alert con animación
        $alertElement.fadeIn(300);

        // Manejar la confirmación
        $alertElement.find('.confirm-yes').on('click', function() {
            event.preventDefault();
            startLoader();
            $.ajax({
                url: '<?= site_url('transpo/removeTicket/') ?>' + editTicket + '/' + ticketId,
                method: 'POST',
                success: function(data) {
                    startLoader(false);
                    $spanElement.hide();
                    $alertElement.remove();
                },
                error: function() {
                    startLoader(false);
                    $spanAElement.show();
                    $alertElement.remove();
                }
            });
            
        });

        // Manejar la cancelación
        $alertElement.find('.confirm-no').on('click', function() {
            $spanAElement.show();
            $alertElement.remove();
        });
    });

    // ===== COPY FUNCTIONALITY (Bootstrap 5) =====
    // Handle copy functionality for copyable content
    $(document).on('click', '.copyable-content', function() {
        const textToCopy = $(this).data('copy');
        if (textToCopy) {
            // Use Clipboard API if available
            if (navigator.clipboard) {
                navigator.clipboard.writeText(textToCopy).then(() => {
                    showCopyNotification($(this));
                });
            } else {
                // Fallback for older browsers
                const tempTextarea = $('<textarea>');
                tempTextarea.val(textToCopy);
                $('body').append(tempTextarea);
                tempTextarea[0].select();
                document.execCommand('copy');
                tempTextarea.remove();
                showCopyNotification($(this));
            }
            // Visual feedback
            const originalBg = $(this).css('background-color');
            $(this).css('background-color', '#28a745');
            setTimeout(() => {
                $(this).css('background-color', originalBg);
            }, 200);
        }
    });
    // Show copy notification (Bootstrap 5 style)
    function showCopyNotification(element) {
        const notification = $('<div class="copy-notification shadow-sm rounded bg-success text-white px-2 py-1">¡Copiado!</div>');
        notification.css({
            position: 'absolute',
            fontSize: '0.8rem',
            zIndex: 2000,
            pointerEvents: 'none',
            opacity: 0,
        });
        const offset = element.offset();
        notification.css({
            top: offset.top - 30,
            left: offset.left + element.width() / 2 - 25
        });
        $('body').append(notification);
        notification.animate({ opacity: 1 }, 200).delay(1000).animate({ opacity: 0 }, 200, function() {
            $(this).remove();
        });
    }

    // ===== MULTISELECT FUNCTIONALITY (Bootstrap 5) =====
    // Función para inicializar multiselects
    function initializeMultiselects() {
        $('.multiselect-wrapper').each(function() {
            const wrapper = $(this);
            const select = wrapper.find('select');
            const display = wrapper.find('.multiselect-display');
            const dropdown = wrapper.find('.multiselect-dropdown');
            const searchInput = wrapper.find('.multiselect-search input');
            const selectId = select.attr('id');
            // Configurar valores iniciales
            updateDisplay(wrapper);
            // Bootstrap 5 dropdown: toggle con data-bs-toggle
            display.attr('data-bs-toggle', 'dropdown');
            display.on('click', function(e) {
                e.stopPropagation();
                $('.multiselect-dropdown').not(dropdown).removeClass('show');
                $('.multiselect-display').not(display).removeClass('active');
                dropdown.toggleClass('show');
                display.toggleClass('active');
            });
            // Event listener para checkboxes
            wrapper.find('.multiselect-option input[type="checkbox"]').on('change', function() {
                const value = $(this).closest('.multiselect-option').data('value');
                const option = select.find(`option[value="${value}"]`);
                if ($(this).is(':checked')) {
                    option.prop('selected', true);
                    $(this).closest('.multiselect-option').addClass('selected');
                } else {
                    option.prop('selected', false);
                    $(this).closest('.multiselect-option').removeClass('selected');
                }
                updateDisplay(wrapper);
            });
            // Event listener para búsqueda
            if (searchInput.length) {
                searchInput.on('input', function() {
                    const searchTerm = $(this).val().toLowerCase();
                    wrapper.find('.multiselect-option').each(function() {
                        const text = $(this).find('label').text().toLowerCase();
                        if (text.includes(searchTerm)) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                });
            }
        });
        // Cerrar dropdowns al hacer click fuera
        $(document).on('click', function() {
            $('.multiselect-dropdown').removeClass('show');
            $('.multiselect-display').removeClass('active');
        });
    }
    // Función para actualizar el display del multiselect
    function updateDisplay(wrapper) {
        const select = wrapper.find('select');
        const display = wrapper.find('.multiselect-display');
        const selectedOptions = select.find('option:selected');
        const selectId = select.attr('id');
        // Limpiar display actual
        display.find('.multiselect-selected, .multiselect-placeholder').remove();
        if (selectedOptions.length === 0) {
            // Mostrar placeholder
            const placeholder = display.data('placeholder') || 'Seleccionar opciones...';
            display.prepend(`<div class="multiselect-placeholder">${placeholder}</div>`);
        } else {
            // Mostrar tags seleccionados
            const selectedContainer = $('<div class="multiselect-selected"></div>');
            selectedOptions.each(function() {
                const value = $(this).val();
                const text = $(this).text();
                const tagClass = getTagClass(selectId, value);
                const tag = $(`
                    <span class="multiselect-tag ${tagClass} badge bg-primary me-1">
                        ${text}
                        <span class="multiselect-tag-remove ms-1" data-value="${value}" style="cursor:pointer;">&times;</span>
                    </span>
                `);
                selectedContainer.append(tag);
            });
            display.prepend(selectedContainer);
        }
        // Event listener para remover tags
        display.find('.multiselect-tag-remove').on('click', function(e) {
            e.stopPropagation();
            const value = $(this).data('value');
            const option = select.find(`option[value="${value}"]`);
            const checkbox = wrapper.find(`.multiselect-option[data-value="${value}"] input[type="checkbox"]`);
            option.prop('selected', false);
            checkbox.prop('checked', false);
            checkbox.closest('.multiselect-option').removeClass('selected');
            updateDisplay(wrapper);
        });
        // Actualizar checkboxes según selecciones
        wrapper.find('.multiselect-option').each(function() {
            const value = $(this).data('value');
            const isSelected = select.find(`option[value="${value}"]`).is(':selected');
            const checkbox = $(this).find('input[type="checkbox"]');
            checkbox.prop('checked', isSelected);
            $(this).toggleClass('selected', isSelected);
        });
    }
    // Función para obtener la clase CSS del tag según el tipo y valor
    function getTagClass(selectId, value) {
        const classMap = {
            'status': {
                'INCLUIDA': 'status-incluida',
                'INCLUIDA (SOLICITADO)': 'status-incluida',
                'SOLICITADO': 'status-solicitado',
                'LIGA PENDIENTE': 'status-pendiente',
                'PAGO PENDIENTE': 'status-pendiente',
                'CORTESÍA (CAPTURA PENDIENTE)': 'status-pendiente',
                'PAGO EN DESTINO (CAPTURA PENDIENTE)': 'status-pendiente',
                'PAGADA (CAPTURA PENDIENTE)': 'status-pendiente',
                'CANCELADA': 'status-cancelada',
                'PENDIENTE CANCELACION': 'status-cancelada',
                'CORTESÍA (CAPTURADO)': 'status-capturado',
                'PAGO EN DESTINO (CAPTURADO)': 'status-capturado',
                'PAGADA (CAPTURADO)': 'status-capturado'
            },
            'hotel': {
                'ATELIER': 'hotel-atelier',
                'OLEO': 'hotel-oleo'
            },
            'tipo': {
                'ENTRADA': 'tipo-entrada',
                'SALIDA': 'tipo-salida'
            }
        };
        return classMap[selectId] && classMap[selectId][value] ? classMap[selectId][value] : '';
    }
    // Inicializar multiselects cuando el documento esté listo
    initializeMultiselects();
});
</script>
