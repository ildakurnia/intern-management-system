@extends('layouts/contentNavbarLayout')

@section('title', 'Tambah Lokasi Absensi')

@section('vendor-style')
  @vite(['resources/assets/vendor/libs/leaflet/leaflet.scss'])
@endsection

@section('vendor-script')
  @vite(['resources/assets/vendor/libs/leaflet/leaflet.js'])
@endsection

@section('content')
@include('partials.app-breadcrumb', [
  'items' => [
    ['label' => 'Dashboard', 'url' => route('dashboard.admin')],
    ['label' => 'Master Lokasi Absensi', 'url' => route('admin.attendance-locations.index')],
    ['label' => 'Tambah', 'current' => true],
  ],
])

<form action="{{ route('admin.attendance-locations.store') }}" method="POST">
  @include('pages.admin.attendance-locations._form', ['isEdit' => false])
</form>
@endsection

@section('page-script')
@include('pages.admin.attendance-locations.partials.map-script')
@endsection
