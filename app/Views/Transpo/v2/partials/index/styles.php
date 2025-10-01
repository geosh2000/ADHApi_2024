
<link rel="stylesheet" href="btnStyles.css">
<style>
/* Estilos generales */
.card {
    border: none;
    border-radius: 0.5rem; /* Bootstrap 5 border-radius-lg */
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
}

.card-header {
    border-radius: 0.5rem 0.5rem 0 0 !important;
    border-bottom: 2px solid #e9ecef;
    background-color: var(--bs-card-cap-bg, #f8f9fa);
}

.form-label {
    color: var(--bs-secondary-color, #495057);
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

/* ===== TABLE IMPROVEMENTS ===== */

/* Table general styles */
.table-responsive {
    border-radius: 0.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

#transferTable {
    margin-bottom: 0;
    background: white;
}

#transferTable thead th {
    background: linear-gradient(135deg, var(--bs-dark), var(--bs-secondary));
    color: #fff;
    font-weight: 600;
    font-size: 0.85rem;
    border: none;
    padding: 12px 8px;
    text-align: center;
    vertical-align: middle;
}

/* Row hover effects */
.table-row {
    transition: box-shadow 0.2s, background-color 0.2s;
    cursor: pointer;
}

.table-row:hover, .table-hover>tbody>tr:hover {
    background-color: var(--bs-table-hover-bg, rgba(0, 123, 255, 0.04)) !important;
    box-shadow: 0 2px 8px rgba(0, 123, 255, 0.08);
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
    border-radius: 0.5rem;
    background-color: var(--bs-light);
    color: var(--bs-dark);
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
    background-color: var(--bs-primary-bg-subtle, rgba(13, 110, 253, 0.1));
    color: var(--bs-primary, #0d6efd);
}

.copy-icon {
    position: absolute;
    right: -2px;
    top: 50%;
    transform: translateY(-50%);
    opacity: 0;
    transition: opacity 0.2s ease;
    color: var(--bs-primary, #0d6efd);
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
    border-radius: 1rem;
    font-weight: 500;
    min-width: 120px;
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
    background-color: var(--bs-light);
    color: var(--bs-dark);
    border: none;
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
    border-radius: 0.25rem;
    transition: box-shadow 0.2s, transform 0.2s;
}

.action-buttons .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 123, 255, 0.10);
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
    background: linear-gradient(135deg, var(--bs-light), #e9ecef);
    border: 1px solid var(--bs-border-color, #dee2e6);
    border-radius: 0.375rem;
    text-decoration: none;
    color: var(--bs-secondary-color, #495057);
    font-size: 0.75rem;
    transition: background 0.2s, color 0.2s, box-shadow 0.2s, transform 0.2s;
    position: relative;
    overflow: hidden;
}

.ticket-link:hover {
    background: linear-gradient(135deg, var(--bs-primary), #0056b3);
    color: #fff;
    text-decoration: none;
    transform: translateY(-1px);
    box-shadow: 0 2px 6px rgba(13, 110, 253, 0.25);
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
    color: var(--bs-success, #198754);
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
    border-radius: 0.375rem;
    font-weight: 500;
    padding: 8px 16px;
    transition: box-shadow 0.2s, transform 0.2s;
    box-shadow: 0 2px 4px rgba(25, 135, 84, 0.18);
    background-color: var(--bs-success, #198754);
    color: #fff;
    border: none;
}

#exportXls:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(25, 135, 84, 0.28);
    background-color: #157347;
    color: #fff;
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

.btn-notInvoiced {
    --bs-btn-bg: #dc3545;
    --bs-btn-border-color: #dc3545;
    --bs-btn-hover-bg: #b02a37;
    --bs-btn-hover-border-color: #b02a37;
    --bs-btn-disabled-bg: #f8d7da;
    --bs-btn-disabled-border-color: #f8d7da;
    color: #fff !important;
    background-color: #dc3545 !important;
    border: 1px solid #dc3545 !important;
}
.btn-notInvoiced:hover, .btn-notInvoiced:focus {
    background-color: #b02a37 !important;
    border-color: #b02a37 !important;
    color: #fff !important;
    box-shadow: 0 0 0 0.2rem rgba(220,53,69,.25);
}
.btn-notInvoiced:disabled, .btn-notInvoiced.disabled {
    background-color: #f8d7da !important;
    border-color: #f8d7da !important;
    color: #fff !important;
}

.btn-cancel {
    background-color: #dc3545 !important;
    color: #fff !important;
    border: 1px solid #dc3545 !important;
}
.btn-cancel:hover, .btn-cancel:focus {
    background-color: #b02a37 !important;
    border-color: #b02a37 !important;
    color: #fff !important;
    box-shadow: 0 0 0 0.2rem rgba(220,53,69,.25);
}
.btn-cancel:disabled, .btn-cancel.disabled {
    background-color: #f8d7da !important;
    border-color: #f8d7da !important;
    color: #fff !important;
}

.btn-pdt-cancel {
    background-color: #fd7e14 !important;
    color: #fff !important;
    border: 1px solid #fd7e14 !important;
}
.btn-pdt-cancel:hover, .btn-pdt-cancel:focus {
    background-color: #b02a37 !important;
    border-color: #b02a37 !important;
    color: #fff !important;
    box-shadow: 0 0 0 0.2rem rgba(253,126,20,.25);
}
.btn-pdt-cancel:disabled, .btn-pdt-cancel.disabled {
    background-color: #ffe5d0 !important;
    border-color: #ffe5d0 !important;
    color: #fff !important;
}

.btn-incluNoData {
    background-color: #fff !important;
    color: #212529 !important;
    border: 1px solid #dee2e6 !important;
}
.btn-incluNoData:hover, .btn-incluNoData:focus {
    background-color: #f8f9fa !important;
    border-color: #dee2e6 !important;
    color: #212529 !important;
    box-shadow: 0 0 0 0.2rem rgba(222,226,230,.25);
}
.btn-incluNoData:disabled, .btn-incluNoData.disabled {
    background-color: #f9f9f9 !important;
    border-color: #dee2e6 !important;
    color: #adb5bd !important;
}

.btn-incluSolicitado {
    background-color: #800080 !important;
    color: #fff !important;
    border: 1px solid #800080 !important;
}
.btn-incluSolicitado:hover, .btn-incluSolicitado:focus {
    background-color: #660066 !important;
    border-color: #660066 !important;
    color: #fff !important;
    box-shadow: 0 0 0 0.2rem rgba(128,0,128,.25);
}
.btn-incluSolicitado:disabled, .btn-incluSolicitado.disabled {
    background-color: #e0b3e0 !important;
    border-color: #e0b3e0 !important;
    color: #fff !important;
}

.btn-ligaPendiente {
    background-color: #26d2c7 !important;
    color: #fff !important;
    border: 1px solid #26d2c7 !important;
}
.btn-ligaPendiente:hover, .btn-ligaPendiente:focus {
    background-color: #24a0ae !important;
    border-color: #24a0ae !important;
    color: #fff !important;
    box-shadow: 0 0 0 0.2rem rgba(38,210,199,.25);
}
.btn-ligaPendiente:disabled, .btn-ligaPendiente.disabled {
    background-color: #e0f7fa !important;
    border-color: #e0f7fa !important;
    color: #fff !important;
}

.btn-pagoPendiente {
    background-color: #ffc107 !important;
    color: #212529 !important;
    border: 1px solid #ffc107 !important;
}
.btn-pagoPendiente:hover, .btn-pagoPendiente:focus {
    background-color: #ffca2c !important;
    border-color: #ffca2c !important;
    color: #212529 !important;
    box-shadow: 0 0 0 0.2rem rgba(255,193,7,.25);
}
.btn-pagoPendiente:disabled, .btn-pagoPendiente.disabled {
    background-color: #fff3cd !important;
    border-color: #fff3cd !important;
    color: #212529 !important;
}

.btn-pagadoSinIngresar {
    background-color: #fff3cd !important;
    color: #212529 !important;
    border: 1px solid #ffe066 !important;
}
.btn-pagadoSinIngresar:hover, .btn-pagadoSinIngresar:focus {
    background-color: #ffe066 !important;
    border-color: #ffe066 !important;
    color: #212529 !important;
    box-shadow: 0 0 0 0.2rem rgba(255,240,102,.25);
}
.btn-pagadoSinIngresar:disabled, .btn-pagadoSinIngresar.disabled {
    background-color: #fffbe6 !important;
    border-color: #fffbe6 !important;
    color: #adb5bd !important;
}

.btn-pagadoRegistradoAtpm {
    background-color: #198754 !important;
    color: #fff !important;
    border: 1px solid #198754 !important;
}
.btn-pagadoRegistradoAtpm:hover, .btn-pagadoRegistradoAtpm:focus {
    background-color: #146c43 !important;
    border-color: #146c43 !important;
    color: #fff !important;
    box-shadow: 0 0 0 0.2rem rgba(25,135,84,.25);
}
.btn-pagadoRegistradoAtpm:disabled, .btn-pagadoRegistradoAtpm.disabled {
    background-color: #c8e6c9 !important;
    border-color: #c8e6c9 !important;
    color: #fff !important;
}

.btn-pagadoRegistradoOlcp {
    background-color: #87ceeb !important;
    color: #fff !important;
    border: 1px solid #87ceeb !important;
}
.btn-pagadoRegistradoOlcp:hover, .btn-pagadoRegistradoOlcp:focus {
    background-color: #6bb6d8 !important;
    border-color: #6bb6d8 !important;
    color: #fff !important;
    box-shadow: 0 0 0 0.2rem rgba(135,206,235,.25);
}
.btn-pagadoRegistradoOlcp:disabled, .btn-pagadoRegistradoOlcp.disabled {
    background-color: #d1ecf8 !important;
    border-color: #d1ecf8 !important;
    color: #fff !important;
}

.pagination {
    margin-top: 20px;
    text-align: center;
}
.pagination li {
    display: inline-block;
    margin-right: 5px;
}
.pagination .page-link {
    padding: 5px 10px;
    background-color: transparent;
    color: var(--bs-primary, #0d6efd);
    text-decoration: none;
    border: 1px solid var(--bs-primary, #0d6efd);
    border-radius: 0.25rem;
    transition: background-color 0.3s, color 0.3s;
}
.pagination .page-link:hover {
    background-color: var(--bs-primary, #0d6efd);
    color: #fff;
}
.pagination .active .page-link, .pagination .page-item.active .page-link {
    background-color: var(--bs-primary, #0d6efd);
    color: #fff;
    border-color: var(--bs-primary, #0d6efd);
    pointer-events: none;
}

.table-sm-custom {
    font-size: 0.75rem;
    width: 50%;
}
.table-sm-custom td, .table-sm-custom th {
    padding: 0.3rem;
}

.dropdown-menu {
    position: absolute !important;
    will-change: transform;
    border-radius: 0.375rem;
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
    background-color: var(--bs-dropdown-bg, #fff);
    border: 1px solid var(--bs-border-color, #dee2e6);
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
    transition: box-shadow 0.2s, transform 0.2s;
}
.btn:hover, .btn:focus-visible {
    transform: translateY(-1px);
    box-shadow: 0 4px 16px rgba(13,110,253,0.10);
}

/* Input focus styles */
.form-control:focus, .form-select:focus {
    border-color: var(--bs-primary, #0d6efd);
    box-shadow: 0 0 0 0.2rem rgba(13,110,253,.25);
}

/* Card shadow on hover */
.card:hover {
    box-shadow: 0 8px 25px rgba(13,110,253,0.07) !important;
    transition: box-shadow 0.3s ease;
}


</style>
