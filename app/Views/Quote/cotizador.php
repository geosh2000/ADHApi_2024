<?= $this->extend('app/layout/layout') ?>
<?php $this->section('title') ?>Cotizador<?php $this->endSection() ?>
<?php $this->section('pageTitle') ?>Cotizador<?php $this->endSection() ?>

<?= $this->section('content') ?>
<?php
// Arrays proporcionados por el usuario
$hotels = [
    '1' => 'Atelier Playa Mujeres',
    '5' => 'Oleo Cancún Playa',
];

$languages = [
    'ESP' => 'Español',
    'ENG' => 'Inglés',
];

$currencies = [
    'MXN',
    'USD',
];

// Mapeo para construir URL iframe según hotel y moneda
$mapHotel = [
    'MXN' => [
        '1' => 2,
        '5' => 6,
    ],
    'USD' => [
        '1' => 1,
        '5' => 5,
    ],
];

// Obtener valores desde $_GET o usar por defecto
$defaultHotel = $_GET['hotel'] ?? '';
$defaultLanguage = $_GET['idioma'] ?? '';
$defaultCurrency = $_GET['moneda'] ?? '';
$defaultCheckin = $_GET['checkin'] ?? '';
$defaultCheckout = $_GET['checkout'] ?? '';
$defaultRooms = $_GET['rooms'] ?? '';
$defaultAdults = $_GET['adults'] ?? '';
$defaultKids = $_GET['kids'] ?? '';
$defaultAdults2 = $_GET['adults2'] ?? '';
$defaultKids2 = $_GET['kids2'] ?? '';
$defaultAge21Val = $_GET['Age21Val'] ?? '';
$defaultAge22Val = $_GET['Age22Val'] ?? '';

// Función para obtener nombre legible por id
function getNameById($array, $id) {
    return $array[$id] ?? $id;
}

// Construir URL iframe según combinación hotel, moneda e idioma y otros filtros
$iframeUrl = 'https://atelier-cc.azurewebsites.net/public/blank.html';
if ($defaultCurrency && $defaultHotel && isset($mapHotel[$defaultCurrency]) && isset($mapHotel[$defaultCurrency][$defaultHotel])) {
    $id = $mapHotel[$defaultCurrency][$defaultHotel];
    $idioma = $defaultLanguage;
    $moneda = $defaultCurrency;
    $hotel = $defaultHotel;
    $params = [
        "language=$idioma",
        "currency=$moneda",
        "hotel=$id",
        "frameshow=1",
        "companysource=callcenter"
    ];
    if ($defaultCheckin) {
        $params[] = "checkin=" . urlencode($defaultCheckin);
    }
    if ($defaultCheckout) {
        $params[] = "checkout=" . urlencode($defaultCheckout);
    }
    if ($defaultRooms) {
        $params[] = "rooms=" . urlencode($defaultRooms);
    }
    if ($defaultAdults) {
        $params[] = "adults=" . urlencode($defaultAdults);
    }
    if ($defaultKids) {
        $params[] = "kids=" . urlencode($defaultKids);
    }
    if ($defaultAdults2) {
        $params[] = "adults2=" . urlencode($defaultAdults2);
    }
    if ($defaultKids2) {
        $params[] = "kids2=" . urlencode($defaultKids2);
    }
    if ($defaultKids2 > 0) {
        if ($defaultAge21Val) {
            $params[] = "Age21Val=" . urlencode($defaultAge21Val);
        }
        if ($defaultAge22Val) {
            $params[] = "Age22Val=" . urlencode($defaultAge22Val);
        }
    }
    $iframeUrl = "https://reserve.atelierdehoteles.com/?" . implode('&', $params);
}
?>
<style>
    main {
        position: absolute;
        top: var(--navbar-height, 30px);
        left: var(--sidebar-width, 35px);
        right: 0;
        bottom: 0;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        padding: 0;
        margin: 0;
        overflow: hidden;
    }
    #filtersContainer {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: flex-start;
        gap: 1rem;
        padding: 0.5rem 1rem;
        background: transparent;
        border-bottom: 1px solid #ddd;
        flex-shrink: 0;
    }
    #filtersForm {
        display: flex;
        gap: 1rem;
        align-items: center;
        margin: 0;
        flex-wrap: wrap;
    }
    /* Ajuste inputs moneda/idioma más angostos */
    #filtersForm #currencyFilter,
    #filtersForm #languageFilter {
        min-width: 70px;
        max-width: 90px;
        width: 90px;
        display: inline-block;
    }
    /* Botón resumen habitaciones más largo y alineado */
    #filtersForm #roomSummaryBtn {
        min-width: 180px;
        max-width: 240px;
        width: 220px;
        text-align: left;
        margin-top: 0px;
        margin-bottom: 0px;
        vertical-align: middle;
    }
    /* Alinear verticalmente los selects y botón */
    #filtersForm > div {
        display: flex;
        align-items: center;
    }
    #badgesContainer {
        display: flex;
        gap: 0.5rem;
        align-items: center;
        margin-left: auto;
    }
    #cotizadorIframe {
        flex-grow: 1;
        width: 100%;
        border: 0;
        margin: 0;
        padding: 0;
        display: block;
    }
    @media (max-width: 576px) {
        #filtersContainer {
            justify-content: center;
        }
        #badgesContainer {
            margin-left: 0;
            margin-top: 0.5rem;
            width: 100%;
            justify-content: center;
        }
    }
