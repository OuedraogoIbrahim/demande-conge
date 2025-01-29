<div>
    <div class="card">

        <div class="card-header border-bottom">
            <h5 class="card-title mb-0">Filtres</h5>
            <div class="d-flex justify-content-between align-items-center row pt-4 gap-4 gap-md-0">

                <div class="col-md-4" wire:ignore>
                    <label for="selectFiliere" class="form-label">Filière</label>
                    <select wire:model='filiere' id="selectFiliere" class="select2 form-select form-select-lg"
                        data-allow-clear="true">
                        <option value="">Selectionner la filière</option>
                        @foreach ($filieres as $f)
                            <option value={{ $f->id }}>{{ $f->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label>Nom du niveau</label>
                    <input type="search" wire:model.live.debounce.1000ms='search' class="form-control" placeholder="">
                </div>

                <div class="col-md-4 user_status"></div>
            </div>
        </div>

        <div class="row m-5">

            <div class="col-md-12">
                <div
                    class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-6 mb-md-0 mt-n6 mt-md-0">
                    <div class="dt-buttons btn-group flex-wrap">

                        <button class="btn btn-secondary add-new btn-primary waves-effect waves-light mb-4"
                            tabindex="0" aria-controls="DataTables_Table_0" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasAddUser"><span><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                                    class="d-none d-sm-inline-block">Ajouter un niveau</span></span></button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-datatable table-responsive">
            <table class="datatables-users table">
                <thead class="border-top">
                    <tr>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Filiere</th>
                        @if (Illuminate\Support\Facades\Auth::user()->role == 'superviseur')
                            <th>Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($niveaux as $niveau)
                        <tr>
                            <th>{{ $niveau->nom }}</th>
                            <th>{{ $niveau->description }}</th>
                            <th>{{ $niveau->filiere->nom }}</th>
                            @if (Illuminate\Support\Facades\Auth::user()->role == 'superviseur')
                                <td class="" style="">
                                    <div class="d-flex align-items-center">
                                        <a href="javascript:void(0);"
                                            onclick="confirmDelete(event, '{{ $niveau->id }}')"
                                            class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill delete-record">
                                            <i class="ti ti-trash ti-md"></i>
                                        </a>

                                        <button wire:click="sendNiveau('{{ $niveau->id }}')"
                                            class="btn btn-sm btn-icon edit-record btn-text-secondary rounded-pill waves-effect"
                                            data-id="11" data-bs-toggle="offcanvas" data-bs-target="#offcanvasStart"
                                            aria-controls="offcanvasStart">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="my-4">
                {{ $niveaux->links('pagination::bootstrap-5') }}
            </div>
        </div>

        <script>
            function confirmDelete(event, niveauId) {
                event.preventDefault();

                Swal.fire({
                    title: 'Êtes-vous sûr ?',
                    text: 'Vous ne pourrez pas revenir en arrière !',
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
                            title: 'Niveau supprimée avec succès.',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        @this.call('deleteNiveau', niveauId); // Appelez la méthode Livewire pour supprimer
                    }
                });
            }
        </script>
        <!-- Offcanvas to add new user -->

    </div>

</div>

@script
    <script>
        $(document).ready(function() {
            // $('#selectFiliere').select2();
            $('#selectFiliere').on('change', function(e) {
                var data = $('#selectFiliere').select2("val");
                @this.set('filiere', data);
            });
        });
    </script>
@endscript
