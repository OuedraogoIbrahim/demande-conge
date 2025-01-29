<div class="row">

    @error('filiere')
        <div class="alert alert-danger alert-dismissible" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    @enderror

    @error('module')
        <div class="alert alert-danger alert-dismissible" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    @enderror

    {{-- @if (isset($niveaux) && $niveaux->isEmpty())
        <div class="alert alert-warning d-flex align-items-center justify-content-between" role="alert">
            <div>
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                Aucun niveau pour cette filiere
            </div>
            <a href="{{ route('niveaux.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Créer un niveau
            </a>
        </div>
    @endif --}}

    {{-- @if (isset($modules) && $modules->isEmpty())
        <div class="alert alert-warning d-flex align-items-center justify-content-between" role="alert">
            <div>
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                Aucun module pour ce niveau
            </div>
            <a href="{{ route('modules.create', ['filiere' => $filiere]) }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Créer un module
            </a>
        </div>
    @endif --}}

    {{-- @endif --}}


    <div class="col-xxl">
        <div class="card mb-6">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Ajouter un professeur</h5>
                <small class="text-muted float-end"></small>
            </div>
            <div class="card-body">
                <form method="POST" wire:submit='submit'>
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
                        <div wire:ignore class="col-md-6">
                            <label class="form-label" for="filiere-tagify">Filière(s)</label>
                            <input id="filiere-tagify" type="text"
                                class="form-control @error('filiere') is-invalid @enderror" name="filiere">
                        </div>
                    </div>

                    <div wire:ignore class="row" id="niveau-container">
                    </div>

                    <div wire:ignore class="row" id="module-container">
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

