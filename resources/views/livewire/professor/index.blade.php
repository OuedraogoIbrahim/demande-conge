<div>
    {{-- <div class="row g-6 mb-6">
        @foreach ($studentCounts as $key => $s)
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span class="text-heading">{{ $key }}</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2">{{ $s }}</h4>
                                </div>
                                <small class="mb-0">Liste des professeurs</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-success">
                                    <i class="ti ti-user-check ti-26px"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

    </div> --}}

    <div class="card">
        @session('message')
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                </button>
            </div>
        @endsession

        <div class="card-header border-bottom">
            <h5 class="card-title mb-0">Filtres</h5>
            <div class="d-flex justify-content-between align-items-center row pt-4 gap-4 gap-md-0">
                <div class="col-md-4" wire:ignore>
                    <label for="selectFiliere" class="form-label">Filière</label>
                    <select wire:model='filiere' id="selectFiliere" class="select2 form-select form-select-lg"
                        data-allow-clear="false">
                        <option value="">Selectionner la filière</option>
                        @foreach ($filieres as $f)
                            <option value={{ $f->id }}>{{ $f->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4" wire:ignore>
                    <label for="selectNiveau" class="form-label">Niveau</label>
                    <select wire:model='niveau' id="selectNiveau" class="select2 form-select form-select-lg"
                        data-allow-clear="false">
                        <option value="">Selectionner le niveau</option>
                        @foreach ($niveaux as $n)
                            <option value={{ $n->id }}>{{ $n->nom . '(' . $n->filiere->nom . ')' }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label>Nom ou Prénom</label>
                    <input type="search" wire:model.live.debounce.1000ms='search' class="form-control" placeholder="">
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
                                        class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">Ajouter un étudiant</span></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="card-datatable table-responsive">
            <table class="datatables-users table">
                <thead class="border-top">
                    <tr>
                        <th>Nom/Prénom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Module et Filière</th>
                        {{-- <th>Niveau</th> --}}
                        @if (Illuminate\Support\Facades\Auth::user()->role == 'superviseur')
                            <th>Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($professeurs as $professeur)
                        <tr>
                            <th>
                                <p>{{ $professeur->nom . '/' . $professeur->prenom }}</p>
                                @if ($professeur->role == 'coordinateur')
                                    <span class="badge bg-info bg-glow">Coordinateur(
                                        {{ $professeur->coordinateur->first()->filiere->nom }} )</span>
                                @endif
                            </th>
                            <th>{{ $professeur->email }}</th>
                            <th>{{ $professeur->telephone }}</th>
                            <th>
                                <div class="flex flex-wrap justify-center">
                                    @foreach ($professeur->modulesToShow as $m)
                                        <div class="bg-blue-100 rounded-lg shadow-md">
                                            <p class="font-semibold"> Module : {{ $m->nom }}</p>
                                            <p class="text-sm text-gray-600">Filière :
                                                {{ $m->filiere->nom . ' ( ' . $m->niveau->nom . ' )' }}</p>
                                        </div>
                                        @if (!$loop->last)
                                            <hr class="border-red-600">
                                        @endif
                                    @endforeach

                                    @if ($professeur->has_more_modules)
                                        <div class="flex justify-center w-full mt-2">
                                            @if ($showAllModulesFor === $professeur->id)
                                                <a href="javascript:void(0);"
                                                    wire:click.prevent="showLessModules('{{ $professeur->id }}')"
                                                    class="text-blue-600 hover:text-blue-800">
                                                    Réduire
                                                </a>
                                            @else
                                                <a href="javascript:void(0);"
                                                    wire:click.prevent="showAllModules('{{ $professeur->id }}')"
                                                    class="text-blue-600 hover:text-blue-800">
                                                    Voir tout
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </th>
                            @if (Illuminate\Support\Facades\Auth::user()->role == 'superviseur')
                                <td class="" style="">
                                    <div class="d-flex align-items-center">
                                        <a href="javascript:void(0);"
                                            onclick="confirmDelete(event, '{{ $professeur->id }}')"
                                            class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill delete-record">
                                            <i class="ti ti-trash ti-md"></i>
                                        </a>

                                        <button wire:click="sendProfesseur('{{ $professeur->id }}')"
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

                                            @if ($professeur->role == 'coordinateur')
                                                <button wire:click="addAsNotCoordinateur('{{ $professeur->id }}')"
                                                    class="btn btn-label-primary">
                                                    Enlever comme coordinateur
                                                </button>
                                            @else
                                                <button wire:click="addAsCoordinateur('{{ $professeur->id }}')"
                                                    class="btn btn-label-primary">
                                                    Nommer comme coordinateur
                                                </button>
                                            @endif

                                        </div>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="my-4">
                {{ $professeurs->links('pagination::bootstrap-5') }}
            </div>
        </div>

        {{--  --}}

        {{--  --}}

        <script>
            function confirmDelete(event, professeurId) {
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
                            title: 'Professeur supprimée avec succès.',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        @this.call('deleteProfesseur', professeurId); // Appelez la méthode Livewire pour supprimer
                    }
                });
            }
        </script>
        <!-- Offcanvas to add new user -->
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
            // $('#selectFiliere').select2();
            $('#selectFiliere').on('change', function(e) {
                var data = $('#selectFiliere').select2("val");
                @this.set('filiere', data);
                @this.call('selectNiveau')

            });
        });

        $(document).ready(function() {
            // $('#selectNiveau').select2();
            $('#selectNiveau').on('change', function(e) {
                var data = $('#selectNiveau').select2("val");
                @this.set('niveau', data);

            });
        });

        $wire.on('test', () => {
            //

            let niveaux = @this.call('getNiveaux').then(niveaux => {

                // Vider les options actuelles
                $('#selectNiveau').empty();

                // Ajouter l'option par défaut
                $('#selectNiveau').append('<option value="">Sélectionnez un niveau</option>');

                // Ajouter les nouvelles options dynamiquement
                niveaux.forEach(function(niveau) {

                    $('#selectNiveau').append('<option value="' + niveau.id + '">' + niveau.nom +
                        '</option>');

                });

            });

        });
    </script>
@endscript
