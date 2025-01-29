<div class="row">
    <div class="col-md-12">
        <div class="card mb-6">
            <div class="card-body">
                <form id="formEtablissement" method="POST" wire:submit="update">
                    <div class="row g-5">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="defaultInput" class="form-label">Etablissement</label>
                                <input id="logo" name="etablissement" wire:model='nom'
                                    class="form-control @error('nom') is-invalid @enderror" type="text"
                                    placeholder="Nom de l'établissement">
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="formFile"
                                    class="form-label">Logo</label>
                                <input class="form-control" type="file" id="formFile" name="logo"
                                    wire:model='logo' accept="image/*">
                                @error('logo')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <div class="mt-6 d-flex align-items-center justify-content-between">
                        <div>
                            <button type="submit" class="btn btn-primary me-3" wire:loading.attr="disabled"
                                wire:target="update">
                                <span wire:loading.remove> Sauvegarder </span>
                                <div wire:loading>
                                    <span class="spinner-border" text-primary role="status" aria-hidden="true"></span>
                                </div>
                            </button>
                            <button type="button" wire:click="reinitialiser" class="btn btn-outline-secondary"
                                wire:loading.attr="disabled" wire:target="reinitialiser">
                                <span wire:loading.remove> Réinitialiser </span>
                                <div wire:loading>
                                    <span class="spinner-border text-secondary" role="status"
                                        aria-hidden="true"></span>
                                </div>
                            </button>
                        </div>

                        @if ($logo)
                            <!-- Bouton Prévisualiser le logo -->
                            <div>
                                <button type="button" class="btn btn-info" data-bs-toggle="modal"
                                    data-bs-target="#logoModal" onclick="previewLogo()">
                                    <i class="ri-eye-line me-1"></i> Prévisualiser le nouveau logo
                                </button>
                            </div>
                        @endif

                    </div>

                    <div class="modal fade" id="logoModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Aperçu du logo</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <img id="logoPreview" src="#" alt="Aperçu du logo"
                                        class="img-fluid img-thumbnail d-none" style="max-width: 300px;">
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>

        <div class="card">
            <h5 class="card-header">Supprimer l'établissement</h5>
            <div class="card-body">
                <div class="form-check mb-6 ms-3">
                    <input class="form-check-input" type="checkbox" name="confirmationSuppression"
                        id="confirmationSuppression" wire:model.live="hasConfirmed" />
                    <label class="form-check-label" for="confirmationSuppression">
                        Je confirme la suppression de mon établissement
                    </label>
                </div>
                <button onclick="confirmDelete(event)" type="button" class="btn btn-danger"
                    {{ !$hasConfirmed ? 'disabled' : '' }} wire:loading.attr="disabled">
                    <span wire:loading.remove> Supprimer </span>
                    <div wire:loading>
                        <span class="spinner-border text-light" role="status" aria-hidden="true"></span>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(event) {
            event.preventDefault();

            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: 'Vous ne pourrez pas revenir en arrière ! La suppression entrainera la perte de toutes les données liés a votre établissement',
                imageUrl: "{{ asset('assets/lordicon/delete.gif') }}",
                // icon: 'warning',
                imageWidth: 100, // Largeur du GIF
                imageHeight: 100, // Hauteur du GIF
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer !',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: "success",
                        title: 'Etablissement supprimé avec succès.',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    @this.call('delete'); // Appelez la méthode Livewire pour supprimer
                }
            });
        }
    </script>

</div>

<script>
    function previewLogo() {
        const fileInput = document.getElementById('logo');
        const logoPreview = document.getElementById('logoPreview');

        if (fileInput.files && fileInput.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                logoPreview.src = e.target.result;
                logoPreview.classList.remove('d-none');
            };

            reader.readAsDataURL(fileInput.files[0]);
        }
    }
</script>
