<div wire:ignore.self class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasStart"
    aria-labelledby="offcanvasStartLabel">
    <div class="offcanvas-header border-bottom">
        <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Modifier Document</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0 p-6 h-100">
        <form class="add-new-user pt-0" id="addNewUserForm" wire:submit='update'>
            <div class="mb-6">
                <label class="form-label" for="titre">Titre</label>
                <input type="text" wire:model='titre' class="form-control @error('titre') is-invalid @enderror"
                    id="titre" name="titre" />
                @error('titre')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-6">
                <label class="form-label" for="description">Description</label>
                <textarea wire:model='description' class="form-control @error('description') is-invalid @enderror" name="description"
                    id="description"></textarea>
                @error('description')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-6">
                <label class="form-label" for="document">Document</label>
                <input type="file" wire:model='fichier' id="document"
                    class="form-control @error('fichier') is-invalid @enderror" name="fichier" accept="application/pdf" />
                @error('fichier')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-6">
                <div wire:ignore>
                    <label class="form-label" for="filiere-update">Filière</label>
                    <select id="filiere-update" class="select2 form-select @error('filiere') is-invalid @enderror">
                        <option value="">Choisir la filière</option>
                    </select>
                </div>
                @error('filiere')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-6">
                <div wire:ignore>
                    <label class="form-label" for="niveau-update">Niveau</label>
                    <select id="niveau-update" class="select2 form-select @error('niveau') is-invalid @enderror">
                        <option value="">Selectionner le niveau</option>
                    </select>
                </div>
                @error('niveau')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-6">
                <div wire:ignore>
                    <label class="form-label" for="module-update">Modules</label>
                    <select id="module-update" class="select2 form-select @error('module') is-invalid @enderror">
                        <option value="">Selectionner le module</option>
                    </select>
                </div>
                @error('module')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary me-3 data-submit" wire:loading.attr="disabled">
                <span wire:loading.remove>Mettre à jour</span>
                <span wire:loading>
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Chargement...
                </span>
            </button>

            <button type="reset" class="btn btn-label-danger" data-bs-dismiss="offcanvas">Fermer</button>
        </form>
    </div>
</div>


