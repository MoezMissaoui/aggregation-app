@extends('bo.layouts.app', ['page_name' => 'Dashboard'])

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-6">
            <!-- Card Border Shadow -->
            <div class="col-lg-3 col-sm-6">
                <div class="card card-border-shadow-primary h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="avatar me-4">
                                <span class="avatar-initial rounded bg-label-primary"><i
                                        class="ti ti-users ti-28px"></i></span>
                            </div>
                            <h4 class="mb-0">156</h4>
                        </div>
                        <p class="mb-1">Personnes en attente</p>
                        <p class="mb-0">
                            <span class="text-heading fw-medium me-2">+12.5%</span>
                            <small class="text-muted">par rapport à hier</small>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card card-border-shadow-warning h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="avatar me-4">
                                <span class="avatar-initial rounded bg-label-warning"><i
                                        class="ti ti-clock ti-28px"></i></span>
                            </div>
                            <h4 class="mb-0">15min</h4>
                        </div>
                        <p class="mb-1">Temps d'attente moyen</p>
                        <p class="mb-0">
                            <span class="text-heading fw-medium me-2">-3.4%</span>
                            <small class="text-muted">par rapport à hier</small>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card card-border-shadow-success h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="avatar me-4">
                                <span class="avatar-initial rounded bg-label-success"><i
                                        class="ti ti-check ti-28px"></i></span>
                            </div>
                            <h4 class="mb-0">432</h4>
                        </div>
                        <p class="mb-1">Personnes servies aujourd'hui</p>
                        <p class="mb-0">
                            <span class="text-heading fw-medium me-2">+8.2%</span>
                            <small class="text-muted">par rapport à hier</small>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card card-border-shadow-info h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="avatar me-4">
                                <span class="avatar-initial rounded bg-label-info"><i
                                        class="ti ti-building ti-28px"></i></span>
                            </div>
                            <h4 class="mb-0">12</h4>
                        </div>
                        <p class="mb-1">Établissements actifs</p>
                        <p class="mb-0">
                            <span class="text-heading fw-medium me-2">+1</span>
                            <small class="text-muted">ce mois</small>
                        </p>
                    </div>
                </div>
            </div>
            <!--/ Card Border Shadow -->

            <!-- Vue d'ensemble des services -->
            <div class="col-xxl-6">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="card-title mb-0">
                            <h5 class="m-0 me-2">Services actifs</h5>
                            <small class="text-muted">Temps d'attente moyen : 15 minutes</small>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-text-secondary rounded-pill text-muted border-0 p-2 me-n1" type="button"
                                id="servicesOverview" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ti ti-dots-vertical ti-md text-muted"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="servicesOverview">
                                <a class="dropdown-item" href="javascript:void(0);">Filtrer par service</a>
                                <a class="dropdown-item" href="javascript:void(0);">Actualiser</a>
                                <a class="dropdown-item" href="javascript:void(0);">Exporter les statistiques</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table card-table">
                                <thead>
                                    <tr>
                                        <th class="ps-0">Service</th>
                                        <th class="text-end">En attente</th>
                                        <th class="text-end">Temps moyen</th>
                                        <th class="text-end pe-0">Guichets</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    <tr>
                                        <td class="ps-0">
                                            <div class="d-flex justify-content-start align-items-center">
                                                <div class="avatar me-2 bg-label-success">
                                                    <span class="avatar-initial rounded-circle"><i class="ti ti-id"></i></span>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-semibold">Carte d'identité</h6>
                                                    <small class="text-muted">Service prioritaire</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge bg-label-warning">25 pers.</span>
                                        </td>
                                        <td class="text-end">
                                            <span class="text-danger fw-semibold">20 min</span>
                                        </td>
                                        <td class="text-end pe-0">
                                            <span class="badge bg-success">3 actifs</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="ps-0">
                                            <div class="d-flex justify-content-start align-items-center">
                                                <div class="avatar me-2 bg-label-primary">
                                                    <span class="avatar-initial rounded-circle"><i class="ti ti-file-text"></i></span>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-semibold">Certificats</h6>
                                                    <small class="text-muted">Service standard</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge bg-label-success">12 pers.</span>
                                        </td>
                                        <td class="text-end">
                                            <span class="text-success fw-semibold">10 min</span>
                                        </td>
                                        <td class="text-end pe-0">
                                            <span class="badge bg-success">2 actifs</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="ps-0">
                                            <div class="d-flex justify-content-start align-items-center">
                                                <div class="avatar me-2 bg-label-warning">
                                                    <span class="avatar-initial rounded-circle"><i class="ti ti-home"></i></span>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-semibold">Urbanisme</h6>
                                                    <small class="text-muted">Service spécialisé</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge bg-label-warning">18 pers.</span>
                                        </td>
                                        <td class="text-end">
                                            <span class="text-warning fw-semibold">15 min</span>
                                        </td>
                                        <td class="text-end pe-0">
                                            <span class="badge bg-warning">1 actif</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="ps-0">
                                            <div class="d-flex justify-content-start align-items-center">
                                                <div class="avatar me-2 bg-label-info">
                                                    <span class="avatar-initial rounded-circle"><i class="ti ti-users"></i></span>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-semibold">État civil</h6>
                                                    <small class="text-muted">Service standard</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge bg-label-success">8 pers.</span>
                                        </td>
                                        <td class="text-end">
                                            <span class="text-success fw-semibold">12 min</span>
                                        </td>
                                        <td class="text-end pe-0">
                                            <span class="badge bg-success">2 actifs</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Vue d'ensemble des services -->

            <!-- Shipment statistics-->
            <div class="col-xxl-6 col-lg-7">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="card-title mb-0">
                            <h5 class="mb-1">Statistiques des temps d'attente</h5>
                            <p class="card-subtitle">Temps d'attente moyen aujourd'hui : 15 minutes</p>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-label-primary">Aujourd'hui</button>
                            <button type="button" class="btn btn-label-primary dropdown-toggle dropdown-toggle-split"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:void(0);">Aujourd'hui</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Cette semaine</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Ce mois</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Cette année</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="shipmentStatisticsChart"></div>
                    </div>
                </div>
            </div>
            <!--/ Shipment statistics -->

            <!-- Performance des files d'attente -->
            <div class="col-xxl-4 col-lg-5">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="card-title mb-0">
                            <h5 class="mb-1">Performance des services</h5>
                            <p class="card-subtitle">Statistiques de la journée</p>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-text-secondary rounded-pill text-muted border-0 p-2 me-n1"
                                type="button" id="queuePerformance" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="ti ti-dots-vertical ti-md text-muted"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="queuePerformance">
                                <a class="dropdown-item" href="javascript:void(0);">Vue détaillée</a>
                                <a class="dropdown-item" href="javascript:void(0);">Actualiser</a>
                                <a class="dropdown-item" href="javascript:void(0);">Exporter le rapport</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="p-0 m-0">
                            <li class="d-flex mb-6">
                                <div class="avatar flex-shrink-0 me-4">
                                    <span class="avatar-initial rounded bg-label-primary"><i
                                            class="ti ti-ticket ti-26px"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0 fw-normal">Tickets émis aujourd'hui</h6>
                                        <small class="text-success fw-normal d-block">
                                            <i class="ti ti-chevron-up mb-1 me-1"></i>
                                            +15% vs hier
                                        </small>
                                    </div>
                                    <div class="user-progress">
                                        <h6 class="text-body mb-0">324</h6>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex mb-6">
                                <div class="avatar flex-shrink-0 me-4">
                                    <span class="avatar-initial rounded bg-label-info"><i
                                            class="ti ti-user-check ti-26px"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0 fw-normal">Personnes servies</h6>
                                        <small class="text-success fw-normal d-block">
                                            <i class="ti ti-chevron-up mb-1 me-1"></i>
                                            +8% vs hier
                                        </small>
                                    </div>
                                    <div class="user-progress">
                                        <h6 class="text-body mb-0">286</h6>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex mb-6">
                                <div class="avatar flex-shrink-0 me-4">
                                    <span class="avatar-initial rounded bg-label-warning"><i
                                            class="ti ti-clock ti-26px"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0 fw-normal">Temps d'attente moyen</h6>
                                        <small class="text-success fw-normal d-block">
                                            <i class="ti ti-chevron-down mb-1 me-1"></i>
                                            -12% vs hier
                                        </small>
                                    </div>
                                    <div class="user-progress">
                                        <h6 class="text-body mb-0">15 min</h6>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex mb-6">
                                <div class="avatar flex-shrink-0 me-4">
                                    <span class="avatar-initial rounded bg-label-success"><i
                                            class="ti ti-chart-bar ti-26px"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0 fw-normal">Taux de service</h6>
                                        <small class="text-success fw-normal d-block">
                                            <i class="ti ti-chevron-up mb-1 me-1"></i>
                                            +5% vs hier
                                        </small>
                                    </div>
                                    <div class="user-progress">
                                        <h6 class="text-body mb-0">88%</h6>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex mb-6">
                                <div class="avatar flex-shrink-0 me-4">
                                    <span class="avatar-initial rounded bg-label-danger"><i
                                            class="ti ti-user-exclamation ti-26px"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0 fw-normal">Taux d'absence</h6>
                                        <small class="text-danger fw-normal d-block">
                                            <i class="ti ti-chevron-up mb-1 me-1"></i>
                                            +2% vs hier
                                        </small>
                                    </div>
                                    <div class="user-progress">
                                        <h6 class="text-body mb-0">12%</h6>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex">
                                <div class="avatar flex-shrink-0 me-4">
                                    <span class="avatar-initial rounded bg-label-info"><i
                                            class="ti ti-thumb-up ti-26px"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0 fw-normal">Satisfaction usagers</h6>
                                        <small class="text-success fw-normal d-block">
                                            <i class="ti ti-chevron-up mb-1 me-1"></i>
                                            +0.5 vs hier
                                        </small>
                                    </div>
                                    <div class="user-progress">
                                        <h6 class="text-body mb-0">4.2/5</h6>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!--/ Performance des files d'attente -->

            <!-- Satisfaction des usagers -->
            <div class="col-xxl-4 col-lg-6">
                <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="card-title mb-0">
                    <h5 class="m-0 me-2">Satisfaction des usagers</h5>
                    </div>
                    <div class="dropdown">
                    <button
                        class="btn btn-text-secondary rounded-pill text-muted border-0 p-2 me-n1"
                        type="button"
                        id="satisfactionStats"
                        data-bs-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false">
                        <i class="ti ti-dots-vertical ti-md text-muted"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="satisfactionStats">
                        <a class="dropdown-item" href="javascript:void(0);">Vue détaillée</a>
                        <a class="dropdown-item" href="javascript:void(0);">Actualiser</a>
                        <a class="dropdown-item" href="javascript:void(0);">Exporter</a>
                    </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="satisfactionChart"></div>
                </div>
                </div>
            </div>
            <!--/ Satisfaction des usagers -->

            <!-- Tickets en cours -->
            <div class="col-xxl-4 col-lg-6">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="card-title mb-0">
                            <h5 class="mb-1">Tickets en cours</h5>
                            <p class="card-subtitle">28 tickets en traitement</p>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-text-secondary rounded-pill text-muted border-0 p-2 me-n1"
                                type="button" id="ticketsTabs" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="ti ti-dots-vertical ti-md text-muted"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="ticketsTabs">
                                <a class="dropdown-item" href="javascript:void(0);">Voir tous les tickets</a>
                                <a class="dropdown-item" href="javascript:void(0);">Actualiser</a>
                                <a class="dropdown-item" href="javascript:void(0);">Exporter</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="nav-align-top">
                            <ul class="nav nav-tabs nav-fill rounded-0 timeline-indicator-advanced" role="tablist">
                                <li class="nav-item">
                                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                        data-bs-target="#navs-justified-new" aria-controls="navs-justified-new"
                                        aria-selected="true">
                                        En attente
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                        data-bs-target="#navs-justified-link-preparing"
                                        aria-controls="navs-justified-link-preparing" aria-selected="false">
                                        En cours
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                        data-bs-target="#navs-justified-link-shipping"
                                        aria-controls="navs-justified-link-shipping" aria-selected="false">
                                        Terminé
                                    </button>
                                </li>
                            </ul>
                            <div class="tab-content border-0 mx-1">
                                <div class="tab-pane fade show active" id="navs-justified-new" role="tabpanel">
                                    <ul class="timeline mb-0">
                                        <li class="timeline-item ps-6 border-left-dashed">
                                            <span
                                                class="timeline-indicator-advanced timeline-indicator-warning border-0 shadow-none">
                                                <i class="ti ti-ticket"></i>
                                            </span>
                                            <div class="timeline-event ps-1">
                                                <div class="timeline-header">
                                                    <small class="text-warning text-uppercase">Carte d'identité</small>
                                                </div>
                                                <h6 class="my-50">Ticket #A125 - Sophie Martin</h6>
                                                <p class="text-muted mb-0">En attente depuis 25 min</p>
                                            </div>
                                        </li>
                                        <li class="timeline-item ps-6 border-transparent">
                                            <span
                                                class="timeline-indicator-advanced timeline-indicator-info border-0 shadow-none">
                                                <i class="ti ti-ticket"></i>
                                            </span>
                                            <div class="timeline-event ps-1">
                                                <div class="timeline-header">
                                                    <small class="text-info text-uppercase">Urbanisme</small>
                                                </div>
                                                <h6 class="my-50">Ticket #B237 - Thomas Dubois</h6>
                                                <p class="text-muted mb-0">En attente depuis 15 min</p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="tab-pane fade" id="navs-justified-link-preparing" role="tabpanel">
                                    <ul class="timeline mb-0">
                                        <li class="timeline-item ps-6 border-left-dashed">
                                            <span
                                                class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                                <i class="ti ti-ticket"></i>
                                            </span>
                                            <div class="timeline-event ps-1">
                                                <div class="timeline-header">
                                                    <small class="text-success text-uppercase">État civil</small>
                                                </div>
                                                <h6 class="my-50">Ticket #C348 - Marie Petit</h6>
                                                <p class="text-muted mb-0">Guichet 3 - Julie Dupont</p>
                                            </div>
                                        </li>
                                        <li class="timeline-item ps-6 border-transparent">
                                            <span
                                                class="timeline-indicator-advanced timeline-indicator-primary border-0 shadow-none">
                                                <i class="ti ti-ticket"></i>
                                            </span>
                                            <div class="timeline-event ps-1">
                                                <div class="timeline-header">
                                                    <small class="text-primary text-uppercase">Certificats</small>
                                                </div>
                                                <h6 class="my-50">Ticket #D459 - Pierre Durand</h6>
                                                <p class="text-muted mb-0">Guichet 5 - Marc Lambert</p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="tab-pane fade" id="navs-justified-link-shipping" role="tabpanel">
                                    <ul class="timeline mb-0">
                                        <li class="timeline-item ps-6 border-left-dashed">
                                            <span
                                                class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                                <i class="ti ti-ticket"></i>
                                            </span>
                                            <div class="timeline-event ps-1">
                                                <div class="timeline-header">
                                                    <small class="text-success text-uppercase">Certificats</small>
                                                </div>
                                                <h6 class="my-50">Ticket #E560 - Claire Bernard</h6>
                                                <p class="text-muted mb-0">Durée : 12 minutes</p>
                                            </div>
                                        </li>
                                        <li class="timeline-item ps-6 border-transparent">
                                            <span
                                                class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                                <i class="ti ti-ticket"></i>
                                            </span>
                                            <div class="timeline-event ps-1">
                                                <div class="timeline-header">
                                                    <small class="text-success text-uppercase">État civil</small>
                                                </div>
                                                <h6 class="my-50">Ticket #F671 - Lucas Moreau</h6>
                                                <p class="text-muted mb-0">Durée : 8 minutes</p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Tickets en cours -->

            <!-- Statistiques des services -->
            <div class="col-12 order-5">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="card-title mb-0">
                            <h5 class="m-0 me-2">Statistiques des services</h5>
                            <small class="text-muted">Mise à jour en temps réel</small>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-text-secondary rounded-pill text-muted border-0 p-2 me-n1"
                                type="button" id="serviceStats" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="ti ti-dots-vertical ti-md text-muted"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="serviceStats">
                                <a class="dropdown-item" href="javascript:void(0);">Vue détaillée</a>
                                <a class="dropdown-item" href="javascript:void(0);">Actualiser</a>
                                <a class="dropdown-item" href="javascript:void(0);">Exporter les données</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-datatable table-responsive">
                        <table class="dt-service-stats table table-sm">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Service</th>
                                    <th>En attente</th>
                                    <th>Temps moyen</th>
                                    <th>Traités aujourd'hui</th>
                                    <th>Satisfaction</th>
                                    <th>État</th>
                                    <th class="w-20">Charge actuelle</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3 bg-label-primary">
                                                <span class="avatar-initial rounded-circle"><i class="ti ti-id"></i></span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">Carte d'identité</h6>
                                                <small class="text-muted">Service prioritaire</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <h6 class="mb-0">25</h6>
                                        <small class="text-danger">+5 en 1h</small>
                                    </td>
                                    <td>
                                        <h6 class="mb-0">20 min</h6>
                                        <small class="text-danger">+5 min</small>
                                    </td>
                                    <td>
                                        <h6 class="mb-0">124</h6>
                                        <small class="text-success">Objectif : 120</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress w-100 me-3" style="height: 8px;">
                                                <div class="progress-bar bg-success" style="width: 85%" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <small class="text-success fw-medium">4.2/5</small>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-danger">Chargé</span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress w-100 me-3" style="height: 8px;">
                                                <div class="progress-bar bg-danger" style="width: 85%" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <small class="text-danger fw-medium">85%</small>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3 bg-label-warning">
                                                <span class="avatar-initial rounded-circle"><i class="ti ti-home"></i></span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">Urbanisme</h6>
                                                <small class="text-muted">Service spécialisé</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <h6 class="mb-0">18</h6>
                                        <small class="text-warning">+2 en 1h</small>
                                    </td>
                                    <td>
                                        <h6 class="mb-0">15 min</h6>
                                        <small class="text-success">-2 min</small>
                                    </td>
                                    <td>
                                        <h6 class="mb-0">85</h6>
                                        <small class="text-warning">Objectif : 90</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress w-100 me-3" style="height: 8px;">
                                                <div class="progress-bar bg-success" style="width: 90%" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <small class="text-success fw-medium">4.5/5</small>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-warning">Modéré</span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress w-100 me-3" style="height: 8px;">
                                                <div class="progress-bar bg-warning" style="width: 65%" role="progressbar" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <small class="text-warning fw-medium">65%</small>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3 bg-label-info">
                                                <span class="avatar-initial rounded-circle"><i class="ti ti-file-text"></i></span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">État civil</h6>
                                                <small class="text-muted">Service standard</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <h6 class="mb-0">8</h6>
                                        <small class="text-success">Stable</small>
                                    </td>
                                    <td>
                                        <h6 class="mb-0">8 min</h6>
                                        <small class="text-success">-1 min</small>
                                    </td>
                                    <td>
                                        <h6 class="mb-0">145</h6>
                                        <small class="text-success">Objectif : 140</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress w-100 me-3" style="height: 8px;">
                                                <div class="progress-bar bg-success" style="width: 95%" role="progressbar" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <small class="text-success fw-medium">4.8/5</small>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-success">Fluide</span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress w-100 me-3" style="height: 8px;">
                                                <div class="progress-bar bg-success" style="width: 45%" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <small class="text-success fw-medium">45%</small>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--/ Statistiques des services -->
        </div>
    </div>
@endsection

@section('scripts')
    <script src="/assets/js/app-logistics-dashboard.js"></script>
@endsection
