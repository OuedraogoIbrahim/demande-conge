<div class="row g-6">

    <div class="m-5">
        @if (session()->has('message'))
            <div class="alert alert-info">
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('failedRows'))
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach (session('failedRows') as $failedRow)
                        <li>Ligne {{ $failedRow['ligne'] }}: {{ implode(', ', $failedRow['erreurs']) }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
    </div>

    {{-- Formulaire --}}
    <div class="col-xl-6 col-md-12">
        <form wire:submit.prevent="addNote">
            <div class="input-group">
                <!-- Input file -->
                <input type="file" wire:model="file" class="form-control @error('file') is-invalid @enderror"
                    id="fileInput" aria-controls="DataTables_Table_0" accept=".xlsx, .xls">

                <!-- Bouton de soumission -->
                <button type="submit" class="btn btn-secondary buttons-collection d-flex align-items-center"
                    wire:loading.attr="disabled">
                    <span wire:loading.class="d-none">
                        <i class="ti ti-upload me-2 ti-xs"></i>
                        Import des notes
                    </span>
                    <!-- Spinner pendant le chargement -->
                    <div wire:loading class="spinner-border spinner-border-sm text-light" role="status"></div>
                </button>
            </div>

            <!-- Message d'erreur de validation -->
            @error('file')
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
            @enderror
        </form>
    </div>

    <div class="col-xl-12 col-md-12">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between">
                <h5 class="card-title mb-0">Statistiques</h5>
            </div>
            <div class="card-body d-flex align-items-end">
                <div class="w-100">
                    <div class="row gy-3">
                        <div class="col-md-4 col-6">
                            <div class="d-flex align-items-center">
                                <div class="badge rounded bg-label-primary me-4 p-2">
                                    <i class="ti ti-book ti-lg"></i>
                                </div>
                                <div class="card-info">
                                    <h5 class="mb-0">{{ $nombreModules }}</h5>
                                    <small>Modules enseignés</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="d-flex align-items-center">
                                <div class="badge rounded bg-label-info me-4 p-2">
                                    <i class="ti ti-calendar ti-lg"></i>
                                </div>
                                <div class="card-info">
                                    <h5 class="mb-0">{{ $nombreCours }}</h5>
                                    <small>Cours prévus aujourd'hui</small>
                                </div>
                            </div>
                        </div>

                        <!-- Affichage des étudiants par filière et niveau -->
                        @foreach ($nombreEtudiants as $e)
                            <div class="col-md-4 col-6">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded bg-label-success me-4 p-2">
                                        <i class="ti ti-users ti-lg"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">{{ $e->nombre_etudiants }} Étudiant(s) </h5>
                                        <small>{{ $e->filiere->nom }} {{ $e->niveau->nom }}</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-6 g-6">

        <div class="col-xxl-4 col-md-6">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Progression des Modules</h5>
                </div>
                <div class="card-body">
                    <ul class="p-0 m-0">
                        @foreach ($modules as $module)
                            @php
                                // Calcul du pourcentage
                                $pourcentage = ($module->heures_utilisees / $module->nombre_heures) * 100;

                                // Déterminer la couleur en fonction du pourcentage
                                if ($pourcentage >= 75) {
                                    $color = 'success'; // Vert
                                } elseif ($pourcentage >= 50) {
                                    $color = 'primary'; // Bleu
                                } elseif ($pourcentage >= 25) {
                                    $color = 'warning'; // Jaune
                                } else {
                                    $color = 'danger'; // Rouge
                                }
                            @endphp

                            <li class="d-flex mb-6">
                                <div class="chart-progress me-4" data-color="{{ $color }}"
                                    data-series="{{ round($pourcentage) }}" data-progress_variant="true"></div>
                                <div class="row w-100 align-items-center">
                                    <div class="col-9">
                                        <div class="me-2">
                                            <h6 class="mb-2">{{ $module->nom }}</h6>
                                            <small>{{ $module->nombre_heures }} heures</small> -
                                            <small>{{ $module->niveau->nom }} - {{ $module->filiere->nom }}</small>
                                        </div>
                                    </div>
                                    <div class="col-3 text-end">
                                        <button type="button" class="btn btn-sm btn-icon btn-label-secondary">
                                            <i class="ti ti-chevron-right scaleX-n1-rtl"></i>
                                        </button>
                                    </div>
                                </div>
                            </li>
                        @endforeach

                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xxl-4 col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Devoirs Programmés</h5>
                </div>
                <div class="card-body">
                    @if ($devoirs->isNotEmpty())
                        <ul class="list-unstyled mb-0">
                            @foreach ($devoirs as $devoir)
                                <li class="d-flex mb-6 align-items-center">
                                    <div class="avatar flex-shrink-0 me-4">
                                        <span
                                            class="avatar-initial rounded bg-label-{{ $loop->index % 5 === 0 ? 'primary' : ($loop->index % 5 === 1 ? 'info' : ($loop->index % 5 === 2 ? 'success' : ($loop->index % 5 === 3 ? 'warning' : 'danger'))) }}">
                                            <i class="ti {{ $devoir->icon ?? 'ti-file' }} ti-lg"></i>
                                        </span>
                                    </div>
                                    <div class="row w-100 align-items-center">
                                        <div class="col-sm-8 mb-1 mb-sm-0 mb-lg-1 mb-xxl-0">
                                            <h6 class="mb-0">{{ $devoir->module->nom }}</h6>
                                            <small>Date :
                                                {{ \Carbon\Carbon::parse($devoir->start_time)->format('d/m/Y') }}</small><br>
                                            <small>Heure :
                                                {{ \Carbon\Carbon::parse($devoir->heure_debut)->format('h:i') . ' à ' . \Carbon\Carbon::parse($devoir->heure_fin)->format('h:i') }}</small><br>
                                            <small>Filière : {{ $devoir->module->filiere->nom }}</small><br>
                                            <small>Niveau : {{ $devoir->module->niveau->nom }}</small>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">Aucun devoir programmé pour ce module.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xxl-4 col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Coordinateurs des filières</h5>
                    </div>
                </div>
                <div class="px-5 py-4 border border-start-0 border-end-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="mb-0 text-uppercase">Nom & Prénom</p>
                        <p class="mb-0 text-uppercase">Filière</p>
                    </div>
                </div>
                <div class="card-body">
                    @foreach ($coordinateurs as $coordinateur)
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h6 class="mb-0 text-truncate">{{ $coordinateur->nom }}
                                        {{ $coordinateur->prenom }}
                                    </h6>
                                    <small class="text-truncate text-body">{{ $coordinateur->email }}</small><br>
                                    <small class="text-truncate text-body">{{ $coordinateur->telephone }}</small>
                                </div>
                            </div>
                            <div class="text-end">
                                <h6 class="mb-0">{{ $coordinateur->coordinateur->first()->filiere->nom }}</h6>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    <div class="col-xl-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0 me-2">Cours en attente(Hier, Aujourd’hui, Demain)</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-borderless border-top">
                    <thead class="border-bottom">
                        <tr>
                            <th>MODULE</th>
                            <th>DATE</th>
                            <th>HEURE</th>
                            <th>STATUT</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cours as $coursItem)
                            <tr>
                                <td class="pt-4">{{ $coursItem->module->nom }}</td>
                                <td class="pt-4">
                                    {{ \Carbon\Carbon::parse($coursItem->date_debut)->translatedFormat('d M Y') }}</td>
                                <td class="pt-4">
                                    {{ \Carbon\Carbon::parse($coursItem->heure_debut)->translatedFormat('H:i') . ' à ' . \Carbon\Carbon::parse($coursItem->heure_fin)->format('H:i') }}
                                </td>
                                <td class="pt-4"><span class="badge bg-label-warning">En attente</span></td>
                                <td class="pt-4">
                                    @if (\Carbon\Carbon::parse($coursItem->date_debut)->lessThanOrEqualTo(\Carbon\Carbon::today()))
                                        <div class="dropdown">
                                            <button
                                                class="btn btn-text-secondary rounded-pill text-muted border-0 p-2 me-n1"
                                                type="button" id="coursActions{{ $coursItem->id }}"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="ti ti-dots-vertical ti-md text-muted"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <button wire:click='markAsDone("{{ $coursItem->id }}")'
                                                    class="dropdown-item" href="javascrip();">Marquer le cours comme
                                                    effectuer</button>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        @if ($cours->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center text-muted pt-4">Aucun cours trouvé</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Earning Reports Tabs-->
    {{-- <div class="col-xl-9 col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="card-title m-0">
                    <h5 class="mb-1">Earning Reports</h5>
                    <p class="card-subtitle">Yearly Earnings Overview</p>
                </div>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs widget-nav-tabs pb-8 gap-4 mx-1 d-flex flex-nowrap" role="tablist">
                    <li class="nav-item">
                        <a href="javascript:void(0);"
                            class="nav-link btn active d-flex flex-column align-items-center justify-content-center"
                            role="tab" data-bs-toggle="tab" data-bs-target="#navs-orders-id"
                            aria-controls="navs-orders-id" aria-selected="true">
                            <div class="badge bg-label-secondary rounded p-2"><i
                                    class="ti ti-shopping-cart ti-md"></i>
                            </div>
                            <h6 class="tab-widget-title mb-0 mt-2">Orders</h6>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="javascript:void(0);"
                            class="nav-link btn d-flex flex-column align-items-center justify-content-center"
                            role="tab" data-bs-toggle="tab" data-bs-target="#navs-sales-id"
                            aria-controls="navs-sales-id" aria-selected="false">
                            <div class="badge bg-label-secondary rounded p-2"><i class="ti ti-chart-bar ti-md"></i>
                            </div>
                            <h6 class="tab-widget-title mb-0 mt-2"> Sales</h6>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="javascript:void(0);"
                            class="nav-link btn d-flex flex-column align-items-center justify-content-center"
                            role="tab" data-bs-toggle="tab" data-bs-target="#navs-profit-id"
                            aria-controls="navs-profit-id" aria-selected="false">
                            <div class="badge bg-label-secondary rounded p-2"><i
                                    class="ti ti-currency-dollar ti-md"></i>
                            </div>
                            <h6 class="tab-widget-title mb-0 mt-2">Profit</h6>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="javascript:void(0);"
                            class="nav-link btn d-flex flex-column align-items-center justify-content-center"
                            role="tab" data-bs-toggle="tab" data-bs-target="#navs-income-id"
                            aria-controls="navs-income-id" aria-selected="false">
                            <div class="badge bg-label-secondary rounded p-2"><i class="ti ti-chart-pie-2 ti-md"></i>
                            </div>
                            <h6 class="tab-widget-title mb-0 mt-2">Income</h6>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="javascript:void(0);"
                            class="nav-link btn d-flex align-items-center justify-content-center disabled"
                            role="tab" data-bs-toggle="tab" aria-selected="false">
                            <div class="badge bg-label-secondary rounded p-2"><i class="ti ti-plus ti-md"></i></div>
                        </a>
                    </li>
                </ul>
                <div class="tab-content p-0 ms-0 ms-sm-2">
                    <div class="tab-pane fade show active" id="navs-orders-id" role="tabpanel">
                        <div id="earningReportsTabsOrders"></div>
                    </div>
                    <div class="tab-pane fade" id="navs-sales-id" role="tabpanel">
                        <div id="earningReportsTabsSales"></div>
                    </div>
                    <div class="tab-pane fade" id="navs-profit-id" role="tabpanel">
                        <div id="earningReportsTabsProfit"></div>
                    </div>
                    <div class="tab-pane fade" id="navs-income-id" role="tabpanel">
                        <div id="earningReportsTabsIncome"></div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <div
        wire:loading.class="position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center">
        <div wire:loading class="sk-chase sk-primary">
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
        </div>
    </div>

</div>
