@extends('layouts/mainMaster')

@section('title', 'Ajouter filiere')

@section('breadcrumb')
    @component('common.breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
        <li class="breadcrumb-item"><a href="{{ route('filieres.index') }}">Filières</a></li>
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
    @livewire('Filiere.create')
@endsection
