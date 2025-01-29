<div wire:ignore.self class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasStart"
    aria-labelledby="offcanvasStartLabel">
    <div class="offcanvas-header border-bottom">
        <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Modifier étudiant</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0 p-6 h-100">
        <form class="add-new-user pt-0" id="addNewUserForm" wire:submit='update'>
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
                    <label class="form-label" for="niveau">Niveau</label>
                    <select id="niveau-update" class="select2 form-select @error('niveau') is-invalid @enderror">
                        <option value="">Selectionner le niveau</option>
                    </select>
                </div>
                @error('niveau')
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
            // $('#niveau').select2();
            $('#niveau-update').on('change', function(e) {
                var data = $('#niveau-update').select2("val");
                @this.set('niveau', data);
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

        });
    </script>
@endscript
