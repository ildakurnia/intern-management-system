@extends('layouts/contentNavbarLayout')

@section('title', 'Tambah Grup Menu')

@section('content')
<div class="row g-6">
  <div class="col-md-6">
    <div class="card shadow-sm border-0">
      <div class="card-header border-bottom py-4">
        <h5 class="m-0 text-primary fw-bold">Tambah Grup Menu Fitur</h5>
      </div>
      <div class="card-body pt-6">
        <form action="{{ route('menus.store') }}" method="POST">
          @csrf
          <div class="form-floating form-floating-outline mb-6">
            <input type="text" class="form-control" id="title" name="title" placeholder="Nama Grup Menu" required />
            <label for="title">Nama Grup Menu</label>
          </div>
          <div class="form-floating form-floating-outline mb-6">
            <input type="text" class="form-control" id="icon" name="icon" placeholder="ri-home-line" />
            <label for="icon">Icon (Remix Icon Class)</label>
          </div>
          <div class="form-floating form-floating-outline mb-6">
            <input type="number" class="form-control" id="order" name="order" value="0" />
            <label for="order">Urutan Tampil</label>
          </div>
          <div class="form-floating form-floating-outline mb-6">
            <input type="text" class="form-control" id="route_name" name="route_name" placeholder="misal: dashboard" />
            <label for="route_name">Route Name (Opsional)</label>
          </div>
          <div class="mt-6">
            <button type="submit" class="btn btn-primary me-3">Simpan</button>
            <a href="{{ route('menus.index') }}" class="btn btn-outline-secondary">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
