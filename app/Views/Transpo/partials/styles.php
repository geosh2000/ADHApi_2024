<link rel="stylesheet" href="btnStyles.css">
<style>
/* Estilos generales */
.card {
    border: none;
    border-radius: 10px;
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
    border-bottom: 2px solid #e9ecef;
}

.form-label {
    color: #495057;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

/* ===== TABLE IMPROVEMENTS ===== */

/* Table general styles */
.table-responsive {
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

#transferTable {
    margin-bottom: 0;
    background: white;
}

#transferTable thead th {
    background: linear-gradient(135deg, #343a40, #495057);
    color: white;
    font-weight: 600;
    font-size: 0.85rem;
    border: none;
    padding: 12px 8px;
    text-align: center;
    vertical-align: middle;
}

/* Row hover effects */
.table-row {
    transition: all 0.2s ease;
    cursor: pointer;
}

.table-row:hover {
    background-color: rgba(0, 123, 255, 0.05) !important;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

/* Cell styles */
.table-sm-custom td {
    padding: 8px 6px;
    vertical-align: middle;
    border-color: #e9ecef;
    font-size: 0.8rem;
}

/* ID Cell */
.id-cell {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
}

.id-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
}

.id-number {
    font-weight: bold;
    color: #495057;
    font-size: 0.9rem;
}

/* Hotel Cell */
.hotel-cell {
    min-width: 140px;
}

.hotel-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.hotel-name {
    font-weight: 500;
    color: #495057;
    font-size: 0.8rem;
}

.badge-sm {
    font-size: 0.65rem;
    padding: 2px 6px;
    border-radius: 10px;
}

/* Copyable cells */
.copyable-cell {
    position: relative;
}

.copyable-content {
    position: relative;
    display: inline-block;
    padding: 2px 4px;
    border-radius: 4px;
    transition: all 0.2s ease;
    cursor: pointer;
    width: 100%;
}

.copyable-content:hover {
    background-color: rgba(0, 123, 255, 0.1);
    color: #007bff;
}

.copy-icon {
    position: absolute;
    right: -2px;
    top: 50%;
    transform: translateY(-50%);
    opacity: 0;
    transition: opacity 0.2s ease;
    color: #007bff;
    font-size: 0.7rem;
    pointer-events: none;
}

.copyable-content:hover .copy-icon {
    opacity: 1;
}

/* Guest Cell */
.guest-cell {
    min-width: 160px;
}

.guest-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.guest-name {
    font-weight: 500;
    color: #495057;
}

.guest-email {
    color: #6c757d;
    font-size: 0.75rem;
}

/* Date Cell */
.date-cell {
    min-width: 100px;
}

.date-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.date-value {
    font-weight: 500;
    color: #495057;
}

.time-value {
    color: #6c757d;
    font-size: 0.75rem;
}

/* Flight Cell */
.flight-cell {
    min-width: 120px;
}

.flight-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.airline-value {
    font-weight: 500;
    color: #495057;
}

.flight-value {
    color: #6c757d;
    font-size: 0.75rem;
}

/* Status Cell */
.status-cell {
    min-width: 140px;
}

.status-btn {
    font-size: 0.7rem;
    padding: 4px 8px;
    border-radius: 15px;
    font-weight: 500;
    min-width: 120px;
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
}

/* Actions Cell */
.actions-cell {
    min-width: 180px;
}

.action-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 2px;
    justify-content: center;
}

.action-buttons .btn {
    padding: 4px 6px;
    border-radius: 4px;
    transition: all 0.2s ease;
}

.action-buttons .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

/* Tickets Cell */
.tickets-cell {
    min-width: 120px;
}

.tickets-container {
    display: flex;
    flex-direction: column;
    gap: 4px;
    align-items: center;
}

.ticket-link-wrapper {
    position: relative;
    width: 100%;
}

.ticket-link {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 4px 8px;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border: 1px solid #dee2e6;
    border-radius: 6px;
    text-decoration: none;
    color: #495057;
    font-size: 0.75rem;
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
}

.ticket-link:hover {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    text-decoration: none;
    transform: translateY(-1px);
    box-shadow: 0 2px 6px rgba(0, 123, 255, 0.3);
}

.ticket-number {
    font-weight: 600;
}

.ticket-type {
    font-size: 0.65rem;
    opacity: 0.8;
}

.external-icon {
    opacity: 0;
    transition: opacity 0.2s ease;
    font-size: 0.6rem;
}

.ticket-link:hover .external-icon {
    opacity: 1;
}

/* Price Cell */
.price-cell {
    min-width: 80px;
}