</style>

<!-- Agregar CSS de daterangepicker -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<main>
    <div id="filtersContainer">
        <form id="filtersForm" autocomplete="off">
            <div>
                <label class="form-label mb-0 me-1" for="hotelFilter"><i class="bi bi-building"></i></label>
                <select class="form-select form-select-sm" id="hotelFilter" name="hotel" aria-label="Filtro de Hotel">
                    <option value="">Seleccione Hotel</option>
                    <?php foreach ($hotels as $key => $name): ?>
                        <option value="<?= esc($key) ?>"<?= $key === $defaultHotel ? ' selected' : '' ?>><?= esc($name) ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div>
                <label class="form-label mb-0 me-1" for="currencyFilter"><i class="bi bi-currency-exchange"></i></label>
                <select class="form-select form-select-sm" id="currencyFilter" name="moneda" aria-label="Filtro de Moneda">
                    <option value="">Moneda</option>
                    <?php foreach ($currencies as $currency): ?>
                        <option value="<?= esc($currency) ?>"<?= $currency === $defaultCurrency ? ' selected' : '' ?>><?= esc($currency) ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div>
                <label class="form-label mb-0 me-1" for="languageFilter"><i class="bi bi-translate"></i></label>
                <select class="form-select form-select-sm" id="languageFilter" name="idioma" aria-label="Filtro de Idioma">
                    <option value="">Idioma</option>
                    <?php foreach ($languages as $key => $name): ?>
                        <option value="<?= esc($key) ?>"<?= $key === $defaultLanguage ? ' selected' : '' ?>><?= esc($name) ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div>
                <label class="form-label mb-0 me-1" for="dateRangeFilter"><i class="bi bi-calendar-range"></i></label>
                <input type="text" class="form-control form-control-sm" id="dateRangeFilter" aria-label="Filtro de Rango de Fechas" style="min-width: 180px;" readonly />
                <!-- Inputs ocultos para compatibilidad -->
                <input type="hidden" id="checkinFilter" name="checkin" value="<?= esc($defaultCheckin) ?>">
                <input type="hidden" id="checkoutFilter" name="checkout" value="<?= esc($defaultCheckout) ?>">
            </div>
            <!-- Resumen habitaciones y modal -->
            <div class="d-flex align-items-center" style="gap:0.5rem;">
                <button type="button" class="btn btn-primary btn-sm" id="roomSummaryBtn">
                    <i class="bi bi-people"></i>
                    <span id="roomSummaryText"></span>
                </button>
                <!-- Nuevo botón: abrir cotizador en nueva ventana -->
                <button type="button" class="btn btn-outline-secondary btn-sm" id="openCotizadorNewTabBtn" title="Abrir cotizador en nueva ventana">
                    <i class="bi bi-arrow-up-right-square"></i>
                </button>
                <!-- Botón buscar cotizaciones -->
                <!-- <button type="button" class="btn btn-success btn-sm" id="searchCotizadorBtn" title="Buscar cotizaciones">
                    <i class="bi bi-search"></i> Buscar
                </button> -->
                <input type="hidden" id="roomsData" name="roomsData" />
            </div>
        </form>
        <div id="badgesContainer">
            <span class="badge rounded-pill bg-primary" id="badgeHotel"><?= esc(getNameById($hotels, $defaultHotel)) ?></span>
            <span class="badge rounded-pill bg-success" id="badgeCurrency"><?= esc($defaultCurrency ?: '') ?></span>
            <span class="badge rounded-pill bg-info text-dark" id="badgeLanguage"><?= esc(getNameById($languages, $defaultLanguage)) ?></span>
            <!-- Botón de códigos con dropdown -->
            <div class="dropdown ms-2" id="codesDropdownContainer">
                <button class="btn btn-outline-warning btn-sm dropdown-toggle d-flex align-items-center" type="button" id="codesDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-percent me-1"></i> Códigos
                </button>
                <ul class="dropdown-menu" id="codesDropdownMenu" aria-labelledby="codesDropdownBtn">
                    <?php if (isset($codes) && is_array($codes)): ?>
                        <?php
                        $hotelId = $defaultHotel;
                        $codesForHotel = [];
                        foreach ($codes as $c) {
                            if (isset($c['hotel_id']) && $c['hotel_id'] == $hotelId) {
                                $codesForHotel[] = $c;
                            }
                        }
                        ?>
                        <?php if (!empty($codesForHotel)): ?>
                            <?php foreach ($codesForHotel as $code): ?>
                                <li>
                                    <a class="dropdown-item code-copy-item" href="#" data-code="<?= esc($code['code']) ?>">
                                        <strong><?= esc($code['code']) ?></strong>
                                        <?php if (isset($code['discount'])): ?>
                                            <span class="text-muted ms-2"><?= esc($code['discount']) ?>% dto.</span>
                                        <?php endif ?>
                                    </a>
                                </li>
                            <?php endforeach ?>
                        <?php else: ?>
                            <li><span class="dropdown-item-text text-muted">No hay códigos para este hotel</span></li>
                        <?php endif ?>
                    <?php else: ?>
                        <li><span class="dropdown-item-text text-muted">No hay códigos disponibles</span></li>
                    <?php endif ?>
                </ul>
            </div>
        </div>


