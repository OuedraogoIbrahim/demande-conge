@extends('layouts/mainMaster')

@section('title', 'Editon etudiant')

@section('breadcrumb')
    @component('common.breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
        <li class="breadcrumb-item"><a href="{{ route('etudiants.index') }}">Etudiants</a></li>
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
    @livewire('Student.Update', ['user' => $user])
@endsection
