@extends('layouts/mainMaster')

@section('title', 'Ajouter etudiant')

@section('breadcrumb')
    @component('common.breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
        <li class="breadcrumb-item"><a href="{{ route('etudiants.index') }}">Etudiants</a></li>
        <li class="breadcrumb-item active" aria-current="page">Créer</li>
    @endcomponent()
@endsection

@section('vendor-style')
    @vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
@endsection

@section('vendor-script')
    @vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
@endsection

@section('page-script')
    @vite('resources/assets/js/dashboards-analytics.js')
@endsection

@section('content')
    @livewire('Student.create')
@endsection
