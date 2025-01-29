@extends('layouts/mainMaster')

@section('title', 'Creation Session d\'inscription')

@section('breadcrumb')
    @component('common.breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
        <li class="breadcrumb-item"><a href="{{ route('session.index') }}">Sessions</a></li>
        <li class="breadcrumb-item active" aria-current="page">Créer</li>
    @endcomponent()
@endsection

@section('vendor-style')

@endsection

@section('vendor-script')

@endsection

@section('page-script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')
    @livewire('RegistrationSession.Create')
@endsection
