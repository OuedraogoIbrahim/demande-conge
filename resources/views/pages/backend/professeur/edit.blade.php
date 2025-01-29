@extends('layouts/mainMaster')

@section('title', 'Liste des professeurs')

@section('breadcrumb')
    @component('common.breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
        <li class="breadcrumb-item"><a href="{{ route('professeurs.index') }}">Professeurs</a></li>
        <li class="breadcrumb-item active" aria-current="page">Modifier</li>
    @endcomponent()
@endsection

@section('vendor-style')
@endsection

@section('vendor-script')

@endsection

@section('page-script')

@endsection

@section('content')
    @livewire('professor.update', ['user' => $professeur])
@endsection
