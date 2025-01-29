<div>
    <div class="card">

        <div class="card-header border-bottom">
            <h5 class="card-title mb-0">Filtre(s)</h5>
            <div class="d-flex justify-content-between align-items-center row pt-4 gap-4 gap-md-0">

                @if (Illuminate\Support\Facades\Auth::user()->role == 'superviseur' ||
                        Illuminate\Support\Facades\Auth::user()->role == 'coordinateur')
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
                        <label for="selectNiveau" class="form-label">Participants</label>
                        <select wire:model='niveau' id="selectNiveau" class="select2 form-select form-select-lg"
                            data-allow-clear="false">
                            <option value="">Selectionner le niveau</option>
                            @foreach ($niveaux as $n)
                                <option value="{{ $n->nom }}">{{ $n->nom . '(' . $n->filiere->nom . ')' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif


                <div class="col-md-4">
                    <label>Question du sondage</label>
                    <input type="search" wire:model.live.debounce.1000ms='search' class="form-control" placeholder="">
                </div>

                <div class="col-md-4">
                    <div class="fw-medium mt-4">Uniquement ceux en cours</div>
                    <label class="switch switch-square">
                        <input wire:model.live.debounce.500ms='estExpire' type="checkbox" class="switch-input" />
                        <span class="switch-toggle-slider">
                            <span class="switch-on"><i class="ti ti-check"></i></span>
                            <span class="switch-off"><i class="ti ti-x"></i></span>
                        </span>
                    </label>
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
                                    class="d-none d-sm-inline-block">Ajouter un sondage</span></span></button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-datatable table-responsive">
            <table class="datatables-users table">
                <thead class="border-top">
                    <tr>
                        <th>Question</th>
                        <th>Accessibilité</th>
                        <th>Filière concernée</th>
                        <th>Participants</th>
                        <th>Date de fin</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sondages as $s)
                        <tr>
                            <th>{{ $s->question }}</th>
                            <th>{{ ucfirst($s->accessibilite) }}</th>
                            <th>{{ $s->filiere->nom }}</th>
                            <th>{{ $s->participants }}</th>
                            <th>
                                {{ $s->date_fin }}
                                @php
                                    $estExpire = Carbon\Carbon::parse($s->date_fin)->isPast();
                                @endphp

                                @if ($estExpire)
                                    <div class="card-header-elements">
                                        <span class="badge bg-danger rounded-pill">Expiré</span>
                                    </div>
                                @endif


                            </th>
                            <td class="" style="">
                                <div class="d-flex align-items-center">
                                    <a href="javascript:void(0);" onclick="confirmDelete(event, '{{ $s->id }}')"
                                        class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill delete-record">
                                        <i class="ti ti-trash ti-md"></i>
                                    </a>

                                    <a href="{{ route('sondages.show', $s) }}"
                                        class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill">
                                        <i class="ti ti-eye ti-md"></i>
                                    </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="my-4">
                {{ $sondages->links('pagination::bootstrap-5') }}
            </div>
        </div>

        <script>
            function confirmDelete(event, sondageId) {
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
                            title: 'Sondage supprimée avec succès.',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        @this.call('deleteSondage', sondageId); // Appelez la méthode Livewire pour supprimer
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

                    $('#selectNiveau').append('<option value="' + niveau.nom + '">' + niveau.nom +
                        '</option>');

                });

            });


        });
    </script>
@endscript