@script
    <script>
        const input = document.querySelector('input[name="filiere"]')
        // init Tagify script on the above inputs
        const filieres = @json($filieres);

        tagify = new Tagify(input, {
            whitelist: filieres,
            maxTags: 10,
            dropdown: {
                maxItems: 20, // <- mixumum allowed rendered suggestions
                classname: 'tags-look', // <- custom classname for this dropdown, so it could be targeted
                enabled: 0, // <- show suggestions on focus
                closeOnSelect: false // <- do not hide the suggestions dropdown once an item has been selected
            }
        })

        tagify.on('change', function(e) {
            let data = [];

            // Vérifie si e.detail.value est vide
            if (e.detail.value && e.detail.value.trim() !== '') {
                try {
                    data = JSON.parse(e.detail.value);
                } catch (error) {
                    console.error("Erreur lors de l'analyse JSON :", error);
                    return;
                }
            }
            const filiereNames = data.map(item => item.value);

            // Mettre à jour la liste des filières dans Livewire
            @this.set('filiere', filiereNames);

            // Si la liste est vide, appeler une méthode Livewire pour réinitialiser les niveaux
            if (filiereNames.length === 0) {

                @this.call('resetValue',
                    'niveau'); // Appelle une méthode backend pour réinitialiser
                const niveauContainer = document.getElementById('niveau-container');
                niveauContainer.innerHTML = '';
                const moduleContainer = document.getElementById('module-container');
                moduleContainer.innerHTML = '';
            } else {
                @this.call('selectNiveau'); // Met à jour les niveaux normalement
            }
        });


        $wire.on('create-niveau-event', () => {
            const niveauContainer = document.getElementById('niveau-container');
            niveauContainer.innerHTML = ''; // Réinitialise le conteneur des niveaux

            let levels = @this.get('niveaux');

            Object.entries(levels).forEach(([filiereId, niveauxx]) => {
                // Vérifier si l'input existe déjà
                if (document.getElementById(`niveau-tagify-${filiereId}`)) {
                    console.warn(`Input déjà créé pour la filière : ${filiereId}`);
                    return; // Ne pas recréer l'input
                }

                @this.call('getFiliere', filiereId).then(response => {
                    const filiereName = response.nom; // Récupérer le nom de la filière

                    // Créer le conteneur pour cette filière
                    const div = document.createElement('div');
                    div.classList.add('col-md-6');

                    // Créer le label
                    const label = document.createElement('label');
                    label.classList.add('form-label');
                    label.setAttribute('for', `niveau-tagify-${filiereId}`);
                    label.textContent = `Niveaux pour la filière : ${filiereName}`;

                    // Créer l'input
                    const input = document.createElement('input');
                    input.id = `niveau-tagify-${filiereId}`;
                    input.type = 'text';
                    input.classList.add('form-control');

                    // Ajouter le label et l'input dans le conteneur
                    div.appendChild(label);
                    div.appendChild(input);
                    niveauContainer.appendChild(div);

                    // Initialiser Tagify avec les données des niveaux
                    const data = niveauxx.map(niveau => niveau.nom);
                    const tagifyInstance = new Tagify(input, {
                        whitelist: data,
                        maxTags: 10,
                        dropdown: {
                            maxItems: 20,
                            classname: 'tags-look',
                            enabled: 0,
                            closeOnSelect: false
                        }
                    });

                    // Gestion de l'événement 'change' de Tagify
                    tagifyInstance.on('change', function(e) {
                        try {
                            // Vérification si e.detail.value n'est pas vide et peut être parsé
                            if (e.detail.value.trim() === '') {
                                @this.call('resetValue', 'module');
                                const moduleContainer = document.getElementById(
                                    'module-container');
                                if (moduleContainer) {
                                    moduleContainer.innerHTML = ''; // Effacer les modules
                                }
                                return; // Sortir de la fonction si la valeur est vide
                            }

                            // Essayer de parser la valeur JSON
                            const selectedData = JSON.parse(e.detail.value);

                            // Mettre à jour les niveaux dans Livewire
                            @this.set(`niveaux.${filiereId}`, selectedData.map(item => item
                                .value));
                            @this.call('selectModule');

                        } catch (error) {
                            console.error(`Erreur avec l'input ${input.id} :`, error);
                        }
                    });

                }).catch(error => {
                    console.error(`Erreur pour filière ${filiereId} :`, error);
                });
            });
        });


        $wire.on('create-module-event', () => {
            const moduleContainer = document.getElementById('module-container');
            moduleContainer.innerHTML = ''; // Réinitialiser le conteneur des modules

            let modules = @this.get('modules');

            Object.entries(modules).forEach(([filiereId, modulesx]) => {
                // Vérifier si l'input existe déjà
                if (document.getElementById(`module-tagify-${filiereId}`)) {
                    console.warn(`Input déjà créé pour la filière : ${filiereId}`);
                    return; // Ne pas recréer l'input
                }

                @this.call('getFiliere', filiereId).then(response => {
                    const filiereName = response.nom; // Récupérer le nom de la filière

                    // Créer le conteneur pour cette filière
                    const div = document.createElement('div');
                    div.classList.add('col-md-6');

                    // Créer le label
                    const label = document.createElement('label');
                    label.classList.add('form-label');
                    label.setAttribute('for', `module-tagify-${filiereId}`);
                    label.textContent = `Modules pour la filière : ${filiereName}`;

                    // Créer l'input
                    const input = document.createElement('input');
                    input.id = `module-tagify-${filiereId}`;
                    input.type = 'text';
                    input.classList.add('form-control');

                    // Ajouter le label et l'input dans le conteneur
                    div.appendChild(label);
                    div.appendChild(input);
                    moduleContainer.appendChild(div);

                    // Préparer les données pour Tagify
                    const data = modulesx.flatMap(moduleGroup =>
                        moduleGroup.map(module => ({
                            value: module.nom,
                            id: module.id
                        }))
                    );

                    // Initialiser Tagify avec les données des modules
                    const tagifyInstance = new Tagify(input, {
                        whitelist: data,
                        maxTags: 10,
                        dropdown: {
                            maxItems: 20,
                            classname: 'tags-look',
                            enabled: 0,
                            closeOnSelect: false
                        }
                    });

                    // Gestion de l'événement 'change' de Tagify
                    tagifyInstance.on('change', function(e) {
                        try {
                            if (e.detail.value.trim() === '') {
                                @this.call('resetValue', 'module');
                                return;
                            }
                            const selectedData = JSON.parse(e.detail.value);

                            // Envoyer les modules sélectionnés au backend
                            @this.set(`module.${filiereId}`, selectedData.map(
                                item => ({
                                    id: item.id,
                                    nom: item.value,
                                })));

                        } catch (error) {
                            console.error(`Erreur avec l'input ${input.id} :`, error);
                        }
                    });
                }).catch(error => {
                    console.error(`Erreur pour filière ${filiereId} :`, error);
                });
            });
        });
    </script>
@endscript
