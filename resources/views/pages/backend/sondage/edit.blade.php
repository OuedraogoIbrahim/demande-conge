@extends('layouts/mainMaster')

@section('title', 'Editer sondage')

@section('breadcrumb')
    @component('common.breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
        <li class="breadcrumb-item"><a href="{{ route('sondages.index') }}">Sondages</a></li>
        <li class="breadcrumb-item"><a href="{{ route('sondages.show') }}">{{ $sondage->question }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Modifier</li>
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
    @livewire('Sondage.Update', ['sondage' => $sondage])
@endsection