<script>
// --- Códigos de descuento ---
const codes = <?= isset($codes) ? json_encode($codes) : '{}' ?>;
</script>
    </div>
    <!-- Cintillo de alerta para Atelier + niños -->
    <div id="atelierKidsAlert" class="alert alert-warning" style="display: none;">
        <i class="bi bi-exclamation-triangle"></i>
        Recuerde: Para Atelier Playa Mujeres, es importante verificar las políticas para niños. Por favor revise las condiciones antes de continuar con la reserva.
    </div>
    <iframe id="cotizadorIframe" src="<?= esc($iframeUrl) ?>" frameborder="0"></iframe>

    <!-- Modal habitaciones -->
    <div class="modal fade" id="roomsModal" tabindex="-1" aria-labelledby="roomsModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="roomsModalLabel"><i class="bi bi-people"></i> Configurar habitaciones</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            <form id="roomsModalForm" autocomplete="off">
                <div id="roomsModalRoomsContainer"></div>
                <div class="d-flex justify-content-between mt-3">
                    <button type="button" id="addRoomBtn" class="btn btn-outline-success btn-sm"><i class="bi bi-plus-circle"></i> Agregar habitación</button>
                    <button type="button" id="removeRoomBtn" class="btn btn-outline-danger btn-sm"><i class="bi bi-dash-circle"></i> Quitar habitación</button>
                </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" id="saveRoomsConfigBtn">Guardar</button>
          </div>
        </div>
      </div>
    </div>
</main>

