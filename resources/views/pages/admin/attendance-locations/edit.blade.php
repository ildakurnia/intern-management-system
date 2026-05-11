@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Lokasi Absensi')

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
    ['label' => 'Edit', 'current' => true],
  ],
])

<form action="{{ route('admin.attendance-locations.update', $location) }}" method="POST">
  @include('pages.admin.attendance-locations._form', ['isEdit' => true])
</form>
@endsection

@section('page-script')
@include('pages.admin.attendance-locations.partials.map-script')
@endsection
