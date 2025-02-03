<div wire:ignore.self class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasStart"
    aria-labelledby="offcanvasStartLabel">
    <div class="offcanvas-header border-bottom">
        <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Modifier un service</h5>
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

            $('#service-update').on('change', function(e) {
                var data = $(this).val(); // Récupère la valeur sélectionnée
                @this.set('service', data); // Met à jour la propriété Livewire
                // @this.call('selectNiveau');
            });
        });

        // Initialisation des select avec les infos de l'utilisateur
        $wire.on('update-event', () => {

            // service
            let serviceSelectionne = @this.get('service'); // Récupère la filière sélectionnée

            @this.call('getServices').then(services => {

                // Vider les options actuelles
                $('#service-update').empty();

                // Ajouter l'option par défaut
                $('#service-update').append('<option value="">Sélectionnez un service</option>');

                // Ajouter les nouvelles options dynamiquement et sélectionner la bonne filière
                services.forEach(function(service) {

                    // Vérifier si cette filière correspond à celle sélectionnée
                    let selected = (service.id == serviceSelectionne) ? 'selected' : '';

                    // Ajouter l'option avec l'attribut selected si la filière correspond
                    $('#service-update').append('<option value="' + service.id + '" ' + selected +
                        '>' + service
                        .nom + '</option>');
                });

            });

        });
    </script>
@endscript
