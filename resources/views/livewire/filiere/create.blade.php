<div>

    <div wire:ignore.self class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddUser"
        aria-labelledby="offcanvasAddUserLabel">
        <div class="offcanvas-header border-bottom">
            <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Ajouter une filière</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body mx-0 flex-grow-0 p-6 h-100">
            <form class="add-new-user pt-0" id="addNewUserForm" wire:submit='submit'>
                <div class="mb-6">
                    <label class="form-label" for="name">Nom de la filière</label>
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

                <button type="submit" class="btn btn-primary me-3 data-submit" wire:loading.attr="disabled">
                    <span wire:loading.remove>Ajouter la filière</span>
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
