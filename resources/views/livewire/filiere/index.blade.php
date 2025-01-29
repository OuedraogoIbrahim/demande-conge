<div>
    <div class="card">

        <div class="card-header border-bottom">
            <h5 class="card-title mb-0">Filtre</h5>
            <div class="d-flex justify-content-between align-items-center row pt-4 gap-4 gap-md-0">
                <div class="col-md-4">
                    <label>Nom de la filière</label>
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
                                    class="d-none d-sm-inline-block">Ajouter une filière</span></span></button>
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
                        @if (Illuminate\Support\Facades\Auth::user()->role == 'superviseur')
                            <th>Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($filieres as $filiere)
                        <tr>
                            <th>{{ $filiere->nom }}</th>
                            <th>{{ $filiere->description }}</th>
                            @if (Illuminate\Support\Facades\Auth::user()->role == 'superviseur')
                                <td class="" style="">
                                    <div class="d-flex align-items-center">
                                        <a href="javascript:void(0);"
                                            onclick="confirmDelete(event, '{{ $filiere->id }}')"
                                            class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill delete-record">
                                            <i class="ti ti-trash ti-md"></i>
                                        </a>

                                        <button wire:click="sendFiliere('{{ $filiere->id }}')"
                                            class="btn btn-sm btn-icon edit-record btn-text-secondary rounded-pill waves-effect"
                                            data-id="11" data-bs-toggle="offcanvas" data-bs-target="#offcanvasStart"
                                            aria-controls="offcanvasStart">
                                            <i class="ti ti-edit"></i>
                                        </button>

                                        <a href="javascript:;"
                                            class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ti ti-dots-vertical ti-md"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-end m-0" style="">
                                            <a href="{{ route('modules.index', ['filiere' => $filiere]) }}"
                                                class="dropdown-item">Liste des modules</a>
                                        </div>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="my-4">
                {{ $filieres->links('pagination::bootstrap-5') }}
            </div>
        </div>

        <script>
            function confirmDelete(event, classeId) {
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
                            title: 'Filière supprimée avec succès.',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        @this.call('deleteFiliere', classeId); // Appelez la méthode Livewire pour supprimer
                    }
                });
            }
        </script>
    </div>
</div>
