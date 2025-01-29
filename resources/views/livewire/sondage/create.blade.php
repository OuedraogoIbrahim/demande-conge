<div>

    <div wire:ignore.self class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddUser"
        aria-labelledby="offcanvasAddUserLabel">
        <div class="offcanvas-header border-bottom">
            <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Ajouter un sondage</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body mx-0 flex-grow-0 p-6 h-100">
            <form class="add-new-user pt-0" id="addNewUserForm" wire:submit='save'>
                <div class="mb-6">
                    <label class="form-label" for="question">Question</label>
                    <input autocomplete="off" type="text" wire:model='question'
                        class="form-control @error('question') is-invalid @enderror" id="question" name="question" />
                    @error('question')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-6">
                    <label for="flatpickr-date" class="form-label">Date de fin</label>
                    <input autocomplete="off" wire:model='date_fin' type="text" class="form-control"
                        placeholder="YYYY-MM-DD" id="flatpickr-date" />
                    @error('date_fin')
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
                    <label class="form-label">Options</label>
                    @foreach ($options as $index => $option)
                        <div class="input-group mb-2">
                            <input wire:model="options.{{ $index }}.option" type="text" autocomplete="off"
                                name="options[]"
                                class="form-control form-control-sm @error('options.' . $index . '.option') is-invalid @enderror"
                                placeholder="Option {{ $index + 1 }}">

                            <button type="button" class="btn btn-danger btn-sm"
                                wire:click="removeOption({{ $index }})">
                                Supprimer
                            </button>

                            @error('options.' . $index . '.option')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    @endforeach
                    <button type="button" class="btn btn-secondary btn-sm mt-2" wire:click="addOption">
                        Ajouter une option
                    </button>
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

                <div class="mb-6">
                    <div wire:ignore>
                        <label class="form-label" for="niveau">Participants</label>
                        <select id="niveau" class="select2 form-select @error('niveau') is-invalid @enderror">
                            <option value="">Selectionner le niveau</option>
                            @foreach ($niveaux as $n)
                                <option value={{ $n->id }}>{{ $n->nom . '(' . $n->filiere->nom . ')' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('participant')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="" class="form-label">Accessible aux </label>
                    <div class="switches-stacked">
                        <label class="switch switch-square">
                            <input wire:model="accessibilite" type="radio" class="switch-input" name="accessibilite"
                                value="professeur" />
                            <span class="switch-toggle-slider">
                                <span class="switch-on"></span>
                                <span class="switch-off"></span>
                            </span>
                            <span class="switch-label">Professeurs</span>
                        </label>

                        <label class="switch switch-square">
                            <input wire:model="accessibilite" type="radio" class="switch-input" name="accessibilite"
                                value="etudiant" />
                            <span class="switch-toggle-slider">
                                <span class="switch-on"></span>
                                <span class="switch-off"></span>
                            </span>
                            <span class="switch-label">Etudiants</span>
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary me-3 data-submit" wire:loading.attr="disabled">
                    <span wire:loading.remove>Créer le sondage</span>
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
                @this.call('selectNiveau');
            });
        });

        $(document).ready(function() {
            // $('#niveau').select2();
            $('#niveau').on('change', function(e) {
                var data = $('#niveau').select2("val");
                @this.set('participant', data);
            });
        });

        $wire.on('create-event', () => {
            //
            let niveaux = @this.call('getNiveaux').then(niveaux => {

                // Vider les options actuelles
                $('#niveau').empty();

                // Ajouter l'option par défaut
                $('#niveau').append('<option value="">Sélectionnez un niveau</option>');
                $('#niveau').append('<option value="Tous">Tous les niveaux</option>');

                // Ajouter les nouvelles options dynamiquement
                niveaux.forEach(function(niveau) {

                    $('#niveau').append('<option value="' + niveau.id + '">' + niveau.nom +
                        '</option>');

                });

            });

        });
    </script>
@endscript
