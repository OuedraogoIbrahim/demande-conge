@extends('layouts/mainMaster')

@section('title', 'Ajouter module')

@section('breadcrumb')
    @component('common.breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
        <li class="breadcrumb-item"><a href="{{ route('modules.index') }}">Modules</a></li>
        <li class="breadcrumb-item active" aria-current="page">Créer</li>
    @endcomponent()
@endsection

@section('vendor-style')
@endsection

@section('vendor-script')
    @vite('resources/js/app.js')
@endsection

@section('page-script')
@endsection

@section('content')
    @livewire('Module.create', ['filiere' => $filiere])
@endsection
