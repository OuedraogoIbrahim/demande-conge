<div class="row">

    <div class="col-md-12">
        <div class="nav-align-top">
            <ul class="nav nav-pills flex-column flex-md-row mb-6 gap-2 gap-lg-0">
                <li class="nav-item"><a class="nav-link active" href="javascript:void(0);">
                        <i class="ri-group-line me-1_5"></i>Données</a>
                </li>
            </ul>
        </div>
        <div class="card mb-6">
            <!-- Account -->
            <div class="card-body">

            </div>
            <div class="card-body pt-0">
                <form id="formAccountSettings" method="POST" wire:submit="update">
                    <div class="row mt-1 g-5">
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control @error('nom') is-invalid @enderror" type="text"
                                    id="firstName" name="firstName" wire:model='nom' />
                                <label for="firstName">Nom</label>
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control @error('prenom') is-invalid @enderror" type="text"
                                    name="lastName" id="lastName" wire:model='prenom' />
                                <label for="lastName">Prénom</label>
                                @error('prenom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control @error('email') is-invalid @enderror" type="text"
                                    id="email" name="email" wire:model='email' />
                                <label for="email">E-mail</label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control @error('telephone') is-invalid @enderror"
                                    id="telephone" name="telephone" wire:model='telephone' />
                                <label for="telephone">Téléphone</label>
                                @error('telephone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" disabled class="form-control" id="role" name="role"
                                    wire:model='role' />
                                <label for="role">Role</label>
                            </div>
                        </div>

                        @if ($user->role == 'etudiant')
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" disabled class="form-control" id="filiere" name="filiere"
                                        value="{{ $user->student()->first()->filiere->nom }}" />
                                    <label for="filiere">Filière</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" disabled class="form-control" id="filiere" name="niveau"
                                        value="{{ $user->student()->first()->niveau->nom }}" />
                                    <label for="niveau">Niveau</label>
                                </div>
                            </div>
                        @endif


                        @if ($filiereCoordinateur)
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" disabled class="form-control" id="filCord" name="filCord"
                                        wire:model='filiereCoordinateur' />
                                    <label for="filCord">Coordinateur de la filière</label>
                                </div>
                            </div>
                        @endif

                        @if ($modulesEnseignes)
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <div class="form-control bg-light text-dark" style="height: auto;">
                                        <ul class="mb-0">
                                            @foreach ($modulesEnseignes as $m)
                                                <li>{{ $m->nom . ' ( Niveau : ' . $m->niveau->nom . ' | Filière : ' . $m->filiere->nom . ' )' }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <label for="modulesEnseignes">Modules enseignés</label>
                                </div>
                            </div>
                        @endif


                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" disabled class="form-control" id="etablissement"
                                    name="etablissement" wire:model='etablissement' />
                                <label for="etablissement">Etablissement</label>
                            </div>
                        </div>

                    </div>
                    <div class="mt-6">
                        <button type="submit" class="btn btn-primary me-3" wire:loading.attr='disabled'>
                            <span wire:loading.remove> Sauvegarder </span>
                            <div wire:loading>
                                <span class="spinner-border" text-primary role="status" aria-hidden="true"></span>
                            </div>
                        </button>
                        <button type="button" wire:click='reinitialiser'
                            class="btn btn-outline-secondary">Réinitialiser</button>
                    </div>
                </form>
            </div>
            <!-- /Account -->
        </div>

        @if (Illuminate\Support\Facades\Auth::user()->role == 'etudiant')
            <div class="card">
                <h5 class="card-header">Mes Notes</h5>
                <div class="card-body">
                    @php
                        $notes = json_decode($user->note?->donnees, true);
                    @endphp

                    @if ($notes && count($notes) > 0)
                        <ul class="list-group">
                            @foreach ($notes as $matiere => $details)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><strong>{{ $matiere }}</strong></span>
                                    @if (isset($details['note']))
                                        <span class="badge bg-success rounded-pill">{{ $details['note'] }}/20</span>
                                    @else
                                        <span class="badge bg-warning rounded-pill">Aucune note disponible</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="alert alert-info" role="alert">
                            Aucune note enregistrée.
                        </div>
                    @endif
                </div>
            </div>
        @endif


        <div class="card mt-6">
            <h5 class="card-header">Supprimer mon compte</h5>
            <div class="card-body">
                <form id="formAccountDeactivation" wire:submit="deleteAccount">
                    <div class="form-check mb-6 ms-3">
                        <input class="form-check-input" type="checkbox" name="accountActivation"
                            id="accountActivation" wire:model.live="hasDeleted" />
                        <label class="form-check-label" for="accountActivation">
                            Je confirme la suppression de mon compte
                        </label>
                    </div>
                    <button type="submit" class="btn btn-danger deactivate-account"
                        {{ !$hasDeleted ? 'disabled' : '' }}>
                        Supprimer
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
