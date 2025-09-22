<!DOCTYPE html>
<html lang="es">
<head>
    
     <!-- Matomo Tag Manager -->
    <script>
    var _mtm = window._mtm = window._mtm || [];
    _mtm.push({'mtm.startTime': (new Date().getTime()), 'event': 'mtm.Start'});
    (function() {
        var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
        g.async=true; g.src='https://matomo.geoshglobal.com/js/container_yOnzEg5O.js'; s.parentNode.insertBefore(g,s);
    })();
    </script>
    <!-- End Matomo Tag Manager -->
     <meta charset="UTF-8">
    <title><?= esc($title) ?></title>
    <link rel="icon" href="https://atelier-cc.azurewebsites.net/favicon-adh.ico">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    <main class="flex items-center justify-center min-h-screen relative bg-cover bg-center overflow-hidden" style="background-image: url('/path/to/your/image.jpg');">
        <div class="bg-white/30 backdrop-blur-md rounded-2xl shadow-xl p-10 max-w-7xl w-full lg:grid lg:grid-cols-4 gap-6 h-full">
            <aside class="hidden lg:block lg:col-span-1 bg-white/50 backdrop-blur-md rounded-2xl p-6 shadow-inner h-full max-h-[80vh] overflow-y-auto">
                <div class="flex space-x-4 border-b mb-4">
                    <button data-tab="history" class="pb-2 border-b-2 border-blue-700 text-blue-700 font-semibold">Últimas consultas</button>
                    <button data-tab="today" class="pb-2 text-gray-600 hover:text-blue-700">Servicios del día</button>
                </div>
                <section id="tab-history">
                    <h2 class="text-xl font-bold text-blue-700 mb-4">Últimas consultas</h2>
                    <ul id="consulta-history" class="space-y-1"></ul>
                </section>
                <section id="tab-today" class="hidden">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="relative w-full">
                            <input
                                id="filter-today-input"
                                type="text"
                                placeholder="Filtrar servicios por folio o huésped"
                                class="flex-grow px-3 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 text-sm pr-10"
                            >
                            <button id="clear-today-input" type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-500 focus:outline-none">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                        <button id="refresh-today" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded" title="Refrescar"><i class="fa-solid fa-arrows-rotate"></i></button>
                    </div>
                    <div id="loader-today" class="hidden text-center py-2"><i class="fas fa-spinner fa-spin text-blue-700 text-lg"></i></div>
                    <div id="today-sections" class="space-y-4">
                        <div>
                            <h3 class="text-lg font-semibold text-blue-700 mb-2">Entradas</h3>
                            <ul id="today-entradas" class="space-y-1"></ul>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-blue-700 mb-2">Salidas</h3>
                            <ul id="today-salidas" class="space-y-1"></ul>
                        </div>
                    </div>
                </section>
            </aside>

            <section class="lg:col-span-3">
                <!-- Formulario de búsqueda -->
                <form method="get" action="<?= site_url('public/consulta_transpo') ?>" class="max-w-xl mb-6">
                    <div class="flex items-center gap-2">
                        <input type="text" name="q" value="<?= esc($search) ?>"
                               placeholder="Ingresa folio o nombre"
                               class="flex-grow px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 text-lg shadow-sm">
                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-md text-lg">
                            <?= !empty($resultados) ? 'Nueva búsqueda' : 'Buscar' ?>
                        </button>
                    </div>
                </form>

                <!-- Resultados -->
                <section class="<?= ($search && empty($resultados)) ? 'text-center text-gray-600 text-lg' : '' ?>">
                    <?php if ($search && empty($resultados)): ?>
                        <p>No se encontraron resultados para "<strong><?= esc($search) ?></strong>"</p>
                    <?php endif; ?>

                    <?php if (!empty($resultados)): ?>
                        <?php
                            // Agrupar resultados por folio+item
                            $grouped = [];
                            foreach ($resultados as $r) {
                                $key = $r['folio'] . '-' . $r['item'];
                                if (!isset($grouped[$key])) {
                                    $grouped[$key] = [
                                        'folio' => $r['folio'],
                                        'item' => $r['item'],
                                        'guest' => $r['guest'],
                                        'hotel' => $r['hotel'],
                                        'status' => $r['status'],
                                        'correo' => $r['correo'] ?? '',
                                        'phone' => $r['phone'] ?? '',
                                        'precio' => $r['precio'] ?? '',
                                        'entrada' => null,
                                        'salida' => null,
                                    ];
                                }
                                if (strtolower($r['tipo']) === 'entrada') {
                                    $grouped[$key]['entrada'] = $r;
                                } elseif (strtolower($r['tipo']) === 'salida') {
                                    $grouped[$key]['salida'] = $r;
                                }
                            }
                        ?>
                        <?php
                            // Contar folios únicos
                            $uniqueFolios = array_unique(array_map(function($data){ return $data['folio']; }, $grouped));
                        ?>
                        <?php if (count($uniqueFolios) > 1): ?>
                            <div class="mb-6">
                                <p class="text-gray-700 mb-2">Se encontraron varios servicios para "<strong><?= esc($search) ?></strong>". Selecciona uno:</p>
                                <ul class="space-y-2">
                                    <?php foreach ($grouped as $key => $data): ?>
                                        <li
                                            class="px-2 py-1 rounded-md hover:bg-blue-100 text-blue-700 text-sm cursor-pointer"
                                            onclick="window.location.href = '<?= site_url('public/consulta_transpo') ?>?q=<?= urlencode($data['folio']) ?>';"
                                        >
                                            <?= esc($data['folio']) ?> - <?= esc($data['guest']) ?> (<?= esc($data['item']) ?>)
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php else: ?>
                            <div class="max-h-[90vh] overflow-y-auto pr-2">
                                <div class="space-y-6">
                                    <?php foreach ($grouped as $key => $data): ?>
                                        <?php
                                            $statusText = strtolower($data['status']);
                                            if (strpos($statusText, 'destino') !== false) {
                                                // efecto llamativo tipo warning que "flashee"
                                                $statusClass = 'bg-green-200 text-red-800 animate-pulse';
                                            } elseif (strpos($statusText, 'capturad') !== false) {
                                                $statusClass = 'bg-green-100 text-green-800'; // success
                                            } elseif (strpos($statusText, 'no facturado') !== false || strpos($statusText, 'cancelad') !== false) {
                                                $statusClass = 'bg-red-100 text-red-800'; // danger
                                            } elseif (strpos($statusText, 'pendiente') !== false) {
                                                // efecto llamativo tipo warning que "flashee"
                                                $statusClass = 'bg-yellow-200 text-yellow-800 animate-pulse';
                                            } else {
                                                $statusClass = 'bg-yellow-100 text-yellow-800'; // warn
                                            }
                                        ?>
                                        <div class="bg-white/30 backdrop-blur-md rounded-2xl shadow-xl p-6 w-full">
                                            <h2 class="text-xl font-bold text-blue-700 mb-4">
                                                Folio: <?= esc($data['folio']) ?> - <?= esc($data['guest']) ?> (<?= esc($data['item']) ?>)
                                            </h2>
                                            <div class="mb-6 grid grid-cols-2 gap-x-6 gap-y-2 text-gray-700 text-sm sm:text-base">
                                                <div><i class="fa-solid fa-hotel text-blue-700 mr-2"></i><?= esc($data['hotel']) ?></div>
                                                <div class="inline-block px-2 py-1 rounded <?= $statusClass ?>"><i class="fa-solid fa-tag mr-2"></i><?= esc($data['status']) ?></div>
                                                <div><i class="fa-solid fa-envelope text-red-600 mr-2"></i><?= esc($data['correo']) ?></div>
                                                <div><i class="fa-solid fa-phone text-purple-600 mr-2"></i><?= esc($data['phone']) ?></div>
                                            </div>
                                            <?php if ($data['entrada'] || $data['salida']): ?>
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                    <?php if ($data['entrada'] && !empty($data['entrada']['date'])): ?>
                                                        <div class="bg-white/80 rounded-lg p-4 mb-4 md:mb-0">
                                                            <h3 class="font-semibold text-lg mb-2">Entrada</h3>
                                                            <ul class="divide-y divide-gray-300 text-gray-700 text-sm sm:text-base">
                                                                <?php if (!empty($data['entrada']['date'])): ?>
                                                                    <li class="py-1"><i class="fa-regular fa-calendar text-indigo-600 mr-2"></i><?= esc($data['entrada']['date']) ?></li>
                                                                <?php endif; ?>
                                                                <?php if (!empty($data['entrada']['airline'])): ?>
                                                                    <li class="py-1"><i class="fa-solid fa-building text-gray-700 mr-2"></i><?= esc($data['entrada']['airline']) ?></li>
                                                                <?php endif; ?>
                                                                <?php if (!empty($data['entrada']['flight'])): ?>
                                                                    <li class="py-1"><i class="fa-solid fa-plane-departure text-blue-700 mr-2"></i><?= esc($data['entrada']['flight']) ?></li>
                                                                <?php endif; ?>
                                                                <?php if (!empty($data['entrada']['time'])): ?>
                                                                    <li class="py-1"><i class="fa-regular fa-clock text-yellow-600 mr-2"></i><?= esc($data['entrada']['time']) ?></li>
                                                                <?php endif; ?>
                                                                <?php if (!empty($data['entrada']['pick_up'])): ?>
                                                                    <li class="py-1"><i class="fa-solid fa-bus text-teal-600 mr-2"></i><?= esc($data['entrada']['pick_up']) ?></li>
                                                                <?php endif; ?>
                                                            </ul>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-lg mb-4 md:mb-0">
                                                            <h3 class="font-semibold text-lg mb-2">Entrada</h3>
                                                            <p>Servicio NO solicitado</p>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if ($data['salida'] && !empty($data['salida']['date'])): ?>
                                                        <div class="bg-white/80 rounded-lg p-4">
                                                            <h3 class="font-semibold text-lg mb-2">Salida</h3>
                                                            <ul class="divide-y divide-gray-300 text-gray-700 text-sm sm:text-base">
                                                                <?php if (!empty($data['salida']['date'])): ?>
                                                                    <li class="py-1"><i class="fa-regular fa-calendar text-indigo-600 mr-2"></i><?= esc($data['salida']['date']) ?></li>
                                                                <?php endif; ?>
                                                                <?php if (!empty($data['salida']['airline'])): ?>
                                                                    <li class="py-1"><i class="fa-solid fa-building text-gray-700 mr-2"></i><?= esc($data['salida']['airline']) ?></li>
                                                                <?php endif; ?>
                                                                <?php if (!empty($data['salida']['flight'])): ?>
                                                                    <li class="py-1"><i class="fa-solid fa-plane-departure text-blue-700 mr-2"></i><?= esc($data['salida']['flight']) ?></li>
                                                                <?php endif; ?>
                                                                <?php if (!empty($data['salida']['time'])): ?>
                                                                    <li class="py-1"><i class="fa-regular fa-clock text-yellow-600 mr-2"></i><?= esc($data['salida']['time']) ?></li>
                                                                <?php endif; ?>
                                                                <?php if (!empty($data['salida']['pick_up'])): ?>
                                                                    <li class="py-1"><i class="fa-solid fa-bus text-teal-600 mr-2"></i><?= esc($data['salida']['pick_up']) ?></li>
                                                                <?php endif; ?>
                                                            </ul>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-lg">
                                                            <h3 class="font-semibold text-lg mb-2">Salida</h3>
                                                            <p>Servicio NO solicitado</p>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </section>
            </section>
        </div>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Declarar todayServicesData y función renderTodayServices primero ---
        let todayServicesData = [];
        function renderTodayServices(services) {
            const entradasList = document.getElementById('today-entradas');
            const salidasList = document.getElementById('today-salidas');
            if (!entradasList || !salidasList) return;
            entradasList.innerHTML = '';
            salidasList.innerHTML = '';

            const today = new Date().toISOString().slice(0, 10);

            services.forEach(item => {
                if (!item.tipo || !item.date) return;
                if (item.date !== today) return;

                const li = document.createElement('li');
                li.textContent = item.folio + ' - ' + item.guest;
                li.className = 'block px-2 py-1 rounded-md bg-white/70 hover:bg-blue-100 text-blue-700 text-sm shadow-sm cursor-pointer transition';
                li.addEventListener('click', () => {
                    window.location.href = '<?= site_url('public/consulta_transpo') ?>' + '?q=' + encodeURIComponent(item.folio) + '&from=today';
                });

                if (item.tipo.toLowerCase() === 'entrada') {
                    entradasList.appendChild(li);
                } else if (item.tipo.toLowerCase() === 'salida') {
                    salidasList.appendChild(li);
                }
            });
        }

        // --- Función para obtener servicios del día ---
        function fetchTodayServices() {
            const loader = document.getElementById('loader-today');
            const btn = document.getElementById('refresh-today');
            if (loader && btn) {
                loader.classList.remove('hidden');
                btn.disabled = true;
            }
            fetch('<?= site_url('public/consulta_transpo/today') ?>')
                .then(response => response.json())
                .then(data => {
                    todayServicesData = data || [];
                    renderTodayServices(todayServicesData);
                    if (loader && btn) {
                        loader.classList.add('hidden');
                        btn.disabled = false;
                    }
                    // Limpiar input de filtro al refrescar
                    const filterInput = document.getElementById('filter-today-input');
                    if (filterInput) filterInput.value = '';
                })
                .catch(err => {
                    console.error('Error fetching today services:', err);
                    if (loader && btn) {
                        loader.classList.add('hidden');
                        btn.disabled = false;
                    }
                });
            // Si existe el botón de limpiar, deshabilitarlo (por si acaso)
            const clearBtn = document.getElementById('clear-today-input');
            if (clearBtn) {
                clearBtn.disabled = false;
            }
        }

        // --- Lógica de historial de consultas ---
        const storageKey = 'consultaHistory';
        function loadHistory() {
            const raw = localStorage.getItem(storageKey);
            if (!raw) return [];
            try {
                return JSON.parse(raw);
            } catch {
                return [];
            }
        }
        function saveHistory(history) {
            const limited = history.slice(0, 30); // Mantener solo los 30 primeros
            localStorage.setItem(storageKey, JSON.stringify(limited));
        }
        function renderHistory() {
            const list = document.getElementById('consulta-history');
            if (!list) return;
            const history = loadHistory();
            // Sort descending by timestamp
            history.sort((a, b) => b.timestamp - a.timestamp);
            list.innerHTML = '';
            history.forEach(item => {
                const li = document.createElement('li');
                li.textContent = item.label;
                li.className = 'block px-2 py-1 rounded-md hover:bg-blue-100 text-blue-700 text-sm shadow-sm cursor-pointer transition';
                li.addEventListener('click', () => {
                    // Update timestamp and save
                    const now = Date.now();
                    const updated = history.filter(h => h.folio !== item.folio);
                    updated.unshift({
                        folio: item.folio,
                        label: item.label,
                        timestamp: now
                    });
                    saveHistory(updated);
                    // Redirect with query param q=folio
                    window.location.href = '<?= site_url('public/consulta_transpo') ?>' + '?q=' + encodeURIComponent(item.folio);
                });
                list.appendChild(li);
            });
        }

        // --- Filtro en tiempo real y botón de limpiar input ---
        const filterInput = document.getElementById('filter-today-input');
        const clearBtn = document.getElementById('clear-today-input');
        if (filterInput) {
            filterInput.addEventListener('input', function() {
                const value = filterInput.value.trim().toLowerCase();
                if (!value) {
                    renderTodayServices(todayServicesData);
                } else {
                    const filtered = todayServicesData.filter(function(item) {
                        const folio = String(item.folio || '').toLowerCase();
                        const guest = String(item.guest || '').toLowerCase();
                        return folio.includes(value) || guest.includes(value);
                    });
                    renderTodayServices(filtered);
                }
            });
        }
        if (clearBtn && filterInput) {
            clearBtn.addEventListener('click', function() {
                filterInput.value = '';
                renderTodayServices(todayServicesData);
                filterInput.focus();
            });
        }

        // --- Botón de refresh de servicios del día ---
        const refreshBtn = document.getElementById('refresh-today');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                fetchTodayServices();
            });
        }

        // --- Lógica de tabs ---
        const tabButtons = document.querySelectorAll('aside button[data-tab]');
        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const selectedTab = button.getAttribute('data-tab');
                tabButtons.forEach(btn => {
                    if (btn === button) {
                        btn.classList.add('border-blue-700', 'text-blue-700', 'font-semibold');
                        btn.classList.remove('text-gray-600');
                    } else {
                        btn.classList.remove('border-blue-700', 'text-blue-700', 'font-semibold');
                        btn.classList.add('text-gray-600');
                    }
                });
                document.getElementById('tab-history').classList.toggle('hidden', selectedTab !== 'history');
                document.getElementById('tab-today').classList.toggle('hidden', selectedTab !== 'today');
                if (selectedTab === 'today') {
                    fetchTodayServices();
                }
            });
        });

        // --- Render historial al cargar página ---
        renderHistory();

        // --- Revisar parámetro "from" en la URL para activar tab ---
        const params = new URLSearchParams(window.location.search);
        if (params.get('from') === 'today') {
            const todayBtn = document.querySelector('aside button[data-tab="today"]');
            if (todayBtn) todayBtn.click();
        }

        // --- Insertar búsqueda actual al historial si hay resultados ---
        <?php if ($search && !empty($resultados)): ?>
            (function(){
                const history = loadHistory();
                <?php 
                    $uniqueEntries = [];
                    foreach ($resultados as $r) {
                        $folio = $r['folio'];
                        $guest = $r['guest'];
                        $key = $folio;
                        if (!isset($uniqueEntries[$key])) {
                            $uniqueEntries[$key] = $guest;
                        }
                    }
                ?>
                <?php foreach ($uniqueEntries as $folio => $guest): ?>
                    (function(){
                        const folio = <?= json_encode($folio) ?>;
                        const label = folio + ' - ' + <?= json_encode($guest) ?>;
                        const now = Date.now();
                        // Remove existing entry with same folio
                        const filtered = history.filter(h => h.folio !== folio);
                        filtered.unshift({folio: folio, label: label, timestamp: now});
                        saveHistory(filtered);
                    })();
                <?php endforeach; ?>
                renderHistory();
            })();
        <?php endif; ?>
    });
    </script>
</body>
</html>