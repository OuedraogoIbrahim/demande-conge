<div>
    @if ($registrations->isEmpty())
        <div class="alert alert-warning d-flex align-items-center justify-content-between" role="alert">
            <div>
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                La liste est vide
            </div>

            @if (Auth::user()->role == 'superviseur')
                <a href="{{ route('session.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Ajouter une session d'inscription
                </a>
            @endif
        </div>
    @else
        @if ($students)

            @if ($students->isEmpty())
                <h4 class="text-danger">Aucun étudiant en attente</h4>
            @else
                <div wire:ignore class="col-lg-4 col-md-6">
                    <div class="mt-4">

                        <div class="modal fade" id="fullscreenModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-fullscreen" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalFullTitle">Liste des étudiants en attente</h5>

                                        <div class="mx-10">
                                            <button wire:click='acceptAllStudent' wire:target="acceptAllStudent"
                                                wire:loading.attr="disabled" type="button" class="btn btn-success">
                                                <span wire:loading.remove wire:target="acceptAllStudent">Tout
                                                    accepter</span>
                                                <span wire:loading wire:target="acceptAllStudent">
                                                    <i class="spinner-border spinner-border-sm"></i> En cours...
                                                </span>
                                            </button>

                                            <button wire:click='refuseAllStudent' wire:target="refuseAllStudent"
                                                wire:loading.attr="disabled" type="button" class="btn btn-danger">
                                                <span wire:loading.remove wire:target="refuseAllStudent">Tout
                                                    refuser</span>
                                                <span wire:loading wire:target="refuseAllStudent">
                                                    <i class="spinner-border spinner-border-sm"></i> En cours...
                                                </span>
                                            </button>
                                        </div>

                                        <button wire:click='reloading' type="button" class="btn-close"
                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Nom</th>
                                                    <th>Prénom</th>
                                                    <th>Email</th>
                                                    <th>téléphone</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>

                                            <tbody class="table-border-bottom-0">
                                                @foreach ($students as $s)
                                                    <tr>
                                                        <td>{{ $s->nom }}</td>
                                                        <td>{{ $s->prenom }}</td>
                                                        <td>{{ $s->email }}</td>
                                                        <td>{{ $s->telephone }}</td>
                                                        <td>
                                                            <!-- Bouton Refuser -->
                                                            <button wire:click="refuseStudent('{{ $s->id }}')"
                                                                wire:target="refuseStudent('{{ $s->id }}')"
                                                                wire:loading.attr="disabled"
                                                                wire:confirm="Etes vous sûr de vouloir refuser cette demande"
                                                                type="button" class="btn btn-outline-danger">
                                                                <span wire:loading.remove
                                                                    wire:target="refuseStudent('{{ $s->id }}')">Refuser</span>
                                                                <span wire:loading
                                                                    wire:target="refuseStudent('{{ $s->id }}')">
                                                                    <i class="spinner-border spinner-border-sm"></i> En
                                                                    cours...
                                                                </span>
                                                            </button>

                                                            <!-- Bouton Accepter -->
                                                            <button wire:click="acceptStudent('{{ $s->id }}')"
                                                                wire:target="acceptStudent('{{ $s->id }}')"
                                                                wire:loading.attr="disabled"
                                                                wire:confirm="Etes vous sûr de vouloir accepter cette demande"
                                                                type="button" class="btn btn-outline-success">
                                                                <span wire:loading.remove
                                                                    wire:target="acceptStudent('{{ $s->id }}')">Accepter</span>
                                                                <span wire:loading
                                                                    wire:target="acceptStudent('{{ $s->id }}')">
                                                                    <i class="spinner-border spinner-border-sm"></i> En
                                                                    cours...
                                                                </span>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button wire:click='reloading' type="button" class="btn btn-outline-secondary"
                                            data-bs-dismiss="modal">Fermer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @endif

        @endif

        <div class="card">

            <h5 class="card-header">Listes des sessions d'inscription</h5>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Date de fin</th>
                            <th>Filière</th>
                            <th>Niveau</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($registrations as $r)
                            <tr>
                                <td>
                                    <span>{{ $r->nom }}</span>
                                </td>
                                <td>
                                    <span>{{ $r->date_fin }}</span>
                                </td>
                                <td>
                                    <span>{{ $r->filiere->nom }}</span>
                                </td>
                                <td>
                                    <span>{{ $r->niveau->nom }}</span>
                                </td>

                                <td>
                                    <div class="mt-2">
                                        <a href="javascript:void(0);" class="text-decoration-none text-danger"
                                            onclick="confirmDelete(event, '{{ $r->id }}')">
                                            <i class="ri-delete-bin-6-line me-1"></i> Supprimer
                                        </a>
                                    </div>

                                    <div class="mt-2">
                                        <a href="javascript:void(0);" class="text-decoration-none text-primary"
                                            data-bs-toggle="popover" data-bs-placement="bottom"
                                            data-bs-content="{{ $r->lien }}" title="Lien vers la session">
                                            <i class="ri-link-line me-1"></i> Lien de la session
                                        </a>
                                    </div>

                                    <div class="mt-2">
                                        <a wire:click="display('{{ $r->id }}')" href="javascript:void(0);"
                                            class="text-decoration-none text-success" data-bs-toggle="modal"
                                            data-bs-target="#fullscreenModal">
                                            <i class="ri-group-line me-1"></i> Étudiant(s) en attente
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $registrations->links() }}

            <script>
                function confirmDelete(event, sessionId) {
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
                                title: 'Session supprimée avec succès.',
                                showConfirmButton: false,
                                timer: 1000
                            });
                            @this.call('deleteSession', sessionId); // Appelez la méthode Livewire pour supprimer
                        }
                    });
                }
            </script>
        </div>
    @endif
</div>
