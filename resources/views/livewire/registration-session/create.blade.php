<div class="row">

    @if (isset($niveaux) && $niveaux->isEmpty())
        <div class="alert alert-warning d-flex align-items-center justify-content-between" role="alert">
            <div>
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                Aucun niveau pour cette filiere
            </div>

            @if (Auth::user()->role == 'superviseur')
                <a href="{{ route('niveaux.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Créer un niveau
                </a>
            @endif

        </div>
    @endif

    <div class="col-xxl">
        <div class="card mb-6">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Ajouter </h5>
                <small class="text-muted float-end">Session d'inscription</small>
            </div>
            <div class="card-body">
                <form method="POST" wire:submit='submit'>
                    @csrf
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label" for="basic-nom">Titre</label>
                            <input wire:model='titre' type="text"
                                class="form-control @error('titre') is-invalid @enderror" id="basic-titre"
                                name="titre" autocomplete="off" />
                            @error('titre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="basic-date_fin">Date de fin de la session</label>
                            <input wire:model='date_fin' type="date"
                                class="form-control @error('date_fin') is-invalid @enderror" id="basic-date_fin"
                                name="date_fin" />
                            @error('date_fin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label" for="basic-filieres">Filière</label>
                            <select wire:model.live='filiere' wire:change="resetSelection('filiere')"
                                class="form-control @error('filiere') is-invalid @enderror" id="basic-filieres"
                                name="filiere">
                                <option value="">Sélectionner une filière</option>
                                @foreach ($filieres as $f)
                                    <option value="{{ $f->id }}">{{ $f->nom }}</option>
                                @endforeach
                            </select>
                            @error('filiere')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if ($filiere)
                            <div class="col-md-6">
                                <label class="form-label" for="basic-niveaux">Niveau</label>
                                <select wire:model.live='niveau' wire:change="resetSelection('niveau')"
                                    class="form-control @error('niveau') is-invalid @enderror" id="basic-niveaux"
                                    name="niveau">
                                    <option value="">Sélectionner un niveau</option>
                                    @foreach ($niveaux as $n)
                                        <option value="{{ $n->id }}">{{ $n->nom }}</option>
                                    @endforeach
                                </select>
                                @error('niveau')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                    </div>

                    <div class="row justify-content-end">
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <!-- Affiche "Créer" quand le formulaire n'est pas en soumission -->
                                <span wire:loading.remove>Créer</span>
                                <!-- Affiche un loader pendant la soumission -->
                                <div wire:loading>
                                    <span class="spinner-border" text-primary role="status" aria-hidden="true"></span>
                                </div>
                            </button>
                        </div>
                    </div>

                    <div wire:loading class="position-fixed top-50 start-50 translate-middle">
                        <div class="spinner-border spinner-border-lg text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
