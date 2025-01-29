@extends('layouts/layoutMaster')

@section('title', 'Ajout Professeur - Pages')

@section('vendor-style')
    <style>
        .tags-look .tagify__dropdown__item {
            display: inline-block;
            vertical-align: middle;
            border-radius: 3px;
            padding: .3em .5em;
            border: 1px solid #CCC;
            background: #F3F3F3;
            margin: .2em;
            font-size: .85em;
            color: black;
            transition: 0s;
        }

        .tags-look .tagify__dropdown__item--active {
            border-color: black;
        }

        .tags-look .tagify__dropdown__item:hover {
            background: lightyellow;
            border-color: gold;
        }

        .tags-look .tagify__dropdown__item--hidden {
            max-width: 0;
            max-height: initial;
            padding: .3em 0;
            margin: .2em 0;
            white-space: nowrap;
            text-indent: -20px;
            border: 0;
        }
    </style>
    @vite(['resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/animate-css/animate.scss', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss', 'resources/assets/vendor/libs/tagify/tagify.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
    @vite(['resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/bloodhound/bloodhound.js', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.js', 'resources/assets/vendor/libs/tagify/tagify.js'])
@endsection

<!-- Page Scripts -->
@section('page-script')
    @vite(['resources/assets/js/forms-selects.js'])
@endsection

@section('content')
    @livewire('Professor.Create');
@endsection