<!-- Incluir librerías moment y daterangepicker -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
// Mapas para mostrar nombre legible en los badges
const hotels = <?= json_encode($hotels) ?>;
const languages = <?= json_encode($languages) ?>;
const currencies = <?= json_encode(array_combine($currencies, $currencies)) ?>;
const mapHotel = <?= json_encode($mapHotel) ?>;

// Por defecto: 1 hab, 2 adultos, 0 niños
const defaultRoomsConfig = [
    { adults: 2, kids: 0, kidsAges: [] }
];

function getRoomsConfigFromInput() {
    try {
        const val = document.getElementById('roomsData').value;
        if (!val) return JSON.parse(JSON.stringify(defaultRoomsConfig));
        const arr = JSON.parse(val);
        // Sanitizar: asegurar al menos 1 habitación
        if (!Array.isArray(arr) || arr.length === 0) return JSON.parse(JSON.stringify(defaultRoomsConfig));
        return arr;
    } catch(e) {
        return JSON.parse(JSON.stringify(defaultRoomsConfig));
    }
}

function setRoomsConfigToInput(cfg) {
    document.getElementById('roomsData').value = JSON.stringify(cfg);
}

function updateRoomSummaryText() {
    const rooms = getRoomsConfigFromInput();
    let totalAdults = 0, totalKids = 0;
    rooms.forEach(r => {
        totalAdults += parseInt(r.adults)||0;
        totalKids += parseInt(r.kids)||0;
    });
    let txt = `${totalAdults} adulto${totalAdults===1?'':'s'}`;
    if (totalKids > 0) txt += `, ${totalKids} niño${totalKids===1?'':'s'}`;
    txt += `, ${rooms.length} hab${rooms.length===1?'':'s'}`;
    document.getElementById('roomSummaryText').textContent = txt;
}

function updateIframeAndBadges() {
    const hotel = document.getElementById('hotelFilter').value;
    const currency = document.getElementById('currencyFilter').value;
    const language = document.getElementById('languageFilter').value;
    const checkin = document.getElementById('checkinFilter').value;
    const checkout = document.getElementById('checkoutFilter').value;
    const roomsConfig = getRoomsConfigFromInput();
    let params = [
        `language=${encodeURIComponent(language)}`,
        `currency=${encodeURIComponent(currency)}`
    ];
    let iframeUrl = 'https://atelier-cc.azurewebsites.net/public/blank.html';
    console.log('Refresh iframe', currency, hotel, mapHotel[currency], mapHotel[currency][hotel]);
    if (currency && hotel && mapHotel[currency] && mapHotel[currency][hotel]) {
        const id = mapHotel[currency][hotel];
        params.push(`hotel=${encodeURIComponent(id)}`);
        params.push(`frameshow=1`);
        params.push(`companysource=callcenter`);
        if (checkin) params.push(`checkin=${encodeURIComponent(checkin)}`);
        if (checkout) params.push(`checkout=${encodeURIComponent(checkout)}`);
        // Armar parámetros de habitaciones
        params.push(`rooms=${roomsConfig.length}`);
        roomsConfig.forEach((r, i) => {
            params.push(`adults${i+1>1?i+1:''}=${r.adults}`);
            params.push(`kids${i+1>1?i+1:''}=${r.kids}`);
            if (r.kids > 0 && Array.isArray(r.kidsAges)) {
                for (let k = 0; k < r.kids; ++k) {
                    params.push(`Age${i+1}${k+1}Val=${r.kidsAges[k] ?? ''}`);
                }
            }
        });
        iframeUrl = `https://reserve.atelierdehoteles.com/?${params.join('&')}`;
    }
    document.getElementById('cotizadorIframe').src = iframeUrl;
    // Actualizar badges
    document.getElementById('badgeHotel').textContent = hotels[hotel] || '';
    document.getElementById('badgeCurrency').textContent = currencies[currency] || '';
    document.getElementById('badgeLanguage').textContent = languages[language] || '';

    // Mostrar/ocultar cintillo de alerta si hotel === '1' y hay al menos un niño
    let showAlert = false;
    if (hotel === '1') {
        let totalKids = 0;
        roomsConfig.forEach(r => { totalKids += parseInt(r.kids)||0; });
        if (totalKids > 0) showAlert = true;
    }
    var alertDiv = document.getElementById('atelierKidsAlert');
    if (alertDiv) {
        alertDiv.style.display = showAlert ? '' : 'none';
    }
}

