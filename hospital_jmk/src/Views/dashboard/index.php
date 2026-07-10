<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

        <div class="col-md-10 main-content">
            <h2 class="mb-4">📊 Tableau de Bord</h2>

            <!-- Filtre mois/année pour les graphiques -->
            <div class="card mb-4">
                <div class="card-body">
                    <form id="filterForm" class="row g-3">
                        <div class="col-md-3">
                            <label>Mois</label>
                            <select name="mois" id="mois" class="form-select">
                                <?php for ($m = 1; $m <= 12; $m++): ?>
                                    <option value="<?= str_pad($m, 2, '0', STR_PAD_LEFT) ?>" <?= (isset($_GET['mois']) && $_GET['mois'] == str_pad($m, 2, '0', STR_PAD_LEFT)) ? 'selected' : '' ?>>
                                        <?= date('F', mktime(0, 0, 0, $m, 1)) ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Année</label>
                            <select name="annee" id="annee" class="form-select">
                                <?php for ($y = date('Y') - 2; $y <= date('Y'); $y++): ?>
                                    <option value="<?= $y ?>" <?= (isset($_GET['annee']) && $_GET['annee'] == $y) ? 'selected' : '' ?>><?= $y ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Cartes statistiques -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card text-white bg-primary shadow-sm">
                        <div class="card-body text-center">
                            <h2 class="display-6" id="totalEmployes">0</h2>
                            <p><i class="bi bi-people fs-3"></i> Total Employés</p>
                        </div>

                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success shadow-sm">
                        <div class="card-body text-center">
                            <h2 class="display-6" id="presentAujourdhui">0</h2>
                            <p><i class="bi bi-person-check fs-3"></i> Présents aujourd'hui</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-warning shadow-sm">
                        <div class="card-body text-center">
                            <h2 class="display-6" id="congesEnAttente">0</h2>
                            <p><i class="bi bi-clock fs-3"></i> Congés en attente</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-info shadow-sm">
                        <div class="card-body text-center">
                            <h2 class="display-6" id="totalServices">0</h2>
                            <p><i class="bi bi-building fs-3"></i> Services</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Graphiques -->
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">📊 Répartition par service</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="chartServices" height="200"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">📈 Statut des employés</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="chartStatut" height="200"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">📉 Présence du jour</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="chartPresenceJour" height="200"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">📈 Évolution des congés validés (12 mois)</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="chartEvolutionConges" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats mensuelles détaillées -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">📅 Récapitulatif du mois de <span id="moisLabel"></span></h5>
                </div>
                <div class="card-body">
                    <div class="row text-center" id="statsMoisContainer">
                        <div class="col">
                            <h5>Présent</h5><span class="badge bg-success fs-5" id="statPresent">0</span>
                        </div>
                        <div class="col">
                            <h5>Absent</h5><span class="badge bg-danger fs-5" id="statAbsent">0</span>
                        </div>
                        <div class="col">
                            <h5>Retard</h5><span class="badge bg-warning fs-5" id="statRetard">0</span>
                        </div>
                        <div class="col">
                            <h5>Congé</h5><span class="badge bg-info fs-5" id="statConge">0</span>
                        </div>
                        <div class="col">
                            <h5>Maladie</h5><span class="badge bg-secondary fs-5" id="statMaladie">0</span>
                        </div>
                        <div class="col">
                            <h5>Formation</h5><span class="badge bg-dark fs-5" id="statFormation">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
    // ============================================
    // 1. RÉCUPÉRER LES DONNÉES VIA AJAX
    // ============================================
    function fetchData(mois, annee) {
        fetch('index.php?action=dashboard_data&mois=' + mois + '&annee=' + annee)
            .then(response => response.json())
            .then(data => {
                // Mettre à jour les cartes
                document.getElementById('totalEmployes').textContent = data.totalEmployes;
                document.getElementById('presentAujourdhui').textContent = data.presentAujourdhui;
                document.getElementById('congesEnAttente').textContent = data.congesEnAttente;
                document.getElementById('totalServices').textContent = data.totalServices;

                // Mettre à jour le mois
                document.getElementById('moisLabel').textContent = data.moisActuel;

                // Mettre à jour les stats mensuelles
                document.getElementById('statPresent').textContent = data.statutsMois['Présent'] || 0;
                document.getElementById('statAbsent').textContent = data.statutsMois['Absent'] || 0;
                document.getElementById('statRetard').textContent = data.statutsMois['Retard'] || 0;
                document.getElementById('statConge').textContent = data.statutsMois['Congé'] || 0;
                document.getElementById('statMaladie').textContent = data.statutsMois['Maladie'] || 0;
                document.getElementById('statFormation').textContent = data.statutsMois['Formation'] || 0;

                // Mettre à jour les graphiques
                updateCharts(data);
            })
            .catch(error => console.error('Erreur:', error));
    }

    // ============================================
    // 2. CRÉER LES GRAPHIQUES (Chart.js)
    // ============================================
    let chartServices, chartStatut, chartPresenceJour, chartEvolutionConges;

    function updateCharts(data) {
        // --- 2.1 Graphique : Répartition par service ---
        const ctx1 = document.getElementById('chartServices').getContext('2d');
        if (chartServices) chartServices.destroy();
        chartServices = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: data.repartitionService.map(item => item.nom_service),
                datasets: [{
                    label: 'Nombre d\'employés',
                    data: data.repartitionService.map(item => item.total),
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // --- 2.2 Graphique : Statut des employés ---
        const ctx2 = document.getElementById('chartStatut').getContext('2d');
        if (chartStatut) chartStatut.destroy();
        const statutColors = {
            'Actif': '#28a745',
            'En Congé': '#17a2b8',
            'En Arrêt Maladie': '#dc3545',
            'Suspendu': '#6c757d',
            'Démissionné': '#343a40'
        };
        chartStatut = new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: data.statutEmployes.map(item => item.statut_employe),
                datasets: [{
                    data: data.statutEmployes.map(item => item.total),
                    backgroundColor: data.statutEmployes.map(item => statutColors[item.statut_employe] || '#6c757d'),
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true
            }
        });

        // --- 2.3 Graphique : Présence du jour ---
        const ctx3 = document.getElementById('chartPresenceJour').getContext('2d');
        if (chartPresenceJour) chartPresenceJour.destroy();
        const presenceColors = {
            'Présent': '#28a745',
            'Absent': '#dc3545',
            'Retard': '#ffc107',
            'Congé': '#17a2b8',
            'Maladie': '#6c757d',
            'Formation': '#fd7e14'
        };
        chartPresenceJour = new Chart(ctx3, {
            type: 'pie',
            data: {
                labels: data.presenceJour.map(item => item.statut),
                datasets: [{
                    data: data.presenceJour.map(item => item.total),
                    backgroundColor: data.presenceJour.map(item => presenceColors[item.statut] || '#6c757d'),
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true
            }
        });

        // --- 2.4 Graphique : Évolution des congés validés ---
        const ctx4 = document.getElementById('chartEvolutionConges').getContext('2d');
        if (chartEvolutionConges) chartEvolutionConges.destroy();
        chartEvolutionConges = new Chart(ctx4, {
            type: 'line',
            data: {
                labels: data.evolutionConges.map(item => item.mois),
                datasets: [{
                    label: 'Congés validés',
                    data: data.evolutionConges.map(item => item.total),
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // ============================================
    // 3. CHARGER LES DONNÉES AU DÉMARRAGE
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        const mois = document.getElementById('mois').value;
        const annee = document.getElementById('annee').value;
        fetchData(mois, annee);
    });

    // ============================================
    // 4. FILTRE PAR MOIS/ANNÉE
    // ============================================
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const mois = document.getElementById('mois').value;
        const annee = document.getElementById('annee').value;
        // Mettre à jour l'URL (pour le partage)
        const url = new URL(window.location.href);
        url.searchParams.set('mois', mois);
        url.searchParams.set('annee', annee);
        window.history.pushState({}, '', url);
        fetchData(mois, annee);
    });
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>