.price-container {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 2px;
    font-weight: 500;
    color: #28a745;
}

.currency {
    font-size: 0.8rem;
    opacity: 0.8;
}

.amount {
    font-size: 0.85rem;
}

/* Phone and Date Created Cells */
.phone-cell, .date-created-cell {
    min-width: 100px;
}

.phone-container, .date-created-container {
    display: flex;
    justify-content: flex-end;
    font-size: 0.8rem;
    color: #6c757d;
}

/* Export button */
#exportXls {
    border-radius: 6px;
    font-weight: 500;
    padding: 8px 16px;
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(40, 167, 69, 0.2);
}

#exportXls:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .table-sm-custom td {
        padding: 6px 4px;
        font-size: 0.75rem;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 1px;
    }
    
    .action-buttons .btn {
        padding: 3px 5px;
        font-size: 0.7rem;
    }
    
    .ticket-link {
        padding: 3px 6px;
        font-size: 0.7rem;
    }
    
    .status-btn {
        font-size: 0.65rem;
        padding: 3px 6px;
        min-width: 100px;
    }
}

/* Estilos para multiselect mejorados */
.multiselect-wrapper {
    position: relative;
    width: 100%;
}

.multiselect-hidden {
    display: none !important;
}

.multiselect-display {
    position: relative;
    display: flex;
    align-items: center;
    min-height: 38px;
    padding: 8px 12px;
    background-color: #fff;
    border: 1px solid #ced4da;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 14px;
}

