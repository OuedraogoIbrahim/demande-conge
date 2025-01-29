<div class="row">

    {{-- @if (!$filiere) --}}

    @if (isset($niveaux) && $niveaux->isEmpty())
        <div class="alert alert-warning d-flex align-items-center justify-content-between" role="alert">
            <div>
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                Aucun niveau pour cette filiere
            </div>
            <a href="{{ route('niveaux.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Créer un niveau
            </a>
        </div>
    @endif

    @if (isset($modules) && $modules->isEmpty())
        <div class="alert alert-warning d-flex align-items-center justify-content-between" role="alert">
            <div>
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                Aucun module pour ce niveau
            </div>
            <a href="{{ route('modules.create', ['filiere' => $filiere]) }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Créer un module
            </a>
        </div>
    @endif

    {{-- @endif --}}


    <div class="col-xxl">
        <div class="card mb-6">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Ajouter un professeur</h5>
                <small class="text-muted float-end"></small>
            </div>
            <div class="card-body">
                <form method="POST" wire:submit='update'>
                    @csrf
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label" for="basic-nom">Nom</label>
                            <input wire:model='nom' type="text"
                                class="form-control @error('nom') is-invalid @enderror" id="basic-nom" name="nom"
                                value="{{ old('nom') }}" />
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="basic-prenom">Prénom</label>
                            <input wire:model='prenom' type="text"
                                class="form-control @error('prenom') is-invalid @enderror" id="basic-prenom"
                                name="prenom" value="{{ old('prenom') }}" />
                            @error('prenom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label" for="basic-email">Email</label>
                            <input wire:model='email' type="email"
                                class="form-control @error('email') is-invalid @enderror" id="basic-email"
                                name="email" value="{{ old('email') }}" />
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="basic-telephone">Téléphone</label>
                            <input wire:model='telephone' type="text"
                                class="form-control @error('telephone') is-invalid @enderror" id="basic-telephone"
                                name="telephone" value="{{ old('telephone') }}" />
                            @error('telephone')
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

                    @if ($niveau && $filiere)
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label" for="basic-modules">Module</label>
                                <select wire:model='module' name="module[]"
                                    class="form-control @error('modules') is-invalid @enderror" id="basic-modules"
                                    multiple>
                                    <option value="">Sélectionner le(s) module(s)</option>
                                    @foreach ($modules as $m)
                                        <option value="{{ $m->id }}">{{ $m->nom }}</option>
                                    @endforeach
                                </select>
                                @error('modules')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label d-block" for="coordinateur">Mettre comme coordinateur ?</label>

                            <!-- Boutons radio pour Oui / Non -->
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="coordinateur"
                                    wire:model="coordinateur" id="coordinateur-oui" value="oui">
                                <label class="form-check-label" for="coordinateur-oui">Oui</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="coordinateur"
                                    wire:model="coordinateur" id="coordinateur-non" value="non">
                                <label class="form-check-label" for="coordinateur-non">Non</label>
                            </div>

                            @error('coordinateur')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row justify-content-end">
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <!-- Affiche "Créer" quand le formulaire n'est pas en soumission -->
                                <span wire:loading.remove>Modifier</span>
                                <!-- Affiche un loader pendant la soumission -->
                                <div wire:loading>
                                    <span class="spinner-border" text-primary role="status"
                                        aria-hidden="true"></span>
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
