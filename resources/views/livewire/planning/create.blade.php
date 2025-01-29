<div>
    @if ($showForm)

        <form wire:submit="save"
            class="p-4 bg-white border rounded shadow position-absolute top-50 start-50 translate-middle"
            style="min-width: 700px; z-index: 1050; margin-top :40px">


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


            <!-- Champ filiere -->
            <div class="mb-3">
                <label for="title" class="form-label">Filiere</label>
                <select id="filiere-select" class="form-control @error('filiere') is-invalid @enderror"
                    wire:model="filiere" wire:change="resetSelection('filiere')">
                    <option value="">Sélectionnez une filière</option>
                    @foreach ($filieres as $f)
                        <option value="{{ $f->id }}">{{ $f->nom }}</option>
                    @endforeach
                </select>
                @error('filiere')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            @if ($filiere)

                <!-- Champ niveau -->
                <div class="mb-3">
                    <label for="title" class="form-label">Niveau</label>
                    <select id="niveau-select" class="form-control @error('niveau') is-invalid @enderror"
                        wire:model="niveau" wire:change="resetSelection('niveau')">
                        <option value="">Sélectionnez le niveau</option>
                        @foreach ($niveaux as $n)
                            <option value="{{ $n->id }}">{{ $n->nom }}</option>
                        @endforeach
                    </select>
                    @error('niveau')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            @endif

            @if ($niveau && $filiere)

                <!-- Champ modules -->
                <div class="mb-3">
                    <label for="title" class="form-label">Module</label>
                    <select id="module-select" class="form-control @error('module') is-invalid @enderror"
                        wire:model="module" wire:change="resetSelection('module')">
                        <option value="">Sélectionnez le module</option>
                        @foreach ($modules as $m)
                            <option value="{{ $m->id }}">{{ $m->nom }}</option>
                        @endforeach
                    </select>
                    @error('module')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

            @endif


            @if ($module)
                <!-- Champ classe -->
                <div class="mb-3">
                    <label for="title" class="form-label">Classe</label>
                    <select id="classe-select" class="form-control @error('classe') is-invalid @enderror"
                        wire:model="classe">
                        <option value="">Sélectionnez la classe</option>
                        @foreach ($classes as $c)
                            <option value="{{ $c->id }}">{{ $c->nom }}</option>
                        @endforeach
                    </select>
                    @error('classe')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            @endif

            <!-- Champ Date -->
            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" id="date" wire:model="date"
                    class="form-control @error('date') is-invalid @enderror">
                @error('date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Champ Début -->
            <div class="mb-3">
                <label for="start" class="form-label">Heure début</label>
                <input type="time" id="start" wire:model="start"
                    class="form-control @error('start') is-invalid @enderror">
                @error('start')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Champ Fin -->
            <div class="mb-3">
                <label for="end" class="form-label">Heure fin</label>
                <input type="time" id="end" wire:model="end"
                    class="form-control @error('end') is-invalid @enderror">
                @error('end')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Checkbox Toute la journée -->
            <div class="form-check mb-3">
                <input type="checkbox" id="is_duty" wire:model="isDuty"
                    class="form-check-input @error('isDuty') is-invalid @enderror">
                <label for="is_duty" class="form-check-label">Est ce un devoir ? </label>
                @error('isDuty')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Boutons d'action -->
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">{{ $event ? 'Modifier' : 'Sauvegarder' }}</button>
                <button type="button" class="btn btn-secondary" wire:click="cancel">Annuler</button>
            </div>

            <div wire:loading class="position-absolute top-50 start-50 translate-middle">
                <div class="spinner-border spinner-border-lg text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </form>

    @endif

</div>
