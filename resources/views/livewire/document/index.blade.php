<div>
    <div class="card">

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
                    <label>Titre du document</label>
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
                                        class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                                        class="d-none d-sm-inline-block">Ajouter un document</span></span></button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="card-datatable table-responsive">
            <table class="datatables-users table">
                <thead class="border-top">
                    <tr>
                        <th>Titre</th>
                        <th>Filière</th>
                        <th>Niveau</th>
                        <th>Module</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($documents as $d)
                        <tr>
                            <th>{{ $d->titre }}</th>
                            <th>{{ $d->filiere->nom }}</th>
                            <th>{{ $d->niveau->nom }}</th>
                            <th>{{ $d->module->nom }}</th>
                            <td class="" style="">
                                <div class="d-flex align-items-center">
                                    @if (Illuminate\Support\Facades\Auth::user()->role == 'superviseur')
                                        <a href="javascript:void(0);"
                                            onclick="confirmDelete(event, '{{ $d->id }}')"
                                            class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill delete-record">
                                            <i class="ti ti-trash ti-md"></i>
                                        </a>

                                        <button wire:click="sendDocument('{{ $d->id }}')"
                                            class="btn btn-sm btn-icon edit-record btn-text-secondary rounded-pill waves-effect"
                                            data-id="11" data-bs-toggle="offcanvas" data-bs-target="#offcanvasStart"
                                            aria-controls="offcanvasStart">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                    @endif

                                    <a href="javascript:;"
                                        class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ti ti-dots-vertical ti-md"></i>
                                    </a>


                                    <div class="dropdown-menu dropdown-menu-end m-0" style="">
                                        <button wire:click="ViewFile('{{ $d->id }}')"
                                            class="btn btn-label-primary" data-bs-toggle="modal"
                                            data-bs-target="#file">Voir le fichier uploader</button>

                                    </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="my-4">
                {{ $documents->links('pagination::bootstrap-5') }}
            </div>
        </div>


        <div wire:ignore.self class="modal fade" id="file" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <iframe height="500" src={{ $pdfUrl }}></iframe>
                </div>
            </div>
        </div>

        <script>
            function confirmDelete(event, documentId) {
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
                            title: 'Document supprimée avec succès.',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        @this.call('deleteDocument', documentId); // Appelez la méthode Livewire pour supprimer
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
