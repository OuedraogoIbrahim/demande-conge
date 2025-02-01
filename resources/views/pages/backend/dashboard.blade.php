@extends('layouts/layoutMaster')

@section('title', 'Dashboard - CRM')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/apex-charts/apex-charts.scss'])
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/apex-charts/apexcharts.js'])
@endsection

@section('page-script')
    @vite(['resources/assets/js/dashboards-crm.js', 'resources/assets/js/app-academy-dashboard.js', 'resources/assets/vendor/libs/spinkit/spinkit.scss'])
@endsection

@section('content')
    @if (Illuminate\Support\Facades\Auth::user()->role == 'etudiant')
        @livewire('Dashboard.Etudiant')
    @endif
    @if (Illuminate\Support\Facades\Auth::user()->role == 'professeur')
        @livewire('Dashboard.Professeur')
    @endif
    @if (Illuminate\Support\Facades\Auth::user()->role == 'coordinateur')
        @livewire('Dashboard.Coordinateur')
    @endif
@endsection
