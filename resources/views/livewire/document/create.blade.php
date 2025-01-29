<div>
    <div wire:ignore.self class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddUser"
        aria-labelledby="offcanvasAddUserLabel">
        <div class="offcanvas-header border-bottom">
            <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Ajouter un étudiant</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body mx-0 flex-grow-0 p-6 h-100">
            <form class="add-new-user pt-0" id="addNewUserForm" wire:submit='submit'>
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
                        <label class="form-label" for="filiere">Filière</label>
                        <select id="filiere" class="select2 form-select @error('filiere') is-invalid @enderror">
                            <option value="">Choisir la filière</option>
                            @foreach ($filieres as $f)
                                <option value={{ $f->id }}>{{ $f->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('filiere')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-6">
                    <div wire:ignore>
                        <label class="form-label" for="niveau">Niveau</label>
                        <select id="niveau" class="select2 form-select @error('niveau') is-invalid @enderror">
                            <option value="">Selectionner le niveau</option>
                            @foreach ($niveaux as $n)
                                <option value={{ $n->id }}>{{ $n->nom . '(' . $n->filiere->nom . ')' }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('niveau')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-6">
                    <div wire:ignore>
                        <label class="form-label" for="module">Modules</label>
                        <select id="module" class="select2 form-select @error('module') is-invalid @enderror">
                            <option value="">Selectionner le module</option>
                            @foreach ($modules as $m)
                                <option value={{ $m->id }}>
                                    {{ $m->nom . '(' . $m->filiere->nom . ' | ' . $m->niveau->nom . ')' }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('module')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary me-3 data-submit" wire:loading.attr="disabled">
                    <span wire:loading.remove>Ajouter</span>
                    <span wire:loading>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Chargement...
                    </span>
                </button>

                <button type="reset" class="btn btn-label-danger" data-bs-dismiss="offcanvas">Fermer</button>
            </form>
        </div>
    </div>
</div>

@script
    <script>
        $(document).ready(function() {
            $('#filiere').on('change', function(e) {
                var data = $(this).val(); // Récupère la valeur sélectionnée
                @this.set('filiere', data); // Met à jour la propriété Livewire
                @this.call('selectNiveau');
            });
        });

        $(document).ready(function() {
            $('#niveau').on('change', function(e) {
                var data = $('#niveau').select2("val");
                @this.set('niveau', data);
                @this.call('selectModule')
            });
        });

        $(document).ready(function() {
            $('#module').on('change', function(e) {
                var data = $('#module').select2("val");
                @this.set('module', data);
            });
        });

        $wire.on('create-niveau-event', () => {
            //
            let niveaux = @this.call('getNiveaux').then(niveaux => {

                // Vider les options actuelles
                $('#niveau').empty();

                // Ajouter l'option par défaut
                $('#niveau').append('<option value="">Sélectionnez un niveau</option>');

                // Ajouter les nouvelles options dynamiquement
                niveaux.forEach(function(niveau) {
                    $('#niveau').append('<option value="' + niveau.id + '">' + niveau.nom +
                        '</option>');

                });

            });

            let modules = @this.call('getModules').then(modules => {

                // Vider les options actuelles
                $('#module').empty();

                // Ajouter l'option par défaut
                $('#module').append('<option value="">Sélectionnez un module</option>');

                // Ajouter les nouvelles options dynamiquement
                modules.forEach(function(module) {
                    $('#module').append('<option value="' + module.id + '">' + module.nom +
                        '</option>');
                });

            });

        });

        $wire.on('create-module-event', () => {
            //
            let modules = @this.call('getModules').then(modules => {

                // Vider les options actuelles
                $('#module').empty();

                // Ajouter l'option par défaut
                $('#module').append('<option value="">Sélectionnez un module</option>');

                // Ajouter les nouvelles options dynamiquement
                modules.forEach(function(module) {
                    $('#module').append('<option value="' + module.id + '">' + module.nom +
                        '</option>');

                });

            });

        });
    </script>
@endscript
