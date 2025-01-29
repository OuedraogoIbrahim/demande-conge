@extends('layouts/mainMaster')

@section('title', 'Ajouter un document')

@section('breadcrumb')

    @component('common.breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
        <li class="breadcrumb-item"><a href="{{ route('documents.index') }}">Documents</a></li>
        <li class="breadcrumb-item active" aria-current="page">Créer</li>
    @endcomponent()
@endsection

@section('vendor-style')
    @vite(['resources/css/app.css'])
@endsection

@section('vendor-script')

@endsection

@section('page-script')
    @vite(['resources/js/app.js'])
@endsection

@section('content')
    @livewire('Document.Create')
@endsection
