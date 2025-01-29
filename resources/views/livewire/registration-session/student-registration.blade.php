<div class="row">

    <div class="col-xxl">
        <div class="card mb-6">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Session d'inscription </h5>
            </div>
            <div class="card-body">
                <form method="POST" wire:submit='submit'>
                    @csrf
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label" for="basic-nom">Nom</label>
                            <input wire:model='nom' type="text"
                                class="form-control @error('nom') is-invalid @enderror" id="basic-nom" name="nom" />
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="basic-prenom">Prénom</label>
                            <input wire:model='prenom' type="text"
                                class="form-control @error('prenom') is-invalid @enderror" id="basic-prenom"
                                name="prenom" />
                            @error('prenom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label" for="basic-email">Email</label>
                            <input wire:model='email' type="email"
                                class="form-control @error('email') is-invalid @enderror" id="basic-email"
                                name="email" />
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="basic-telephone">Téléphone</label>
                            <input wire:model='telephone' type="text"
                                class="form-control @error('telephone') is-invalid @enderror" id="basic-telephone"
                                name="telephone" />
                            @error('telephone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row justify-content-end">
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <!-- Affiche "Créer" quand le formulaire n'est pas en soumission -->
                                <span wire:loading.remove>Créer</span>
                                <!-- Affiche un loader pendant la soumission -->
                                <div wire:loading>
                                    <span class="spinner-border" text-primary role="status" aria-hidden="true"></span>
                                </div>
                            </button>
                        </div>
                    </div>

                    <div wire:loading class="position-fixed top-50 start-50 translate-middle">
                        <div class="spinner-border spinner-border-lg text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
