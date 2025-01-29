@extends('layouts/layoutMaster')

@section('title', 'Liste Module - Pages')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/animate-css/animate.scss', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
    @vite(['resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/bloodhound/bloodhound.js', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'])
@endsection

<!-- Page Scripts -->
@section('page-script')
    @vite(['resources/assets/js/forms-selects.js'])
@endsection

@section('content')

    @livewire('Module.Index', ['filiere' => $filiere])
    @livewire('Module.create', ['filiere' => $filiere])
    @livewire('Module.Update');

@endsection