@script
    <script>
        $(document).ready(function() {

            $('#filiere-update').on('change', function(e) {
                var data = $(this).val(); // Récupère la valeur sélectionnée
                @this.set('filiere', data); // Met à jour la propriété Livewire
                @this.call('selectNiveau');
            });
        });

        $(document).ready(function() {
            $('#niveau-update').on('change', function(e) {
                var data = $('#niveau-update').select2("val");
                @this.set('niveau', data);
                @this.call('selectModule')
            });
        });

        $(document).ready(function() {
            $('#module-update').on('change', function(e) {
                var data = $('#module-update').select2("val");
                @this.set('module', data);
            });
        });

        // Initialisation des select avec les infos de l'utilisateur
        $wire.on('update-event', () => {

            // Filiere
            let filiereSelectionnee = @this.get('filiere'); // Récupère la filière sélectionnée

            @this.call('getFilieres').then(filieres => {

                // Vider les options actuelles
                $('#filiere-update').empty();

                // Ajouter l'option par défaut
                $('#filiere-update').append('<option value="">Sélectionnez une filière</option>');

                // Ajouter les nouvelles options dynamiquement et sélectionner la bonne filière
                filieres.forEach(function(filiere) {

                    // Vérifier si cette filière correspond à celle sélectionnée
                    let selected = (filiere.id == filiereSelectionnee) ? 'selected' : '';

                    // Ajouter l'option avec l'attribut selected si la filière correspond
                    $('#filiere-update').append('<option value="' + filiere.id + '" ' + selected +
                        '>' + filiere
                        .nom + '</option>');
                });


            });


            // Niveau
            let niveauSelectionne = @this.get('niveau'); // Récupère le niveau sélectionné

            @this.call('getNiveaux').then(niveaux => {

                // Vider les options actuelles
                $('#niveau-update').empty();

                // Ajouter l'option par défaut
                $('#niveau-update').append('<option value="">Sélectionnez un niveau</option>');

                // Ajouter les nouvelles options dynamiquement et sélectionner le bon niveau
                niveaux.forEach(function(niveau) {

                    // Vérifier si ce niveau correspond au niveau sélectionné
                    let selected = (niveau.id == niveauSelectionne) ? 'selected' : '';

                    // Ajouter l'option avec l'attribut selected si le niveau correspond
                    $('#niveau-update').append('<option value="' + niveau.id + '" ' + selected +
                        '>' + niveau.nom + '</option>');
                });

            });

            // Module
            let moduleSelectionne = @this.get('module'); // Récupère le niveau sélectionné

            @this.call('getModules').then(modules => {

                // Vider les options actuelles
                $('#module-update').empty();

                // Ajouter l'option par défaut
                $('#module-update').append('<option value="">Sélectionnez un module</option>');

                // Ajouter les nouvelles options dynamiquement et sélectionner le bon niveau
                modules.forEach(function(module) {

                    // Vérifier si ce module correspond au module sélectionné
                    let selected = (module.id == moduleSelectionne) ? 'selected' : '';

                    // Ajouter l'option avec l'attribut selected si le module correspond
                    $('#module-update').append('<option value="' + module.id + '" ' + selected +
                        '>' + module.nom + '</option>');
                });

            });

        });


        // Lorsque une filiere est choisi
        $wire.on('update-filiere-select', () => {

            // Niveau
            let niveauSelectionne = @this.get('niveau'); // Récupère le niveau sélectionné

            @this.call('getNiveaux').then(niveaux => {

                // Vider les options actuelles
                $('#niveau-update').empty();

                // Ajouter l'option par défaut
                $('#niveau-update').append('<option value="">Sélectionnez un niveau</option>');

                // Ajouter les nouvelles options dynamiquement et sélectionner le bon niveau
                niveaux.forEach(function(niveau) {
                    // Vérifier si ce niveau correspond au niveau sélectionné
                    let selected = (niveau.id == niveauSelectionne) ? 'selected' : '';

                    // Ajouter l'option avec l'attribut selected si le niveau correspond
                    $('#niveau-update').append('<option value="' + niveau.id + '" ' + selected +
                        '>' + niveau.nom + '</option>');
                });

            });

            // Module
            let moduleSelectionne = @this.get('module'); // Récupère le niveau sélectionné

            @this.call('getModules').then(modules => {

                // Vider les options actuelles
                $('#module-update').empty();

                // Ajouter l'option par défaut
                $('#module-update').append('<option value="">Sélectionnez un module</option>');

                // Ajouter les nouvelles options dynamiquement et sélectionner le bon niveau
                modules.forEach(function(m) {

                    // Vérifier si ce module correspond au module sélectionné
                    let selected = (m.id == moduleSelectionne) ? 'selected' : '';

                    // Ajouter l'option avec l'attribut selected si le module correspond
                    $('#module-update').append('<option value="' + m.id + '" ' + selected +
                        '>' + m.nom + '</option>');
                });

            });



        });


        // Lorsque un niveau est choisi
        $wire.on('update-niveau-select', () => {

            // Module
            let moduleSelectionne = @this.get('module'); // Récupère le niveau sélectionné

            @this.call('getModules').then(modules => {
                console.log(modules);

                // Vider les options actuelles
                $('#module-update').empty();

                // Ajouter l'option par défaut
                $('#module-update').append('<option value="">Sélectionnez un module</option>');

                // Ajouter les nouvelles options dynamiquement et sélectionner le bon niveau
                modules.forEach(function(module) {

                    // Vérifier si ce module correspond au module sélectionné
                    let selected = (module.id == moduleSelectionne) ? 'selected' : '';

                    // Ajouter l'option avec l'attribut selected si le module correspond
                    $('#module-update').append('<option value="' + module.id + '" ' + selected +
                        '>' + module.nom + '</option>');
                });

            });

        });
    </script>
@endscript
