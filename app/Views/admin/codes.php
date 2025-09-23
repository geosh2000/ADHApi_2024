<?php $this->extend('app/layout/layout.php') ?>

<?php $this->section('title') ?><i class="fas fa-tags me-2"></i>Códigos<?php $this->endSection() ?>
<?php $this->section('pageTitle') ?>Códigos<?php $this->endSection() ?>

<?php $this->section('styles') ?>
<style>
    .group-hotel {
        padding: 1rem;
        border-radius: 0.5rem;
        margin-bottom: 2rem;
        color: #fff;
    }
    .bg-hotel-0 { background-color: #007bff; }
    .bg-hotel-1 { background-color: #28a745; }
    .bg-hotel-2 { background-color: #ffc107; }
    .bg-hotel-3 { background-color: #17a2b8; }
    .bg-hotel-4 { background-color: #6f42c1; }

    .group-currency {
        padding: 1rem;
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
        color: #fff;
    }
    .bg-currency-0 { background-color: #343a40; }
    .bg-currency-1 { background-color: #495057; }
    .bg-currency-2 { background-color: #6c757d; }

    .card {
        border: none;
        border-radius: 0.5rem;
    }

    .card-text i {
        color: #6c757d;
    }

    .code-text {
        font-family: monospace;
        font-size: 1.1rem;
        background-color: #f8f9fa;
        padding: 0.2rem 0.5rem;
        border-radius: 0.25rem;
        user-select: text;
    }

    .btn-edit {
        background: none;
        border: none;
        color: #007bff;
        cursor: pointer;
        font-size: 1.1rem;
        padding: 0;
    }
    .btn-edit:hover {
        color: #0056b3;
    }
</style>
<?php $this->endSection() ?>

<?php $this->section('content') ?>
<div class="container">
    <?php if (permiso("discountCodeEdit")): ?>
        <?php if (session()->getFlashdata('message')): ?>
            <?php $type = session()->getFlashdata('message_type'); ?>
            <div class="alert alert-<?= ($type === 'success') ? 'success' : (($type === 'error') ? 'danger' : 'info') ?> alert-dismissible fade show" role="alert">
                <?= esc(session()->getFlashdata('message')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
            <div></div>
            <div class="input-group" style="max-width: 300px;">
                <input type="text" id="searchInput" class="form-control" placeholder="Buscar códigos, hoteles, categorías o moneda...">
                <button class="btn btn-outline-secondary" type="button" id="clearSearch" aria-label="Limpiar búsqueda" title="Limpiar búsqueda"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <?php
        // Agrupar códigos por hotel y currency
        $groupedCodes = [];
        foreach ($codes as $code) {
            $hotel = $code['Hotel'];
            $currency = $code['currency'];
            if (!isset($groupedCodes[$hotel])) {
                $groupedCodes[$hotel] = [];
            }
            if (!isset($groupedCodes[$hotel][$currency])) {
                $groupedCodes[$hotel][$currency] = [];
            }
            $groupedCodes[$hotel][$currency][] = $code;
        }
        // Para asignar clases de color a hoteles y monedas
        $hotelKeys = array_keys($groupedCodes);
        ?>
        <?php foreach ($groupedCodes as $hotelIndex => $hotelData): ?>
            <?php 
                $hotel = $hotelIndex;
                $currencies = $hotelData;
            ?>
            <div class="group-hotel bg-hotel-<?= array_search($hotel, $hotelKeys) % 5 ?>">
                <h2 class="mb-4"><i class="fas fa-hotel me-2"></i><?= esc($hotel) ?></h2>
                <?php 
                $currencyKeys = array_keys($currencies);
                foreach ($currencies as $currencyIndex => $codesList): 
                    $currency = $currencyIndex;
                ?>
                    <div class="group-currency bg-currency-<?= array_search($currency, $currencyKeys) % 3 ?>">
                        <h4 class="mb-3"><i class="fas fa-coins me-2"></i><?= esc($currency) ?></h4>
                        <div class="row row-cols-1 row-cols-md-3 g-4 mb-4">
                            <?php foreach ($codesList as $code): ?>
                                <div class="col">
                                    <div class="card h-100 shadow-sm">
                                        <div class="card-body d-flex flex-column">
                                            <p class="card-text mb-1"><i class="fas fa-tags me-2"></i><strong>Categoría:</strong> <?= esc($code['category_name']) ?></p>
                                            <p class="card-text mb-3"><i class="fas fa-percent me-2"></i><strong>Descuento:</strong> <?= esc($code['discount_percentage']) ?></p>
                                            <form action="<?= site_url('admin/code_modify') ?>" method="post" class="mt-auto" id="form-<?= esc($code['id']) ?>">
                                                <div class="mb-3">
                                                    <label for="code-<?= esc($code['id']) ?>" class="form-label"><i class="fas fa-barcode me-2"></i>Código</label>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="code-text" id="code-text-<?= esc($code['id']) ?>"><?= esc($code['code']) ?></span>
                                                        <input type="text" class="form-control d-none" id="code-<?= esc($code['id']) ?>" name="code" value="<?= esc($code['code']) ?>" required>
                                                        <button type="button" class="btn-edit" aria-label="Editar código" title="Editar código" onclick="toggleEdit(<?= esc($code['id']) ?>)"><i class="fas fa-pen" id="icon-<?= esc($code['id']) ?>"></i></button>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="id" value="<?= esc($code['id']) ?>">
                                                <button type="submit" class="btn btn-primary w-100 d-none" id="save-btn-<?= esc($code['id']) ?>">Guardar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <script>
        const originalCodes = {};

        function toggleEdit(id) {
            const textSpan = document.getElementById('code-text-' + id);
            const input = document.getElementById('code-' + id);
            const saveBtn = document.getElementById('save-btn-' + id);
            const icon = document.getElementById('icon-' + id);

            if (!input.classList.contains('d-none')) {
                // Cancel editing
                input.classList.add('d-none');
                textSpan.classList.remove('d-none');
                saveBtn.classList.add('d-none');
                input.value = originalCodes[id];
                textSpan.textContent = originalCodes[id];
                icon.classList.remove('fa-times');
                icon.classList.add('fa-pen');
            } else {
                // Start editing
                originalCodes[id] = textSpan.textContent;
                textSpan.classList.add('d-none');
                input.classList.remove('d-none');
                input.focus();
                saveBtn.classList.remove('d-none');
                icon.classList.remove('fa-pen');
                icon.classList.add('fa-times');
            }
        }

        // Auto close Bootstrap alerts after 5 seconds
        window.addEventListener('DOMContentLoaded', () => {
            const alertElements = document.querySelectorAll('.alert');
            alertElements.forEach(alertElement => {
                setTimeout(() => {
                    // Using Bootstrap's alert dispose method if available
                    if (typeof bootstrap !== 'undefined' && bootstrap.Alert) {
                        const alertInstance = bootstrap.Alert.getOrCreateInstance(alertElement);
                        alertInstance.close();
                    } else {
                        // Fallback: remove 'show' class to hide alert
                        alertElement.classList.remove('show');
                    }
                }, 5000);
            });

            // Search filtering logic
            const searchInput = document.getElementById('searchInput');

            searchInput.addEventListener('input', () => {
                const filter = searchInput.value.toLowerCase();

                const hotelGroups = document.querySelectorAll('.group-hotel');
                hotelGroups.forEach(hotelGroup => {
                    const hotelName = hotelGroup.querySelector('h2').textContent.toLowerCase();
                    let hotelMatches = hotelName.includes(filter);

                    const currencyGroups = hotelGroup.querySelectorAll('.group-currency');
                    let anyCurrencyVisible = false;

                    currencyGroups.forEach(currencyGroup => {
                        const currencyName = currencyGroup.querySelector('h4').textContent.toLowerCase();

                        const cards = currencyGroup.querySelectorAll('.card');
                        let anyCardVisible = false;

                        cards.forEach(card => {
                            const category = card.querySelector('.card-text strong').nextSibling.textContent.toLowerCase().trim();
                            const discount = card.querySelectorAll('.card-text')[1].textContent.toLowerCase();
                            const codeText = card.querySelector('.code-text').textContent.toLowerCase();

                            const matches = hotelMatches || currencyName.includes(filter) || category.includes(filter) || discount.includes(filter) || codeText.includes(filter);

                            if (matches) {
                                card.parentElement.classList.remove('d-none');
                                anyCardVisible = true;
                            } else {
                                card.parentElement.classList.add('d-none');
                            }
                        });

                        if (anyCardVisible || currencyName.includes(filter)) {
                            currencyGroup.classList.remove('d-none');
                            anyCurrencyVisible = true;
                        } else {
                            currencyGroup.classList.add('d-none');
                        }
                    });

                    if (anyCurrencyVisible || hotelMatches) {
                        hotelGroup.classList.remove('d-none');
                    } else {
                        hotelGroup.classList.add('d-none');
                    }
                });
            });

            // Restore filter from localStorage if exists
            const savedFilter = localStorage.getItem('codesFilter');
            if (savedFilter !== null) {
                searchInput.value = savedFilter;
                searchInput.dispatchEvent(new Event('input', { bubbles: true }));
                localStorage.removeItem('codesFilter');
            }

            // Clear search input and trigger input event
            const clearBtn = document.getElementById('clearSearch');
            clearBtn.addEventListener('click', () => {
                searchInput.value = '';
                searchInput.dispatchEvent(new Event('input'));
                searchInput.focus();
            });

            // Save filter value to localStorage before submitting any form
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', () => {
                    localStorage.setItem('codesFilter', searchInput.value);
                });
            });
        });
    </script>
    <?php else: ?>
    <div class="alert alert-danger">Usuario sin permiso</div>
    <?php endif; ?>
</div>
<?php $this->endSection() ?>
