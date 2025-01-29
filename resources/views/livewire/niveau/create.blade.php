<div>

    <div wire:ignore.self class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddUser"
        aria-labelledby="offcanvasAddUserLabel">
        <div class="offcanvas-header border-bottom">
            <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Ajouter un niveau</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body mx-0 flex-grow-0 p-6 h-100">
            <form class="add-new-user pt-0" id="addNewUserForm" wire:submit='submit'>
                <div class="mb-6">
                    <label class="form-label" for="name">Nom du niveau</label>
                    <input autocomplete="off" type="text" wire:model='name'
                        class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                        placeholder="Licence 1 , Licence 2 , Master" />
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

                <button type="submit" class="btn btn-primary me-3 data-submit" wire:loading.attr="disabled">
                    <span wire:loading.remove>Ajouter le niveau</span>
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
            });
        });
    </script>
@endscript
