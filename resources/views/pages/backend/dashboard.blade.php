@extends('layouts/layoutMaster')

@section('title', 'Dashboard - CRM')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/apex-charts/apex-charts.scss', 'resources/assets/vendor/libs/spinkit/spinkit.scss'])
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/apex-charts/apexcharts.js'])
@endsection

@section('page-script')
    @vite(['resources/assets/js/dashboards-crm.js', 'resources/assets/js/app-academy-dashboard.js'])
@endsection

@section('content')
    @if (Illuminate\Support\Facades\Auth::user()->role == 'employe')
        @livewire('Dashboard.Employe')
    @endif
    @if (Illuminate\Support\Facades\Auth::user()->role == 'grh')
        @livewire('Dashboard.Grh')
    @endif
    @if (Illuminate\Support\Facades\Auth::user()->role == 'responsable')
        @livewire('Dashboard.Responsable')
    @endif
@endsection
