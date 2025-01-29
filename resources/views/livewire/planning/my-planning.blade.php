<div class="row gy-6">

    <div
        {{ $user->role == 'professeur' || $user->role == 'coordinateur' ? "class=\"col-xl-6 mx-auto\"" : "class=\"col-lg-12 mx-auto\"" }}>
        <h6 class="text-muted">Mon Planning de la semaine</h6>

        @if ($user->role == 'superviseur')
            <h4 class="text-center">Aucun évènement trouvé</h4>
        @endif

        @if ($user->role == 'professeur' || $user->role == 'coordinateur')
            <div class="nav-align-top mb-6">
                <ul class="nav nav-pills mb-4" role="tablist">
                    @foreach ($modules as $index => $module)
                        <li class="nav-item">
                            <button type="button" class="nav-link {{ $index === 0 ? 'active' : '' }}" role="tab"
                                data-bs-toggle="tab" data-bs-target="#module-{{ $module->id }}"
                                aria-controls="module-{{ $module->id }}"
                                aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                                {{ $module->nom }}
                            </button>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content">
                    @foreach ($modules as $index => $module)
                        <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
                            id="module-{{ $module->id }}" role="tabpanel">
                            <h5>{{ $module->nom . ' | Filière : ' . $module->filiere->nom . ' ---->' . $module->niveau->nom }}
                            </h5>

                            @if ($module->plannings->isEmpty())
                                <p>Aucun événement pour ce module.</p>
                            @else
                                <ul class="event">
                                    @php
                                        // Regrouper les événements par jour
                                        $eventsByDay = $module->plannings->groupBy(function ($event) {
                                            return Carbon\Carbon::parse($event->date_debut)->translatedFormat('l');
                                        });
                                    @endphp

                                    @foreach ($eventsByDay as $day => $events)
                                        <div style="margin-bottom: 30px">
                                            <li class="text-primary fw-bold mt-3">
                                                <h6>{{ \Illuminate\Support\Str::ucfirst(__($day)) }}</h6>
                                            </li>
                                            @foreach ($events as $event)
                                                <li
                                                    class="{{ Carbon\Carbon::parse($event->date_debut)->isPast() ? 'text-muted fw-bold' : 'fw-bold' }}">
                                                    Date : {{ $event->date_debut }}<br>
                                                    Heure de début : {{ $event->heure_debut }} <br>
                                                    Heure de fin : {{ $event->heure_fin }} <br>
                                                    Salle : {{ $event->classe->nom }} <br>
                                                </li>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endforeach
                </div>
        @endif

        @if ($user->role == 'etudiant' || $user->role == 'chef_de_classe')

            @if ($eventsStudent->isEmpty())
                <h4 class="text-center">Aucun évènement pour cette semaine</h4>
            @else
                <div class="card mb-6">

                    <div class="card-header px-0 pt-0">
                        <div class="nav-align-top">
                            <ul class="nav nav-tabs" role="tablist">
                                @foreach ($eventsStudent->groupBy(fn($e) => $e->date_debut) as $date => $eventGroup)
                                    <li class="nav-item">
                                        <button type="button" class="nav-link {{ $loop->first ? 'active' : '' }}"
                                            role="tab" data-bs-toggle="tab"
                                            data-bs-target="#tab-{{ $date }}"
                                            aria-controls="tab-{{ $date }}"
                                            aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                            {{ \Carbon\Carbon::parse($date)->translatedFormat('d M Y') }}
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content p-0">
                            @foreach ($eventsStudent->groupBy(fn($e) => $e->date_debut) as $date => $eventGroup)
                                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                    id="tab-{{ $date }}" role="tabpanel">
                                    @foreach ($eventGroup as $event)
                                        <p><strong>Module : {{ $event->module->nom }}</strong></p>
                                        <p>Heure de début :
                                            {{ Carbon\Carbon::parse($event->heure_debut)->format('H:i') }}
                                        </p>
                                        <p>Heure de fin : {{ Carbon\Carbon::parse($event->heure_fin)->format('H:i') }}
                                        </p>
                                        <p>Salle : {{ $event->classe->nom }}</p>
                                        <hr>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
            @endif

    </div>
    @endif

</div>

</div>
