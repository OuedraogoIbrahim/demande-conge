<div class="row mb-4 g-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="row g-0">
                <!-- Image du sondage -->
                <div class="col-md-4 d-flex">
                    <img class="card-img card-img-left" src="{{ asset('assets/img/elements/vote.jpeg') }}"
                        alt="Sondage image" />
                </div>

                <!-- Contenu du sondage -->
                <div class="col-md-8">
                    @if ($myVote)
                        <h3 class="fw-bold text-danger text-center">Vous avez déjà voté</h3>
                    @endif
                    @if ($isFinish)
                        <h3 class="text-danger text-center mt-3">Le sondage a expiré</h3>
                    @endif
                    <div class="card-body">
                        <span class="fw-bold"> Question du sondage </span>
                        <h5 class="card-title text-primary">{{ $question }}</h5>
                        <p class="card-text">{{ $description }}</p>
                        <p class="card-text">
                            <strong>Accessible uniquement aux :</strong> {{ ucfirst($accessibilite) . 's' }}
                        </p>
                        <p class="card-text">
                            <strong>Filière concernée :</strong> {{ $filiere }}
                        </p>
                        <p class="card-text">
                            <strong>Ceux qui peuvent voter :</strong> {{ ucfirst($participant) }}
                        </p>

                        <p class="card-text">
                            <strong>Date de fin du sondage :</strong> {{ $date_fin }}
                        </p>

                        <!-- Options du sondage -->
                        <ul class="list-group list-group-flush">
                            @foreach ($options as $index => $option)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>
                                        <strong>Option {{ $index + 1 }} :</strong> {{ $option['option'] }}
                                    </span>
                                    <div>
                                        <!-- Affichage du nombre de votes -->
                                        <span class="badge bg-primary rounded-pill me-2">
                                            {{ $option['votes'] }}
                                            {{ Illuminate\Support\Str::plural('vote', $option['votes']) }}
                                        </span>

                                        @if (!$myVote && !$isFinish)
                                            <!-- Bouton voter -->
                                            <button type="button" class="btn btn-success btn-sm"
                                                wire:click="vote({{ $index }})" wire:confirm="Êtes-vous sûr ?">
                                                Voter
                                            </button>
                                        @endif

                                        @if (($myVote && $myVote->option_choisi == $option['option']) || !$isFinish)
                                            <!-- Bouton retirer le vote -->
                                            <button type="button" class="btn btn-danger btn-sm"
                                                wire:confirm="Êtes-vous sûr ?"
                                                wire:click="removeVote({{ $index }})">
                                                Retirer mon vote
                                            </button>
                                        @endif

                                    </div>
                                </li>
                            @endforeach

                        </ul>

                        <!-- Affichage du nombre total de votants -->
                        <div class="mt-3 text-end">
                            <strong>Nombre total de votants :</strong>
                            {{ array_sum(array_column($options, 'votes')) }}
                        </div>

                        <!-- Dernière mise à jour -->
                        <p class="card-text mt-3">
                            <small class="text-muted">Date de création :
                                {{ $sondage->created_at->diffForHumans() }}</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (Illuminate\Support\Facades\Auth::user()->role == 'coordinateur')
        <div class="d-flex">
            <button type="button" class="btn btn-outline-danger"
                onclick="confirmDelete(event , '{{ $sondage->id }}')">Supprimer ce sondage</button>
        </div>
    @endif

    <script>
        function confirmDelete(event, sondageId) {
            event.preventDefault();

            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: 'Vous ne pourrez pas revenir en arrière !',
                imageUrl: "{{ asset('assets/lordicon/delete.gif') }}",
                // icon: 'warning',
                imageWidth: 100, // Largeur du GIF
                imageHeight: 100, // Hauteur du GIF
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer !',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: "success",
                        title: 'Sondage supprimée avec succès.',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    @this.call('deleteSondage', sondageId); // Appelez la méthode Livewire pour supprimer
                }
            });
        }
    </script>

</div>