function renderRoomsModalRooms(cfg) {
    const container = document.getElementById('roomsModalRoomsContainer');
    container.innerHTML = '';
    cfg.forEach((room, idx) => {
        let kidsAgesHtml = '';
        // Siempre mostrar inputs de edad para el número de niños actual (0-4)
        for (let i = 0; i < room.kids; ++i) {
            kidsAgesHtml += `
                <div class="col-6 col-md-3 mb-1">
                    <label class="form-label mb-0" for="room${idx}_kidAge${i}" style="font-size:0.85em;">Edad niño ${i+1}</label>
                    <input type="number" min="0" max="11" class="form-control form-control-sm input-number-compact" id="room${idx}_kidAge${i}" value="${room.kidsAges[i] !== undefined ? room.kidsAges[i] : ''}" style="max-width:70px;display:inline-block;" />
                </div>
            `;
        }
        container.innerHTML += `
        <fieldset class="border rounded p-2 mb-2">
            <legend class="float-none w-auto fs-6 px-2 mb-1">Habitación ${idx+1}</legend>
            <div class="row g-1 align-items-end">
                <div class="col-6 col-md-3 mb-1">
                    <label class="form-label mb-0" for="room${idx}_adults" style="font-size:0.85em;">Adultos</label>
                    <input type="number" min="1" max="4" class="form-control form-control-sm input-number-compact" id="room${idx}_adults" value="${room.adults}" style="max-width:70px;display:inline-block;" />
                </div>
                <div class="col-6 col-md-3 mb-1">
                    <label class="form-label mb-0" for="room${idx}_kids" style="font-size:0.85em;">Niños</label>
                    <input type="number" min="0" max="4" class="form-control form-control-sm input-number-compact" id="room${idx}_kids" value="${room.kids}" style="max-width:70px;display:inline-block;" />
                </div>
                ${kidsAgesHtml}
            </div>
        </fieldset>
        `;
    });
}

function getRoomsModalFormConfig() {
    // Lee los valores actuales del formulario modal (inputs)
    const rooms = [];
    let idx = 0;
    while (true) {
        const adultsInput = document.getElementById(`room${idx}_adults`);
        const kidsInput = document.getElementById(`room${idx}_kids`);
        if (!adultsInput || !kidsInput) break;
        const adults = parseInt(adultsInput.value) || 1;
        const kids = parseInt(kidsInput.value) || 0;
        const kidsAges = [];
        for (let k = 0; k < kids; ++k) {
            const ageInput = document.getElementById(`room${idx}_kidAge${k}`);
            let age = '';
            if (ageInput) {
                age = parseInt(ageInput.value);
                if (isNaN(age)) age = '';
            }
            kidsAges.push(age);
        }
        rooms.push({ adults, kids, kidsAges });
        idx++;
    }
    return rooms;
}

function validateRoomsConfig(cfg) {
    // Cada habitación: adultos 1-4, niños 0-4, edad de niños 0-11
    if (!Array.isArray(cfg) || cfg.length < 1 || cfg.length > 5) return false;
    for (const r of cfg) {
        if (typeof r.adults !== 'number' || r.adults < 1 || r.adults > 4) return false;
        if (typeof r.kids !== 'number' || r.kids < 0 || r.kids > 4) return false;
        if (r.kids > 0) {
            if (!Array.isArray(r.kidsAges)) return false;
            if (r.kidsAges.length !== r.kids) return false;
            for (let age of r.kidsAges) {
                if (typeof age !== 'number' || age < 0 || age > 11) return false;
            }
        }
    }
    return true;
}

