<div>
    <div class="row g-6 mb-6">
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
                                <small class="mb-0">Liste des étudiants</small>
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

    </div>

    <div class="card">

        <div class="m-5">
            @if (session()->has('message'))
                <div class="alert alert-info">
                    {{ session('message') }}
                </div>
            @endif

            @if (session()->has('failedRows'))
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach (session('failedRows') as $failedRow)
                            <li>Ligne {{ $failedRow['ligne'] }}: {{ implode(', ', $failedRow['erreurs']) }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
        </div>

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

        <div class="row m-5">

            <div class="col-md-12">
                <div
                    class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-6 mb-md-0 mt-n6 mt-md-0">

                    <div class="dt-buttons btn-group flex-wrap">
                        @if (Illuminate\Support\Facades\Auth::user()->role == 'professeur')
                            <div class="btn-group mx-4 mb-4">
                                <form wire:submit.prevent="addNote">
                                    <div class="input-group">
                                        <!-- Input file -->
                                        <input type="file" wire:model="file"
                                            class="form-control @error('file') is-invalid @enderror" id="fileInput"
                                            aria-controls="DataTables_Table_0" accept=".xlsx, .xls">

                                        <!-- Bouton de soumission -->
                                        <button type="submit"
                                            class="btn btn-secondary buttons-collection d-flex align-items-center"
                                            wire:loading.attr="disabled">
                                            <span wire:loading.class="d-none">
                                                <i class="ti ti-upload me-2 ti-xs"></i>
                                                Import des notes
                                            </span>
                                            <!-- Spinner pendant le chargement -->
                                            <div wire:loading class="spinner-border spinner-border-sm text-light"
                                                role="status"></div>
                                        </button>
                                    </div>

                                    <!-- Message d'erreur de validation -->
                                    @error('file')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </form>

                            </div>
                        @endif

                        @if (Illuminate\Support\Facades\Auth::user()->role == 'superviseur')
                            <div class="btn-group mx-4 mb-4">
                                <form wire:submit.prevent="addStudent">
                                    <div class="input-group">
                                        <!-- Input file -->
                                        <input type="file" wire:model="file"
                                            class="form-control @error('file') is-invalid @enderror" id="fileInput"
                                            aria-controls="DataTables_Table_0" accept=".xlsx, .xls">

                                        <!-- Bouton de soumission -->
                                        <button type="submit"
                                            class="btn btn-secondary buttons-collection d-flex align-items-center"
                                            wire:loading.attr="disabled">
                                            <span wire:loading.class="d-none">
                                                <i class="ti ti-upload me-2 ti-xs"></i>
                                                Import des étudiants
                                            </span>
                                            <!-- Spinner pendant le chargement -->
                                            <div wire:loading class="spinner-border spinner-border-sm text-light"
                                                role="status"></div>
                                        </button>
                                    </div>

                                    <!-- Message d'erreur de validation -->
                                    @error('file')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </form>

                            </div>
                        @endif

                        <button class="btn btn-secondary add-new btn-primary waves-effect waves-light mb-4"
                            tabindex="0" aria-controls="DataTables_Table_0" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasAddUser"><span><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                                    class="d-none d-sm-inline-block">Ajouter un étudiant</span></span></button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-datatable table-responsive">
            <table class="datatables-users table">
                <thead class="border-top">
                    <tr>
                        <th>Nom/Prénom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Filière</th>
                        <th>Niveau</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <th>{{ $user->nom . '/' . $user->prenom }}</th>
                            <th>{{ $user->email }}</th>
                            <th>{{ $user->telephone }}</th>
                            <th>{{ $user->student->first()->filiere->nom }}</th>
                            <th>{{ $user->student->first()->niveau->nom }}</th>
                            <td class="" style="">
                                <div class="d-flex align-items-center">
                                    <a href="javascript:void(0);" onclick="confirmDelete(event, '{{ $user->id }}')"
                                        class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill delete-record">
                                        <i class="ti ti-trash ti-md"></i>
                                    </a>

                                    <button wire:click="sendUser('{{ $user->id }}')"
                                        class="btn btn-sm btn-icon edit-record btn-text-secondary rounded-pill waves-effect"
                                        data-id="11" data-bs-toggle="offcanvas" data-bs-target="#offcanvasStart"
                                        aria-controls="offcanvasStart">
                                        <i class="ti ti-edit"></i>
                                    </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="my-4">
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        </div>

        {{--  --}}

        {{--  --}}

        <script>
            function confirmDelete(event, etudiantId) {
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
                            title: 'Etudiant supprimée avec succès.',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        @this.call('deleteEtudiant', etudiantId); // Appelez la méthode Livewire pour supprimer
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

                // $('#selectNiveau').select2('destroy');

                // Vider les options actuelles
                $('#selectNiveau').empty();

                // Ajouter l'option par défaut
                $('#selectNiveau').append('<option value="">Sélectionnez un niveau</option>');

                // Ajouter les nouvelles options dynamiquement
                niveaux.forEach(function(niveau) {

                    $('#selectNiveau').append('<option value="' + niveau.id + '">' + niveau.nom +
                        '</option>');

                });

                // Réinitialiser Select2
                // $('#selectNiveau').select2();


            });


        });
    </script>
@endscript
