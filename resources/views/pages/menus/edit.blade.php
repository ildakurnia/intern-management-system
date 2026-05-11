@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Grup Menu')

@section('content')
<div class="row g-6">
  <div class="col-md-6">
    <div class="card shadow-sm border-0">
      <div class="card-header border-bottom py-4">
        <h5 class="m-0 text-primary fw-bold">Edit Grup Menu Fitur</h5>
      </div>
      <div class="card-body pt-6">
        <form action="{{ route('menus.update', $menu->id) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="form-floating form-floating-outline mb-6">
            <input type="text" class="form-control" id="title" name="title" value="{{ $menu->title }}" required />
            <label for="title">Nama Grup Menu</label>
          </div>
          <div class="form-floating form-floating-outline mb-6">
            <input type="text" class="form-control" id="icon" name="icon" value="{{ $menu->icon }}" />
            <label for="icon">Icon (Remix Icon Class)</label>
          </div>
          <div class="form-floating form-floating-outline mb-6">
            <input type="number" class="form-control" id="order" name="order" value="{{ $menu->order }}" />
            <label for="order">Urutan Tampil</label>
          </div>
          <div class="form-floating form-floating-outline mb-6">
            <input type="text" class="form-control" id="route_name" name="route_name" value="{{ $menu->route_name }}" />
            <label for="route_name">Route Name (Opsional)</label>
          </div>
          <div class="mt-6">
            <button type="submit" class="btn btn-primary me-3">Update</button>
            <a href="{{ route('menus.index') }}" class="btn btn-outline-secondary">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
