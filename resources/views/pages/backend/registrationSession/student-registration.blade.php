@extends('layouts/mainMaster')

@section('title', 'Session d\'inscription')

@section('vendor-style')

@endsection

@section('vendor-script')

@endsection

@section('page-script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')
    @livewire('RegistrationSession.StudentRegistration', ['registration' => $registration])
@endsection
