<div class="card app-calendar-wrapper">
    <div class="row g-0">
        <!-- Calendar Sidebar -->
        <div class="col app-calendar-sidebar border-end" id="app-calendar-sidebar">
            <div class="border-bottom p-6 my-sm-0 mb-4">
                <button class="btn btn-primary btn-toggle-sidebar w-100" data-bs-toggle="offcanvas"
                    data-bs-target="#addEventSidebar" aria-controls="addEventSidebar">
                    <i class="ti ti-plus ti-16px me-2"></i>
                    <span class="align-middle">Ajouter un évènement</span>
                </button>
            </div>
            <div class="px-3 pt-2">
                <!-- inline calendar (flatpicker) -->
                <div class="inline-calendar"></div>
            </div>
            <hr class="mb-6 mx-n4 mt-3">
            <div class="px-6 pb-2">
                <!-- Filter -->
                <div>
                    <h5>Filtres</h5>
                </div>

                <div class="form-check form-check-secondary mb-5 ms-2">
                    <input class="form-check-input select-all" type="checkbox" id="selectAll" data-value="all" checked>
                    <label class="form-check-label" for="selectAll">Tout</label>
                </div>

                <div class="app-calendar-events-filter text-heading">
                    <div class="form-check form-check-danger mb-5 ms-2">
                        <input class="form-check-input input-filter" type="checkbox" id="select-personal"
                            data-value="personal" checked>
                        <label class="form-check-label" for="select-personal">Devoir</label>
                    </div>
                    <div class="form-check mb-5 ms-2">
                        <input class="form-check-input input-filter" type="checkbox" id="select-business"
                            data-value="business" checked>
                        <label class="form-check-label" for="select-business">Cours</label>
                    </div>
                    <div class="form-check form-check-info ms-2">
                        <input class="form-check-input input-filter" type="checkbox" id="select-etc" data-value="etc"
                            checked>
                        <label class="form-check-label" for="select-etc">Autre</label>
                    </div>
                    {{-- <div class="form-check form-check-warning mb-5 ms-2">
                        <input class="form-check-input input-filter" type="checkbox" id="select-family"
                            data-value="family" checked>
                        <label class="form-check-label" for="select-family">Family</label>
                    </div>
                    <div class="form-check form-check-success mb-5 ms-2">
                        <input class="form-check-input input-filter" type="checkbox" id="select-holiday"
                            data-value="holiday" checked>
                        <label class="form-check-label" for="select-holiday">Holiday</label>
                    </div> --}}
                </div>
            </div>
        </div>
        <!-- /Calendar Sidebar -->

        <!-- Calendar & Modal -->
        <div class="col app-calendar-content">
            <div class="card shadow-none border-0">
                <div class="card-body pb-0">
                    <!-- FullCalendar -->
                    <div id="calendar"></div>
                </div>
            </div>
            <div class="app-overlay"></div>
            <!-- FullCalendar Offcanvas -->
            <div class="offcanvas offcanvas-end event-sidebar" tabindex="-1" id="addEventSidebar"
                aria-labelledby="addEventSidebarLabel">
                <div class="offcanvas-header border-bottom">
                    <h5 class="offcanvas-title" id="addEventSidebarLabel">Ajouter un évènement</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <form class="event-form pt-0" id="eventForm" onsubmit="return false">
                        <div class="mb-5">
                            <label class="form-label" for="eventTitle">Titre</label>
                            <input type="text" class="form-control" id="eventTitle" name="eventTitle" />
                            <div class="invalid-feedback" id="eventTitleError"></div> <!-- Zone pour les erreurs -->
                        </div>
                        <div class="mb-5">
                            <label class="form-label" for="eventLabel">Label</label>
                            <select class="select2 select-event-label form-select" id="eventLabel" name="eventLabel">
                                <option data-label="primary" value="Business" selected>Cours</option>
                                <option data-label="danger" value="Personal">Devoir</option>
                                <option data-label="info" value="ETC">Autre</option>
                            </select>
                            <div class="invalid-feedback" id="eventLabelError"></div> <!-- Zone pour les erreurs -->
                        </div>
                        <div class="mb-5">
                            <label class="form-label" for="eventStartDate">Date de début</label>
                            <input type="text" class="form-control" id="eventStartDate" name="eventStartDate" />
                            <div class="invalid-feedback" id="eventStartDateError"></div> <!-- Zone pour les erreurs -->
                        </div>
                        <div class="mb-5">
                            <label class="form-label" for="eventEndDate">Date de fin</label>
                            <input type="text" class="form-control" id="eventEndDate" name="eventEndDate" />
                            <div class="invalid-feedback" id="eventEndDateError"></div> <!-- Zone pour les erreurs -->
                        </div>
                        <div class="mb-5">
                            <label class="form-label" for="eventURL">Liste des classes</label>
                            <select name="eventURL" id="eventURL" class="select2 select-event-url form-select"
                                required>
                                <option value="">Liste des classes</option>
                                @foreach ($classes as $c)
                                    <option value="{{ $c->id }}">{{ $c->nom }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="eventURLerror"></div> <!-- Zone pour les erreurs -->
                        </div>
                        <div class="mb-4 select2-primary">
                            <label class="form-label" for="eventGuests">Les modules</label>
                            <select class="select2 select-event-guests form-select" id="eventGuests"
                                name="eventGuests" required>
                                <option value="">Liste des modules</option>
                                @foreach ($modules as $m)
                                    <option value="{{ $m->id }}">{{ $m->nom . ' (' . $m->niveau->nom . ')' }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="eventGuestsError"></div> <!-- Zone pour les erreurs -->
                        </div>
                        <div class="d-flex justify-content-sm-between justify-content-start mt-6 gap-2">
                            <div class="d-flex">
                                <button type="submit" id="addEventBtn"
                                    class="btn btn-primary btn-add-event me-4">Ajouter</button>
                                <button type="reset" class="btn btn-label-secondary btn-cancel me-sm-0 me-1"
                                    data-bs-dismiss="offcanvas">Réinitialiser</button>
                            </div>
                            <button class="btn btn-label-danger btn-delete-event d-none">Supprimer</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <!-- /Calendar & Modal -->
    </div>
</div>
