<div>
    <div class="card">

        <div class="card-header border-bottom">
            <h5 class="card-title mb-0">Filtres/ Filière : {{ $filiere->nom }}</h5>
            <div class="d-flex justify-content-between align-items-center row pt-4 gap-4 gap-md-0">

                <div class="col-md-4" wire:ignore>
                    <label for="selectNiveau" class="form-label">Niveau</label>
                    <select id="selectNiveau" class="select2 form-select form-select-lg" data-allow-clear="true">
                        <option value="">Selectionner le niveau</option>
                        @foreach ($niveaux as $n)
                            <option value={{ $n->id }}>{{ $n->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="nombre_heures_min">Nombre d'heures minimum</label>
                    <input wire:model.live.debounce.500ms='nombre_heures_min' class="form-control" type="number"
                        id="nombre_heures_min">
                </div>

                <div class="col-md-4">
                    <label for="nombre_heures_max">Nombre d'heures maximun</label>
                    <input wire:model.live.debounce.500ms='nombre_heures_max' class="form-control" type="number"
                        id="nombre_heures_max">
                </div>

                <div class="col-md-4 mt-5">
                    <label for="nombre_heures_utilise_min">Nombre minimun d'heures utilisé</label>
                    <input wire:model.live.debounce.500ms='nombre_heures_utilise_min' class="form-control"
                        type="number" id="nombre_heures_utilise_min">
                </div>

                <div class="col-md-4 mt-5">
                    <label for="nombre_heures_utilise_max">Nombre maximun d'heures utilisé</label>
                    <input wire:model.live.debounce.500ms='nombre_heures_utilise_max' class="form-control"
                        type="number" id="nombre_heures_utilise_max">
                </div>

                <div class="col-md-4 mt-5">
                    <label for="search">Nom du module</label>
                    <input type="search" wire:model.live.debounce.1000ms='search' id="search" class="form-control"
                        placeholder="">
                </div>

                <div class="col-md-4 user_status"></div>
            </div>
        </div>

        @if (Illuminate\Support\Facades\Auth::user()->role == 'superviseur')
            <div class="row m-5">

                <div class="col-md-12">
                    <div
                        class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-6 mb-md-0 mt-n6 mt-md-0">
                        <div class="dt-buttons btn-group flex-wrap">

                            <button class="btn btn-secondary add-new btn-primary waves-effect waves-light mb-4"
                                tabindex="0" aria-controls="DataTables_Table_0" type="button"
                                data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddUser"><span><i
                                        class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                                        class="d-none d-sm-inline-block">Ajouter un module</span></span></button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="card-datatable table-responsive">
            <table class="datatables-users table">
                <thead class="border-top">
                    <tr>
                        <th>Module</th>
                        <th>Coefficient</th>
                        <th>Nombre d'heures</th>
                        <th>heures utilisées</th>
                        <th>Niveau</th>
                        @if (Illuminate\Support\Facades\Auth::user()->role == 'superviseur')
                            <th>Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($modules as $module)
                        <tr>
                            <th>{{ $module->nom }}</th>
                            <th>{{ $module->coefficient }}</th>
                            <th>{{ $module->nombre_heures }}</th>
                            <th>{{ $module->heures_utilisees }}</th>
                            <th>{{ $module->niveau->nom }}</th>
                            @if (Illuminate\Support\Facades\Auth::user()->role == 'superviseur')
                                <td class="" style="">
                                    <div class="d-flex align-items-center">
                                        <a href="javascript:void(0);"
                                            onclick="confirmDelete(event, '{{ $module->id }}')"
                                            class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill delete-record">
                                            <i class="ti ti-trash ti-md"></i>
                                        </a>

                                        <button wire:click="sendModule('{{ $module->id }}')"
                                            class="btn btn-sm btn-icon edit-record btn-text-secondary rounded-pill waves-effect"
                                            data-id="11" data-bs-toggle="offcanvas" data-bs-target="#offcanvasStart"
                                            aria-controls="offcanvasStart">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="my-4">
                {{ $modules->links('pagination::bootstrap-5') }}
            </div>
        </div>

        <script>
            function confirmDelete(event, moduleId) {
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
                            title: 'Module supprimée avec succès.',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        @this.call('deleteModule', moduleId); // Appelez la méthode Livewire pour supprimer
                    }
                });
            }
        </script>

    </div>

    <div wire:loading.class="position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center">
        <div wire:loading class="sk-chase sk-primary">
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
        </div>
    </div>

</div>

@script
    <script>
        $(document).ready(function() {
            $('#selectNiveau').on('change', function(e) {
                var data = $('#selectNiveau').select2("val");
                @this.set('niveau', data);
            });
        });
    </script>
@endscript
