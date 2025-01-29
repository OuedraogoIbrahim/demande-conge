<div wire:ignore.self class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasStart"
    aria-labelledby="offcanvasStartLabel">
    <div class="offcanvas-header border-bottom">
        <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Modifier module</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0 p-6 h-100">
        <form class="add-new-user pt-0" id="addNewUserForm" wire:submit='update'>
            <div class="mb-6">
                <label class="form-label" for="name">Nom du module</label>
                <input autocomplete="off" type="text" wire:model='name'
                    class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                    placeholder="" />
                @error('name')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-6">
                <label class="form-label" for="prenom">Description</label>
                <textarea wire:model='description' class="form-control @error('description') is-invalid @enderror" name="description"
                    id="description"></textarea>
                @error('description')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-6">
                <label for="coefficient">Coefficient</label>
                <input wire:model='coefficient' class="form-control @error('coefficient') is-invalid @enderror"
                    type="number" id="coefficient">
                @error('coefficient')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-6">
                <label for="nombre_heures">Nombre d'heures du module</label>
                <input wire:model='nombre_heures' class="form-control @error('nombre_heures') is-invalid @enderror"
                    type="number" id="nombre_heures">
                @error('nombre_heures')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-6">
                <div wire:ignore>
                    <label class="form-label" for="niveau-update">Niveau</label>
                    <select id="niveau-update" class="select2 form-select @error('niveau') is-invalid @enderror">
                        <option value="">Choisir le niveau</option>
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

            $('#niveau-update').on('change', function(e) {
                var data = $(this).val(); // Récupère la valeur sélectionnée
                @this.set('niveau', data); // Met à jour la propriété Livewire
            });
        });

        $wire.on('update-event', () => {
            // Niveau
            let niveauSelectionnee = @this.get('niveau'); // Récupère la filière sélectionnée

            @this.call('getNiveaux').then(niveaux => {

                // Vider les options actuelles
                $('#niveau-update').empty();

                // Ajouter l'option par défaut
                $('#niveau-update').append('<option value="">Sélectionnez le niveau</option>');

                // Ajouter les nouvelles options dynamiquement et sélectionner la bonne filière
                niveaux.forEach(function(niveau) {

                    // Vérifier si cette filière correspond à celle sélectionnée
                    let selected = (niveau.id == niveauSelectionnee) ? 'selected' : '';

                    // Ajouter l'option avec l'attribut selected si la filière correspond
                    $('#niveau-update').append('<option value="' + niveau.id + '" ' + selected +
                        '>' + niveau.nom + '</option>');
                });

            });

        });
    </script>
@endscript