document.addEventListener('DOMContentLoaded', function () {

    // Función para construir la URL del cotizador con los filtros actuales
    function buildCotizadorUrl() {
        const hotel = document.getElementById('hotelFilter').value;
        const currency = document.getElementById('currencyFilter').value;
        const language = document.getElementById('languageFilter').value;
        const checkin = document.getElementById('checkinFilter').value;
        const checkout = document.getElementById('checkoutFilter').value;
        const roomsConfig = getRoomsConfigFromInput();
        let params = [
            `language=${encodeURIComponent(language)}`,
            `currency=${encodeURIComponent(currency)}`
        ];
        let url = 'https://atelier-cc.azurewebsites.net/public/blank.html';
        if (currency && hotel && mapHotel[currency] && mapHotel[currency][hotel]) {
            const id = mapHotel[currency][hotel];
            params.push(`hotel=${encodeURIComponent(id)}`);
            params.push(`frameshow=1`);
            params.push(`companysource=callcenter`);
            if (checkin) params.push(`checkin=${encodeURIComponent(checkin)}`);
            if (checkout) params.push(`checkout=${encodeURIComponent(checkout)}`);
            // Armar parámetros de habitaciones
            params.push(`rooms=${roomsConfig.length}`);
            roomsConfig.forEach((r, i) => {
                params.push(`adults${i+1>1?i+1:''}=${r.adults}`);
                params.push(`kids${i+1>1?i+1:''}=${r.kids}`);
                if (r.kids > 0 && Array.isArray(r.kidsAges)) {
                    for (let k = 0; k < r.kids; ++k) {
                        params.push(`Age${i+1}${k+1}Val=${r.kidsAges[k] ?? ''}`);
                    }
                }
            });
            url = `https://reserve.atelierdehoteles.com/?${params.join('&')}`;
        }
        return url;
    }

    // Esperar a que jQuery esté disponible (lo carga el layout)
    function initDateRangePickerWhenReady() {
        if (typeof window.jQuery === 'undefined' || typeof window.jQuery.fn.daterangepicker === 'undefined') {
            setTimeout(initDateRangePickerWhenReady, 50);
            return;
        }
        var $ = window.jQuery;
        const $dateRangeInput = $('#dateRangeFilter');
        // Valores iniciales para daterangepicker
        let startDate = moment("<?= esc($defaultCheckin ?: '') ?>", "YYYY-MM-DD", true);
        let endDate = moment("<?= esc($defaultCheckout ?: '') ?>", "YYYY-MM-DD", true);
        if (!startDate.isValid()) startDate = moment();
        if (!endDate.isValid()) endDate = moment().add(1, 'days');
        // Establecer valor inicial en el input visible
        $dateRangeInput.val(startDate.format("DD MMM 'YY") + ' - ' + endDate.format("DD MMM 'YY"));
        // Establecer valores iniciales en inputs ocultos
        document.getElementById('checkinFilter').value = startDate.format('YYYY-MM-DD');
        document.getElementById('checkoutFilter').value = endDate.format('YYYY-MM-DD');
        $dateRangeInput.daterangepicker({
            startDate: startDate,
            endDate: endDate,
            autoUpdateInput: true,
            locale: {
                format: "DD MMM 'YY",
                cancelLabel: 'Clear'
            }
        }, function(start, end) {
            // Actualizar inputs ocultos cuando cambie el rango
            document.getElementById('checkinFilter').value = start.format('YYYY-MM-DD');
            document.getElementById('checkoutFilter').value = end.format('YYYY-MM-DD');
            updateIframeAndBadges();
        });
    }
    initDateRangePickerWhenReady();

    // Inicializar roomsData por primera vez si vacío
    if (!document.getElementById('roomsData').value) {
        setRoomsConfigToInput(defaultRoomsConfig);
    }
    updateRoomSummaryText();

    // Modal Bootstrap 5
    let roomsModal;
    function getRoomsModal() {
        if (!roomsModal) {
            roomsModal = new bootstrap.Modal(document.getElementById('roomsModal'), { backdrop: 'static' });
        }
        return roomsModal;
    }

    // Botón para abrir modal
    document.getElementById('roomSummaryBtn').addEventListener('click', function(){
        // Renderizar modal con config actual
        const cfg = getRoomsConfigFromInput();
        renderRoomsModalRooms(cfg);
        // Habilitar/deshabilitar add/remove según cantidad
        document.getElementById('addRoomBtn').disabled = cfg.length >= 5;
        document.getElementById('removeRoomBtn').disabled = cfg.length <= 1;
        getRoomsModal().show();
    });

    // Agregar habitación
    document.getElementById('addRoomBtn').addEventListener('click', function(e){
        e.preventDefault();
        let cfg = getRoomsModalFormConfig();
        if (cfg.length < 5) {
            cfg.push({ adults: 2, kids: 0, kidsAges: [] });
            renderRoomsModalRooms(cfg);
            document.getElementById('addRoomBtn').disabled = cfg.length >= 5;
            document.getElementById('removeRoomBtn').disabled = cfg.length <= 1;
        }
    });
    // Quitar habitación
    document.getElementById('removeRoomBtn').addEventListener('click', function(e){
        e.preventDefault();
        let cfg = getRoomsModalFormConfig();
        if (cfg.length > 1) {
            cfg.pop();
            renderRoomsModalRooms(cfg);
            document.getElementById('addRoomBtn').disabled = cfg.length >= 5;
            document.getElementById('removeRoomBtn').disabled = cfg.length <= 1;
        }
    });

    // Inputs dinámicos: volver a renderizar kidsAges cuando cambia # de niños
    document.getElementById('roomsModalRoomsContainer').addEventListener('input', function(e){
        // Si input es de niños, re-renderizar
        if (e.target && e.target.id && e.target.id.match(/^room\d+_kids$/)) {
            let cfg = getRoomsModalFormConfig();
            // Clamp niños 0-4, adultos 1-4
            cfg = cfg.map(r => ({
                adults: Math.min(Math.max(r.adults, 1), 4),
                kids: Math.min(Math.max(r.kids, 0), 4),
                kidsAges: (r.kidsAges||[]).slice(0, Math.min(Math.max(r.kids,0),4))
            }));
            renderRoomsModalRooms(cfg);
            document.getElementById('addRoomBtn').disabled = cfg.length >= 5;
            document.getElementById('removeRoomBtn').disabled = cfg.length <= 1;
        }
    });

    // Guardar configuración
    document.getElementById('saveRoomsConfigBtn').addEventListener('click', function(){
        let cfg = getRoomsModalFormConfig();
        // Clamp y limpiar
        cfg = cfg.map(r => ({
            adults: Math.min(Math.max(r.adults, 1), 4),
            kids: Math.min(Math.max(r.kids, 0), 4),
            kidsAges: (r.kidsAges||[]).map(age => Math.min(Math.max(age,0),11)).slice(0, Math.min(Math.max(r.kids,0),4))
        }));
        // Validar
        if (!validateRoomsConfig(cfg)) {
            alert('Verifique que cada habitación tenga de 1 a 4 adultos, 0 a 4 niños (edades 0-11).');
            return;
        }
        setRoomsConfigToInput(cfg);
        updateRoomSummaryText();
        updateIframeAndBadges();
        getRoomsModal().hide();
    });

    // --- Códigos: Dropdown dinámico y copiar al portapapeles ---
    function refreshCodesDropdown() {
        var hotel = document.getElementById('hotelFilter').value;
        var currency = document.getElementById('currencyFilter').value;
        var codesDropdownBtn = document.getElementById('codesDropdownBtn');
        var codesDropdownMenu = document.getElementById('codesDropdownMenu');
        codesDropdownMenu.innerHTML = '';
        var hasCodes = false;
        // Validar que hotel exista y que codes[hotel] no sea undefined
        if (
            typeof codes === 'object' &&
            codes !== null &&
            hotel &&
            typeof codes[hotel] !== 'undefined'
        ) {
            mappedHotel = mapHotel[currency][hotel];
            var codesForHotel = codes[mappedHotel];
            console.log("codesForHotel", mappedHotel, codesForHotel);

            if (codesForHotel && Object.keys(codesForHotel).length > 0) {
                hasCodes = true;

                Object.keys(codesForHotel).forEach(function(label) {
                    var codeObj = codesForHotel[label];
                    console.log("codeObj", codeObj);

                    var span = '<span class="text-muted ms-2">' + codeObj.descuento + ' dto.</span>';

                    var li = document.createElement('li');
                    var a = document.createElement('a');
                    a.className = 'dropdown-item code-copy-item';
                    a.href = '#';
                    a.dataset.code = codeObj.code; // solo copiar el código
                    a.dataset.discount = codeObj.descuento;
                    a.innerHTML = '<strong>' + label + ': ' + codeObj.code + '</strong>' + span;
                    li.appendChild(a);
                    codesDropdownMenu.appendChild(li);
                });
            }
        }
        if (!hasCodes) {
            var li = document.createElement('li');
            var span = document.createElement('span');
            span.className = 'dropdown-item-text text-muted';
            span.textContent = 'No hay códigos disponibles';
            li.appendChild(span);
            codesDropdownMenu.appendChild(li);
        }
        codesDropdownBtn.disabled = false;
    }
    // Inicializar dropdown de códigos al cargar
    refreshCodesDropdown();
    // Delegación de evento para copiar código al portapapeles
    document.getElementById('codesDropdownMenu').addEventListener('click', function(e){
        var target = e.target;
        // Si se hace click en <strong> dentro del <a>
        if (target.tagName === 'STRONG' && target.parentElement.classList.contains('code-copy-item')) {
            target = target.parentElement;
        }
        if (target.classList.contains('code-copy-item')) {
            e.preventDefault();
            var code = target.dataset.code;
            if (code) {
                // Copiar al portapapeles
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(code).then(function(){
                        target.classList.add('bg-success','text-white');
                        target.innerHTML += ' <i class="bi bi-clipboard-check ms-1"></i>';
                        setTimeout(function(){
                            target.classList.remove('bg-success','text-white');
                            // target.innerHTML = '<strong>' + code + '</strong>' +
                            //     (target.dataset.discount ? '<span class="text-muted ms-2">' + target.dataset.discount + '% dto.</span>' : '');
                        }, 1200);
                    });
                } else {
                    // Fallback
                    var temp = document.createElement('input');
                    temp.value = code;
                    document.body.appendChild(temp);
                    temp.select();
                    document.execCommand('copy');
                    document.body.removeChild(temp);
                }
            }
        }
    });

    // Escuchar cambios en los filtros: actualizar resumen habitaciones, iframe, badges y dropdown de códigos según filtro
    document.getElementById('filtersForm').addEventListener('change', function (e) {
        if (e.target) {
            if (e.target.id === 'roomsData') {
                updateRoomSummaryText();
            }
            if (
                e.target.id === 'hotelFilter' ||
                e.target.id === 'currencyFilter' ||
                e.target.id === 'languageFilter'
            ) {
                updateIframeAndBadges();
                refreshCodesDropdown();
            }
        }
    });

    // Botón Buscar cotizaciones: actualizar iframe y códigos
    document.getElementById('searchCotizadorBtn').addEventListener('click', function () {
        updateIframeAndBadges();
        refreshCodesDropdown();
    });

    // Botón para abrir cotizador en nueva ventana
    document.getElementById('openCotizadorNewTabBtn').addEventListener('click', function(){
        const url = buildCotizadorUrl();
        window.open(url, '_blank');
    });
});
</script>
<style>
    /* Inputs más compactos para el modal de habitaciones */
    #roomsModal .form-control.input-number-compact {
        padding: 0.15rem 0.3rem;
        font-size: 0.95em;
        height: 1.85em;
        max-width: 70px;
        display: inline-block;
    }
    #roomsModal .form-label {
        font-size: 0.92em;
        margin-bottom: 0.1rem;
    }
    #roomsModal fieldset {
        padding: 0.7em 0.6em;
    }
    #roomsModal legend {
        font-size: 1em;
        padding: 0 0.5em;
    }
    #roomsModal .row > [class*="col-"] {
        padding-right: 0.3em;
        padding-left: 0.3em;
    }
    #roomsModal .mb-1 {
        margin-bottom: 0.3rem !important;
    }
    #roomsModal .mb-2 {
        margin-bottom: 0.5rem !important;
    }
</style>
<?= $this->endSection() ?>

    