.multiselect-display:hover {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.multiselect-display.active {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.multiselect-placeholder {
    color: #6c757d;
    font-style: italic;
    flex: 1;
}

.multiselect-selected {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
    flex: 1;
    align-items: center;
}

.multiselect-tag {
    display: inline-flex;
    align-items: center;
    padding: 2px 8px;
    font-size: 12px;
    font-weight: 500;
    color: #fff;
    background-color: #007bff;
    border-radius: 12px;
    white-space: nowrap;
    margin: 1px;
}

.multiselect-tag.status-incluida { background-color: #28a745; }
.multiselect-tag.status-solicitado { background-color: #ffc107; color: #212529; }
.multiselect-tag.status-pendiente { background-color: #fd7e14; }
.multiselect-tag.status-cancelada { background-color: #dc3545; }
.multiselect-tag.status-capturado { background-color: #6f42c1; }

.multiselect-tag.hotel-atelier { background-color: #17a2b8; }
.multiselect-tag.hotel-oleo { background-color: #6610f2; }

.multiselect-tag.tipo-entrada { background-color: #20c997; }
.multiselect-tag.tipo-salida { background-color: #e83e8c; }

.multiselect-tag-remove {
    margin-left: 4px;
    cursor: pointer;
    font-weight: bold;
    opacity: 0.7;
    padding: 0 2px;
}

.multiselect-tag-remove:hover {
    opacity: 1;
}

.multiselect-arrow {
    color: #6c757d;
    font-size: 12px;
    transition: transform 0.3s ease;
    margin-left: 8px;
}

.multiselect-display.active .multiselect-arrow {
    transform: rotate(180deg);
}

.multiselect-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    z-index: 1000;
    background-color: #fff;
    border: 1px solid #ced4da;
    border-top: none;
    border-radius: 0 0 4px 4px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    display: none;
    max-height: 250px;
    overflow-y: auto;
}

.multiselect-dropdown.show {
    display: block;
}

.multiselect-search {
    padding: 8px;
    border-bottom: 1px solid #e9ecef;
    background-color: #f8f9fa;
}

.multiselect-search input {
    border: 1px solid #ced4da;
    border-radius: 3px;
    padding: 4px 8px;
    font-size: 12px;
}

.multiselect-options {
    max-height: 200px;
    overflow-y: auto;
}

.multiselect-option {
    display: flex;
    align-items: center;
    padding: 8px 12px;
    cursor: pointer;
    transition: background-color 0.2s ease;
    border-bottom: 1px solid #f8f9fa;
}

.multiselect-option:hover {
    background-color: #f8f9fa;
}

.multiselect-option:last-child {
    border-bottom: none;
}

.multiselect-option input[type="checkbox"] {
    margin-right: 8px;
    cursor: pointer;
}

.multiselect-option label {
    cursor: pointer;
    margin: 0;
    font-size: 13px;
    flex: 1;
}

.multiselect-option.selected {
    background-color: #e3f2fd;
}

/* Scrollbar personalizado para dropdown */
.multiselect-dropdown::-webkit-scrollbar {
    width: 6px;
}

.multiselect-dropdown::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.multiselect-dropdown::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.multiselect-dropdown::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .multiselect-tag {
        font-size: 11px;
        padding: 1px 6px;
    }
    
    .multiselect-selected {
        gap: 2px;
    }
    
    .multiselect-dropdown {
        max-height: 200px;
    }
}

/* Estilos para los enlaces de paginación */

/* Botón NO FACTURADO - VINO */
.btn-notInvoiced {
    background-color: #FF0000;
    color: white;
    border: 1px solid #FF0000;
}
.btn-notInvoiced:hover {
    background-color: #CC0000;
    border-color: #CC0000;
}
.btn-notInvoiced:focus, .btn-notInvoiced.focus {
    box-shadow: 0 0 0 0.2rem rgba(255, 0, 0, 0.5);
}
.btn-notInvoiced:disabled, .btn-notInvoiced.disabled {
    background-color: #FFCCCC;
    border-color: #FFCCCC;
}

/* Botón Cancel - Rojo */
.btn-cancel {
    background-color: #FF0000;
    color: white;
    border: 1px solid #FF0000;
}
.btn-cancel:hover {
    background-color: #CC0000;
    border-color: #CC0000;
}
.btn-cancel:focus, .btn-cancel.focus {
    box-shadow: 0 0 0 0.2rem rgba(255, 0, 0, 0.5);
}
.btn-cancel:disabled, .btn-cancel.disabled {
    background-color: #FFCCCC;
    border-color: #FFCCCC;
}

/* Botón Cancel - Rojo */
.btn-pdt-cancel {
    background-color: #ff4500;
    color: white;
    border: 1px solid #ff4500;
}
.btn-pdt-cancel:hover {
    background-color: #CC0000;
    border-color: #CC0000;
}
.btn-pdt-cancel:focus, .btn-pdt-cancel.focus {
    box-shadow: 0 0 0 0.2rem rgba(255, 0, 0, 0.5);
}
.btn-pdt-cancel:disabled, .btn-pdt-cancel.disabled {
    background-color: #FFCCCC;
    border-color: #FFCCCC;
}

/* Botón IncluNoData - Blanco */
.btn-incluNoData {
    background-color: #FFFFFF;
    color: black;
    border: 1px solid #CCCCCC;
}
.btn-incluNoData:hover {
    background-color: #F0F0F0;
    border-color: #CCCCCC;
}
.btn-incluNoData:focus, .btn-incluNoData.focus {
    box-shadow: 0 0 0 0.2rem rgba(204, 204, 204, 0.5);
}
.btn-incluNoData:disabled, .btn-incluNoData.disabled {
    background-color: #F9F9F9;
    border-color: #CCCCCC;
}

/* Botón IncluSolicitado - Morado */
.btn-incluSolicitado {
    background-color: #800080;
    color: white;
    border: 1px solid #800080;
}
.btn-incluSolicitado:hover {
    background-color: #660066;
    border-color: #660066;
}
.btn-incluSolicitado:focus, .btn-incluSolicitado.focus {
    box-shadow: 0 0 0 0.2rem rgba(128, 0, 128, 0.5);
}
.btn-incluSolicitado:disabled, .btn-incluSolicitado.disabled {
    background-color: #E0B3E0;
    border-color: #E0B3E0;
}

/* Botón LigaPendiente - Naranja */
.btn-ligaPendiente {
    background-color: #26d2c7;
    color: white;
    border: 1px solid #26d2c7;
}
.btn-ligaPendiente:hover {
    background-color: #24a0ae;
    border-color: #24a0ae;
}
.btn-ligaPendiente:focus, .btn-ligaPendiente.focus {
    box-shadow: 0 0 0 0.2rem rgba(255, 165, 0, 0.5);
}
.btn-ligaPendiente:disabled, .btn-ligaPendiente.disabled {
    background-color: #FFE0B3;
    border-color: #FFE0B3;
}

/* Botón PagoPendiente - Naranja */
.btn-pagoPendiente {
    background-color: #FFA500;
    color: white;
    border: 1px solid #FFA500;
}
.btn-pagoPendiente:hover {
    background-color: #CC8400;
    border-color: #CC8400;
}
.btn-pagoPendiente:focus, .btn-pagoPendiente.focus {
    box-shadow: 0 0 0 0.2rem rgba(255, 165, 0, 0.5);
}
.btn-pagoPendiente:disabled, .btn-pagoPendiente.disabled {
    background-color: #FFE0B3;
    border-color: #FFE0B3;
}

/* Botón PagadoSinIngresar - Amarillo */
.btn-pagadoSinIngresar {
    background-color: #FFFF00;
    color: black;
    border: 1px solid #FFFF00;
}
.btn-pagadoSinIngresar:hover {
    background-color: #CCCC00;
    border-color: #CCCC00;
}
.btn-pagadoSinIngresar:focus, .btn-pagadoSinIngresar.focus {
    box-shadow: 0 0 0 0.2rem rgba(255, 255, 0, 0.5);
}
.btn-pagadoSinIngresar:disabled, .btn-pagadoSinIngresar.disabled {
    background-color: #FFFFE0;
    border-color: #FFFFE0;
}

/* Botón PagadoRegistradoAtpm - Verde */
.btn-pagadoRegistradoAtpm {
    background-color: #4CAF50;
    color: white;
    border: 1px solid #4CAF50;
}
.btn-pagadoRegistradoAtpm:hover {
    background-color: #45a049;
    border-color: #45a049;
}
.btn-pagadoRegistradoAtpm:focus, .btn-pagadoRegistradoAtpm.focus {
    box-shadow: 0 0 0 0.2rem rgba(76, 175, 80, 0.5);
}
.btn-pagadoRegistradoAtpm:disabled, .btn-pagadoRegistradoAtpm.disabled {
    background-color: #c8e6c9;
    border-color: #c8e6c9;
}

/* Botón PagadoRegistradoOlcp - Azul Cielo */
.btn-pagadoRegistradoOlcp {
    background-color: #87CEEB;
    color: white;
    border: 1px solid #87CEEB;
}
.btn-pagadoRegistradoOlcp:hover {
    background-color: #6bb6d8;
    border-color: #6bb6d8;
}
.btn-pagadoRegistradoOlcp:focus, .btn-pagadoRegistradoOlcp.focus {
    box-shadow: 0 0 0 0.2rem rgba(135, 206, 235, 0.5);
}
.btn-pagadoRegistradoOlcp:disabled, .btn-pagadoRegistradoOlcp.disabled {
    background-color: #d1ecf8;
    border-color: #d1ecf8;
}

.pagination {
    margin-top: 20px; /* Espacio entre la tabla y la paginación */
    text-align: center; /* Centrar los enlaces de paginación */
}

.pagination li {
    display: inline-block;
    margin-right: 5px; /* Espacio entre los enlaces */
}

.pagination a {
    padding: 5px 10px;
    background-color: transparent;
    color: #007bff; /* Color de los enlaces */
    text-decoration: none;
    border: 1px solid #007bff; /* Borde de los enlaces */
    border-radius: 3px; /* Borde redondeado */
    transition: background-color 0.3s, color 0.3s; /* Transición suave */
}

.pagination a:hover {
    background-color: #007bff; /* Cambio de color al pasar el ratón */
    color: #fff; /* Cambio de color al pasar el ratón */
}

.pagination .active a {
    background-color: #007bff;
    color: #fff;
    border-color: #007bff;
    pointer-events: none; /* Desactivar el enlace activo */
}

.table-sm-custom {
    font-size: 0.75rem; /* Tamaño de fuente reducido */
    width: 50%; /* Ajusta este valor según tus necesidades */
}
.table-sm-custom td, .table-sm-custom th {
    padding: 0.3rem; /* Ajusta el padding según sea necesario */
}

.dropdown-menu {
    position: absolute !important; /* Asegura que el dropdown flote */
    will-change: transform; /* Mejora el rendimiento de la animación */
}

.copy-button {
    cursor: pointer;
    font-size: 12px; /* Ajusta el tamaño según tus necesidades */
    margin-left: 4px;
    margin-right: 4px;
    color: brown;
}

.add-button {
    cursor: pointer;
    font-size: 15px; /* Ajusta el tamaño según tus necesidades */
    margin-left: 4px;
    margin-right: 4px;
    color: #4CAF50;
}

.remove-button {
    cursor: pointer;
    font-size: 15px; /* Ajusta el tamaño según tus necesidades */
    margin-left: 4px;
    margin-right: 4px;
    color: #CC0000;
}

.actionBtn{
    zoom:0.8;
    margin: 1px;
}

.ticket-alert {
    display: none;
    position: absolute;
    z-index: 1000;
    background: #f8d7da;
    border: 1px solid #f5c6cb;
    padding: 10px;
    border-radius: 5px;
    animation: fadeIn 0.3s;
}

.ticket-alert button {
    background: none;
    border: none;
    margin: 0 5px;
    color: #721c24;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* Loading button styles */
.loadbtn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Button hover effects */
.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

/* Input focus styles */
.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}

/* Card shadow on hover */
.card:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    transition: box-shadow 0.3s ease;
}
</style>
