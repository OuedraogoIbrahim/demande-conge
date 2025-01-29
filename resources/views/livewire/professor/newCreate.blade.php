<div class="row">

    @if (isset($niveaux) && $niveaux->isEmpty())
        <div class="alert alert-warning d-flex align-items-center justify-content-between" role="alert">
            <div>
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                Aucun niveau pour cette filiere
            </div>
            <a href="{{ route('niveaux.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Créer un niveau
            </a>
        </div>
    @endif

    @if (isset($modules) && $modules->isEmpty())
        <div class="alert alert-warning d-flex align-items-center justify-content-between" role="alert">
            <div>
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                Aucun module pour ce niveau
            </div>
            <a href="{{ route('modules.create', ['filiere' => $filiere]) }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Créer un module
            </a>
        </div>
    @endif

    {{-- @endif --}}


    <div class="col-xxl">
        <div class="card mb-6">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Ajouter un professeur</h5>
                <small class="text-muted float-end"></small>
            </div>
            <div class="card-body">
                <form method="POST" wire:submit='submit'>
                    @csrf
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label" for="basic-nom">Nom</label>
                            <input wire:model='nom' type="text"
                                class="form-control @error('nom') is-invalid @enderror" id="basic-nom" name="nom"
                                value="{{ old('nom') }}" />
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="basic-prenom">Prénom</label>
                            <input wire:model='prenom' type="text"
                                class="form-control @error('prenom') is-invalid @enderror" id="basic-prenom"
                                name="prenom" value="{{ old('prenom') }}" />
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
                                name="email" value="{{ old('email') }}" />
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="basic-telephone">Téléphone</label>
                            <input wire:model='telephone' type="text"
                                class="form-control @error('telephone') is-invalid @enderror" id="basic-telephone"
                                name="telephone" value="{{ old('telephone') }}" />
                            @error('telephone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label" for="basic-filieres">Filière(s)</label>
                            <select wire:model.live="filiere" multiple
                                class="form-control @error('filiere') is-invalid @enderror" id="basic-filieres"
                                name="filiere" wire:change="resetSelection('filiere')">
                                <option value="">Sélectionner la/les filière(s)</option>
                                @foreach ($filieres as $f)
                                    <option value="{{ $f->id }}">{{ $f->nom }}</option>
                                @endforeach
                            </select>
                            @error('filiere')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if ($filiere)
                            <div class="col-md-6">
                                @foreach ($niveaux as $filiereId => $niveaux)
                                    <label class="form-label mt-3" for="niveau-{{ $filiereId }}">
                                        Niveaux pour la filière : {{ $filieres->firstWhere('id', $filiereId)->nom }}
                                    </label>
                                    <select wire:model.live="niveau.{{ $filiereId }}" class="form-control" multiple
                                        id="niveau-{{ $filiereId }}" wire:change="resetSelection('niveau')">
                                        <option value="">Sélectionner un niveau</option>
                                        @foreach ($niveaux as $niveau)
                                            <option value="{{ $niveau->id }}">{{ $niveau->nom }}</option>
                                        @endforeach
                                    </select>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    @if (!empty($modules))
                        <div class="row mt-4">
                            @foreach ($modules as $niveauId => $modules)
                                <div class="col-md-6">
                                    <label class="form-label" for="module-{{ $niveauId }}">
                                        Modules pour le niveau : {{ $niveaux->firstWhere('id', $niveauId)->nom }}
                                    </label>
                                    <select wire:model="module.{{ $niveauId }}" multiple
                                        class="form-control @error('modules') is-invalid @enderror"
                                        id="module-{{ $niveauId }}">
                                        <option value="">Sélectionner un module</option>
                                        @foreach ($modules as $module)
                                            <option value="{{ $module->id }}">{{ $module->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- <div class="col-md-6 mb-6">
                        <label for="TagifyCustomInlineSuggestion" class="form-label">Custom Inline Suggestions</label>
                        <input id="TagifyCustomInlineSuggestion" name="TagifyCustomInlineSuggestion"
                            class="form-control" placeholder="select technologies" value="css, html, javascript">
                    </div> --}}

                    <div class="col-md-6 mb-6">
                        <label for="test" class="form-label">Test custom</label>
                        <input wire:model='test' id="test" name="test" class="form-control tagify--custom-dropdown"
                            placeholder="select technologies">
                    </div>


                    <button wire:click='cl' class="btn btn-primary">Voir les resultats</button>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label d-block" for="coordinateur">Mettre comme coordinateur ?</label>

                            <!-- Boutons radio pour Oui / Non -->
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="coordinateur"
                                    wire:model="coordinateur" id="coordinateur-oui" value="oui">
                                <label class="form-check-label" for="coordinateur-oui">Oui</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="coordinateur"
                                    wire:model="coordinateur" id="coordinateur-non" value="non">
                                <label class="form-check-label" for="coordinateur-non">Non</label>
                            </div>

                            @error('coordinateur')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
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
                                    <span class="spinner-border" text-primary role="status"
                                        aria-hidden="true"></span>
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


@script
    <script>
        const input = document.getElementById('test'),
            // init Tagify script on the above inputs
            tagify = new Tagify(input, {
                whitelist: ["A# .NET", "A# (Axiom)", "A-0 System", "A+", "A++", "ABAP", "ABC", "ABC ALGOL", "ABSET",
                    "ABSYS", "ACC", "Accent", "Ace DASL", "ACL2", "Avicsoft", "ACT-III", "Action!", "ActionScript",
                    "Ada", "Adenine", "Agda", "Agilent VEE", "Agora", "AIMMS", "Alef", "ALF", "ALGOL 58",
                    "ALGOL 60", "ALGOL 68", "ALGOL W", "Alice", "Alma-0", "AmbientTalk", "Amiga E", "AMOS", "AMPL",
                    "Apex (Salesforce.com)", "APL", "AppleScript", "Arc", "ARexx", "Argus", "AspectJ",
                    "Assembly language", "ATS", "Ateji PX", "AutoHotkey", "Autocoder", "AutoIt",
                    "AutoLISP / Visual LISP", "Averest", "AWK", "Axum", "Active Server Pages", "ASP.NET", "B",
                    "Babbage", "Bash", "BASIC", "bc", "BCPL", "BeanShell", "Batch (Windows/Dos)", "Bertrand",
                    "BETA", "Bigwig", "Bistro", "BitC", "BLISS", "Blockly", "BlooP", "Blue", "Boo", "Boomerang",
                    "Bourne shell (including bash and ksh)", "BREW", "BPEL", "B", "C--", "C++ – ISO/IEC 14882",
                    "C# – ISO/IEC 23270", "C/AL", "Caché ObjectScript", "C Shell", "Caml", "Cayenne", "CDuce",
                    "Cecil", "Cesil", "Céu", "Ceylon", "CFEngine", "CFML", "Cg", "Ch", "Chapel", "Charity", "Charm",
                    "Chef", "CHILL", "CHIP-8", "chomski", "ChucK", "CICS", "Cilk", "Citrine (programming language)",
                    "CL (IBM)", "Claire", "Clarion", "Clean", "Clipper", "CLIPS", "CLIST", "Clojure", "CLU",
                    "CMS-2", "COBOL – ISO/IEC 1989", "CobolScript – COBOL Scripting language", "Cobra", "CODE",
                    "CoffeeScript", "ColdFusion", "COMAL", "Combined Programming Language (CPL)", "COMIT",
                    "Common Intermediate Language (CIL)", "Common Lisp (also known as CL)", "COMPASS",
                    "Component Pascal", "Constraint Handling Rules (CHR)", "COMTRAN", "Converge", "Cool", "Coq",
                    "Coral 66", "Corn", "CorVision", "COWSEL", "CPL", "CPL", "Cryptol", "csh", "Csound", "CSP",
                    "CUDA", "Curl", "Curry", "Cybil", "Cyclone", "Cython", "Java", "Javascript", "M2001", "M4",
                    "M#", "Machine code", "MAD (Michigan Algorithm Decoder)", "MAD/I", "Magik", "Magma", "make",
                    "Maple", "MAPPER now part of BIS", "MARK-IV now VISION:BUILDER", "Mary",
                    "MASM Microsoft Assembly x86", "MATH-MATIC", "Mathematica", "MATLAB",
                    "Maxima (see also Macsyma)", "Max (Max Msp – Graphical Programming Environment)", "Maya (MEL)",
                    "MDL", "Mercury", "Mesa", "Metafont", "Microcode", "MicroScript", "MIIS",
                    "Milk (programming language)", "MIMIC", "Mirah", "Miranda", "MIVA Script", "ML", "Model 204",
                    "Modelica", "Modula", "Modula-2", "Modula-3", "Mohol", "MOO", "Mortran", "Mouse", "MPD",
                    "Mathcad", "MSIL – deprecated name for CIL", "MSL", "MUMPS", "Mystic Programming L"
                ],
                maxTags: 10,
                dropdown: {
                    maxItems: 20, // <- mixumum allowed rendered suggestions
                    classname: 'tags-look', // <- custom classname for this dropdown, so it could be targeted
                    enabled: 0, // <- show suggestions on focus
                    closeOnSelect: false // <- do not hide the suggestions dropdown once an item has been selected
                }
            })
    </script>
@endscript
