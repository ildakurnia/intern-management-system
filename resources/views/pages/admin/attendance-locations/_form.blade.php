@csrf
@if($isEdit)
  @method('PUT')
@endif

<div class="row g-4">
  <div class="col-lg-5">
    <div class="card h-100 shadow-sm border-0">
      <div class="card-header border-bottom">
        <h5 class="mb-0">{{ $isEdit ? 'Perbarui Detail Lokasi' : 'Tambah Detail Lokasi' }}</h5>
      </div>
      <div class="card-body">
        <div class="mb-4">
          <label for="name" class="form-label">Nama Lokasi</label>
          <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name', $location->name) }}"
            class="form-control @error('name') is-invalid @enderror"
            placeholder="Contoh: Kantor Pusat Persero" />
          @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-4">
          <button type="button" class="btn btn-outline-primary w-100" id="btnUseBrowserLocation">
            <i class="ri ri-map-pin-user-line me-1"></i> Update Lokasi Saat Ini (Browser)
          </button>
          <small class="text-body-secondary d-block mt-2">Gunakan browser untuk mengambil titik koordinat lokasi saat ini secara otomatis.</small>
        </div>

        <div class="row g-3">
          <div class="col-md-6">
            <label for="latitude" class="form-label">Latitude</label>
            <input
              type="number"
              step="0.0000001"
              id="latitude"
              name="latitude"
              value="{{ old('latitude', $location->latitude) }}"
              class="form-control @error('latitude') is-invalid @enderror" />
            @error('latitude')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-md-6">
            <label for="longitude" class="form-label">Longitude</label>
            <input
              type="number"
              step="0.0000001"
              id="longitude"
              name="longitude"
              value="{{ old('longitude', $location->longitude) }}"
              class="form-control @error('longitude') is-invalid @enderror" />
            @error('longitude')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="mt-4">
          <label for="radius_meters" class="form-label">Radius (Meter)</label>
          <input
            type="range"
            min="10"
            max="300"
            step="5"
            id="radius_meters"
            name="radius_meters"
            value="{{ old('radius_meters', $location->radius_meters ?? 100) }}"
            class="form-range" />
          <div class="d-flex justify-content-between align-items-center">
            <small class="text-body-secondary">Minimal 10 meter</small>
            <span class="badge bg-label-primary" id="radiusValueBadge">{{ old('radius_meters', $location->radius_meters ?? 100) }} m</span>
          </div>
          @error('radius_meters')
            <div class="text-danger small mt-1">{{ $message }}</div>
          @enderror
        </div>

        <div class="mt-4">
          <label for="notes" class="form-label">Keterangan</label>
          <textarea
            id="notes"
            name="notes"
            rows="4"
            class="form-control @error('notes') is-invalid @enderror"
            placeholder="Catatan singkat lokasi, misalnya akses masuk, nama site, atau area kerja intern.">{{ old('notes', $location->notes) }}</textarea>
          @error('notes')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-check form-switch mt-4">
          <input
            class="form-check-input"
            type="checkbox"
            role="switch"
            id="is_active"
            name="is_active"
            value="1"
            @checked(old('is_active', $location->is_active ?? true))>
          <label class="form-check-label" for="is_active">Lokasi aktif untuk absensi</label>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-7">
    <div class="card h-100 shadow-sm border-0">
      <div class="card-header border-bottom">
        <h5 class="mb-0">Preview Area Absensi</h5>
      </div>
      <div class="card-body">
        <div id="attendanceLocationMap" class="leaflet-map rounded-4 overflow-hidden" style="height: 470px;"></div>
        <div class="alert alert-primary mt-4 mb-0">
          <div class="fw-semibold mb-1">Tips Pengaturan</div>
          <div class="small">
            Letakkan pin di titik inti area kerja intern. Radius terlalu kecil bisa membuat absensi gagal meski intern ada di lokasi, sedangkan radius terlalu besar membuat validasi menjadi longgar.
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
  <a href="{{ route('admin.attendance-locations.index') }}" class="btn btn-outline-secondary">Batal</a>
  <button type="submit" class="btn btn-primary">
    {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Lokasi' }}
  </button>
</div>
