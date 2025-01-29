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
                    <label class="form-label" for="nom">Nom</label>
                    <input type="text" wire:model='nom' class="form-control @error('nom') is-invalid @enderror"
                        id="nom" name="nom" />
                    @error('nom')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-6">
                    <label class="form-label" for="prenom">Prénom</label>
                    <input type="text" wire:model='prenom' class="form-control @error('prenom') is-invalid @enderror"
                        id="prenom" name="nom" />
                    @error('prenom')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-6">
                    <label class="form-label" for="add-user-email">Email</label>
                    <input type="text" wire:model='email' id="add-user-email"
                        class="form-control @error('email') is-invalid @enderror" name="email" />
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-6">
                    <label class="form-label" for="add-user-contact">Téléphone</label>
                    <input type="text" wire:model='telephone' id="add-user-contact"
                        class="form-control phone-mask @error('telephone') is-invalid @enderror" name="telephone" />
                    @error('telephone')
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
                        <div class="text-danger mt-1">{{ $message }}</div>
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
            // $('#niveau').select2();
            $('#niveau').on('change', function(e) {
                var data = $('#niveau').select2("val");
                @this.set('niveau', data);
            });
        });

        $wire.on('create-event', () => {
            //
            let niveaux = @this.call('getNiveaux').then(niveaux => {

                // $('#niveau').select2('destroy');

                // Vider les options actuelles
                $('#niveau').empty();

                // Ajouter l'option par défaut
                $('#niveau').append('<option value="">Sélectionnez un niveau</option>');

                // Ajouter les nouvelles options dynamiquement
                niveaux.forEach(function(niveau) {

                    $('#niveau').append('<option value="' + niveau.id + '">' + niveau.nom +
                        '</option>');

                });

                // Réinitialiser Select2
                // $('#niveau').select2();

            });

        });
    </script>
@endscript
