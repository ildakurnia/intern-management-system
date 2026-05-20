@extends('layouts/contentNavbarLayout')

@section('title', 'Detail Profil Intern')

@section('page-style')
<style>
    .intern-location-card {
        overflow: hidden;
    }

    .intern-location-header {
        padding: 1.2rem 1.25rem;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
    }

    .intern-location-title {
        margin: 0;
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--bs-heading-color);
    }

    .intern-location-subtitle {
        margin-top: 0.25rem;
        color: var(--bs-secondary-color);
        font-size: 0.9rem;
    }

    .intern-location-badges {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 0.5rem;
    }

    .intern-location-badges .badge {
        border-radius: 999px;
    }

    .intern-location-mobile-shell {
        display: grid;
        gap: 1rem;
    }

    .intern-location-mobile-card {
        padding: 1rem;
        border: 1px solid var(--bs-border-color);
        border-radius: 1rem;
        background: var(--bs-card-bg);
        box-shadow: 0 0.45rem 1.1rem rgba(15, 23, 42, 0.06);
        display: grid;
        gap: 0.9rem;
    }

    .intern-location-mobile-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 0.75rem;
    }

    .intern-location-mobile-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        margin-bottom: 0.35rem;
        color: var(--bs-secondary-color);
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        font-weight: 600;
    }

    .intern-location-mobile-icon {
        width: 1.8rem;
        height: 1.8rem;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--bs-tertiary-bg);
        color: var(--bs-primary);
        flex-shrink: 0;
    }

    .intern-location-mobile-name {
        margin: 0;
        color: var(--bs-heading-color);
        font-size: 1rem;
        font-weight: 700;
        line-height: 1.25;
    }

    .intern-location-mobile-sub {
        color: var(--bs-secondary-color);
        font-size: 0.875rem;
        word-break: break-word;
    }

    .intern-location-mobile-controls {
        display: grid;
        gap: 0.65rem;
        padding-bottom: 0.85rem;
        border-bottom: 1px solid rgba(17, 24, 39, 0.1);
    }

    .intern-location-mobile-control-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        padding: 0.8rem 0.85rem;
        border: 1px solid rgba(17, 24, 39, 0.14);
        border-radius: 0.9rem;
        background: #fff;
    }

    .intern-location-mobile-control-row .form-check {
        margin: 0;
        min-height: 1rem;
        padding-left: 1.5rem;
    }

    .intern-location-mobile-control-row .form-check-input {
        margin-top: 0.15rem;
    }

    .intern-location-mobile-control-row .form-check-label {
        color: #111827;
        font-weight: 500;
    }

    .intern-location-mobile-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.65rem;
    }

    .intern-location-mobile-pill {
        padding: 0.75rem 0.8rem;
        border-radius: 0.9rem;
        border: 1px solid rgba(17, 24, 39, 0.14);
        background: #fff;
        min-width: 0;
    }

    .intern-location-mobile-pill span,
    .intern-location-mobile-note-label {
        display: block;
        color: #111827;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        font-weight: 600;
    }

    .intern-location-mobile-pill strong {
        display: block;
        margin-top: 0.25rem;
        color: #111827;
        font-size: 0.9rem;
        font-weight: 500;
        line-height: 1.3;
        word-break: break-word;
    }

    .intern-location-mobile-note {
        padding: 0.9rem;
        border-radius: 0.9rem;
        border: 1px solid rgba(17, 24, 39, 0.14);
        background: #fff;
    }

    .intern-location-mobile-note-value {
        display: block;
        margin-top: 0.25rem;
        color: #111827;
        font-size: 0.94rem;
        font-weight: 500;
        line-height: 1.45;
        word-break: break-word;
    }

    .intern-location-mobile-actions {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.6rem;
    }

    .intern-location-mobile-actions .btn,
    .intern-location-mobile-actions form {
        width: 100%;
    }

    .intern-location-mobile-actions .btn {
        min-height: 2.75rem;
        border-radius: 0.85rem;
    }

    .intern-location-mobile-foot {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.55rem;
        padding-top: 0.1rem;
    }

    .intern-location-mobile-foot .badge {
        border-radius: 999px;
    }

    .intern-location-mobile-foot-text {
        color: #111827;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        font-weight: 600;
    }

    @media (max-width: 767.98px) {
        .intern-location-desktop-shell {
            display: none;
        }
    }

    @media (min-width: 768px) {
        .intern-location-mobile-shell {
            display: none;
        }
    }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 ims-mobile-toolbar">
  <div>
    @include('partials.app-breadcrumb', [
      'items' => [
        ['label' => 'Dashboard', 'url' => route('dashboard.admin')],
        ['label' => 'Data Intern', 'url' => route('admin.interns.index')],
        ['label' => 'Profil', 'current' => true],
      ],
    ])
  </div>
  <div class="d-flex gap-2">
    @if($intern->user_id && $intern->registration_status !== 'approved')
    <form action="{{ route('admin.interns.approve', $intern) }}" method="POST">
      @csrf
      @method('PUT')
      <button type="submit" class="btn btn-success">
        <i class="ri ri-check-line me-1"></i> Terima / Approve
      </button>
    </form>
    @endif
    <a href="{{ route('admin.interns.index') }}" class="btn btn-outline-secondary">
      <i class="ri ri-arrow-left-line me-1"></i> Kembali
    </a>
  </div>
</div>

<div class="row g-4">
  @php
    $assignedLocationIds = $intern->attendanceLocations->pluck('id')->all();
    $primaryLocationId = optional($intern->attendanceLocations->first(fn ($location) => (bool) $location->pivot->is_primary))->id;
    $oldLocationIds = collect(old('location_ids', $assignedLocationIds))->map(fn ($id) => (int) $id)->all();
  @endphp

  <!-- Data Dasar -->
  <div class="col-lg-6">
    <div class="card h-100">
      <div class="card-header border-bottom">
        <h5 class="mb-0">Data Dasar</h5>
      </div>
      <div class="card-body mt-3">
        <table class="table table-borderless table-sm mb-0">
          <tbody>
            <tr>
              <td class="text-body-secondary py-2" style="width: 35%">Nama</td>
              <td class="fw-medium text-heading py-2">{{ $intern->user->name ?? $intern->name }}</td>
            </tr>
            <tr>
              <td class="text-body-secondary py-2">Email</td>
              <td class="text-heading py-2">{{ $intern->user->email ?? $intern->email ?? '-' }}</td>
            </tr>
            <tr>
              <td class="text-body-secondary py-2">{{ $intern->type === 'mahasiswa' ? 'NIM' : 'NIS' }}</td>
              <td class="text-heading py-2">{{ $intern->nim ?? $intern->nis ?? '-' }}</td>
            </tr>
            <tr>
              <td class="text-body-secondary py-2">Tipe Peserta</td>
              <td class="text-heading py-2">{{ $intern->type ? ucfirst($intern->type) : '-' }}</td>
            </tr>
            <tr>
              <td class="text-body-secondary py-2">Divisi</td>
              <td class="text-heading py-2">{{ $intern->division->name ?? '-' }}</td>
            </tr>
            <tr>
              <td class="text-body-secondary py-2">Periode</td>
              <td class="text-heading py-2">
                {{ $intern->start_date ? \Carbon\Carbon::parse($intern->start_date)->translatedFormat('d M Y') : '-' }} - 
                {{ $intern->end_date ? \Carbon\Carbon::parse($intern->end_date)->translatedFormat('d M Y') : '-' }}
              </td>
            </tr>
            <tr>
              <td class="text-body-secondary py-2">Status Magang</td>
              <td class="py-2"><span class="badge bg-label-{{ $intern->status_badge_class }} rounded-pill">{{ $intern->status_label }}</span></td>
            </tr>
            <tr>
              <td class="text-body-secondary py-2">Akun Login</td>
              <td class="py-2"><span class="badge bg-label-success rounded-pill">{{ $intern->user_id ? 'Terhubung' : 'Belum Terhubung' }}</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Status Onboarding -->
  <div class="col-lg-6">
    <div class="card h-100">
      <div class="card-header border-bottom">
        <h5 class="mb-0">Status Onboarding</h5>
      </div>
      <div class="card-body mt-3">
        <table class="table table-borderless table-sm mb-0">
          <tbody>
            <tr>
              <td class="text-body-secondary py-3 border-bottom">Registrasi Akun</td>
              <td class="text-end py-3 border-bottom">
                <span class="badge {{ $intern->registration_status === 'approved' ? 'bg-label-success' : ($intern->user_id ? 'bg-label-warning' : 'bg-label-secondary') }} rounded-pill">
                  {{ $intern->registration_status === 'approved' ? 'Disetujui' : ($intern->user_id ? 'Terdaftar' : 'Belum Registrasi') }}
                </span>
              </td>
            </tr>
            <tr>
              <td class="text-body-secondary py-3 border-bottom">Kelengkapan Profil</td>
              <td class="text-end py-3 border-bottom"><span class="badge {{ $intern->hasCompletedProfile() ? 'bg-label-success' : 'bg-label-warning' }} rounded-pill">{{ $intern->hasCompletedProfile() ? 'Lengkap' : 'Belum Lengkap' }}</span></td>
            </tr>
            <tr>
              <td class="text-body-secondary py-3">Kelengkapan Berkas</td>
              <td class="text-end py-3"><span class="badge {{ $intern->hasCompletedDocuments() ? 'bg-label-success' : 'bg-label-warning' }} rounded-pill">{{ $intern->hasCompletedDocuments() ? 'Lengkap' : 'Belum Lengkap' }}</span></td>
            </tr>
            <tr>
              <td class="text-body-secondary py-3 border-top">Tahap Onboarding</td>
              <td class="text-end py-3 border-top">
                @if($intern->registration_status === 'approved' && $intern->hasCompletedProfile() && $intern->hasCompletedDocuments())
                  <span class="badge bg-label-success rounded-pill">Aktif</span>
                @elseif($intern->registration_status === 'approved')
                  <span class="badge bg-label-info rounded-pill">Melengkapi Data</span>
                @else
                  <span class="badge bg-label-warning rounded-pill">Register</span>
                @endif
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Biodata Lengkap -->
  <div class="col-lg-8">
    <div class="card h-100">
      <div class="card-header border-bottom">
        <h5 class="mb-0">Biodata Lengkap</h5>
      </div>
      <div class="card-body mt-3">
        <div class="row">
          <div class="col-md-6">
            <table class="table table-borderless table-sm mb-0">
              <tbody>
                <tr>
                  <td class="text-body-secondary py-2" style="width: 40%">No HP</td>
                  <td class="text-heading py-2">{{ $intern->phone ?? '-' }}</td>
                </tr>
                <tr>
                  <td class="text-body-secondary py-2">Tanggal Lahir</td>
                  <td class="text-heading py-2">{{ $intern->birth_date ? \Carbon\Carbon::parse($intern->birth_date)->translatedFormat('d M Y') : '-' }}</td>
                </tr>
                <tr>
                  <td class="text-body-secondary py-2">Jenis Kelamin</td>
                  <td class="text-heading py-2">{{ $intern->gender ? ($intern->gender == 'male' ? 'Laki-laki' : 'Perempuan') : '-' }}</td>
                </tr>
                <tr>
                  <td class="text-body-secondary py-2">Asal Institusi</td>
                  <td class="text-heading py-2">{{ $intern->institution_label }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-md-6">
            <table class="table table-borderless table-sm mb-0">
              <tbody>
                <tr>
                  <td class="text-body-secondary py-2" style="width: 40%">Jurusan</td>
                  <td class="text-heading py-2">{{ $intern->major ?? '-' }}</td>
                </tr>
                <tr>
                  <td class="text-body-secondary py-2">Fakultas</td>
                  <td class="text-heading py-2">{{ $intern->faculty ?? '-' }}</td>
                </tr>
                <tr>
                  <td class="text-body-secondary py-2">Kelas / Smt</td>
                  <td class="text-heading py-2">{{ $intern->semester ?? $intern->school_grade ?? '-' }}</td>
                </tr>
                <tr>
                  <td class="text-body-secondary py-2">IPK</td>
                  <td class="text-heading py-2">{{ $intern->gpa ?? '-' }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="border-top my-3"></div>
        <table class="table table-borderless table-sm mb-0">
          <tbody>
            <tr>
              <td class="text-body-secondary py-2" style="width: 20%">Alamat</td>
              <td class="text-heading py-2">{{ $intern->address ?? '-' }}</td>
            </tr>
            <tr>
              <td class="text-body-secondary py-2">Catatan</td>
              <td class="text-heading py-2">{{ $intern->notes ?? '-' }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Berkas Terlampir -->
  <div class="col-lg-4">
    <div class="card h-100">
      <div class="card-header border-bottom">
        <h5 class="mb-0">Berkas Terlampir</h5>
      </div>
      <div class="card-body mt-3">
        <table class="table table-borderless table-sm mb-0">
          <tbody>
            <tr>
              <td class="text-body-secondary py-3">KTP</td>
              <td class="text-end py-3">
                @if($intern->ktp_path)
                  <a href="javascript:void(0);" onclick="showDocument('{{ asset('storage/' . $intern->ktp_path) }}', 'KTP')" class="badge bg-label-success rounded-pill text-decoration-none">Lihat Berkas</a>
                @else
                  <span class="badge bg-label-secondary rounded-pill">Kosong</span>
                @endif
              </td>
            </tr>
            <tr>
              <td class="text-body-secondary py-3">KTM/Kartu Pelajar</td>
              <td class="text-end py-3">
                @if($intern->student_card_path)
                  <a href="javascript:void(0);" onclick="showDocument('{{ asset('storage/' . $intern->student_card_path) }}', 'KTM/Kartu Pelajar')" class="badge bg-label-success rounded-pill text-decoration-none">Lihat Berkas</a>
                @else
                  <span class="badge bg-label-secondary rounded-pill">Kosong</span>
                @endif
              </td>
            </tr>
            <tr>
              <td class="text-body-secondary py-3">BPJS</td>
              <td class="text-end py-3">
                @if($intern->bpjs_path)
                  <a href="javascript:void(0);" onclick="showDocument('{{ asset('storage/' . $intern->bpjs_path) }}', 'BPJS Ketenagakerjaan')" class="badge bg-label-success rounded-pill text-decoration-none">Lihat Berkas</a>
                @else
                  <span class="badge bg-label-secondary rounded-pill">Kosong</span>
                @endif
              </td>
            </tr>
            <tr>
              <td class="text-body-secondary py-3">Surat Pengantar</td>
              <td class="text-end py-3">
                @if($intern->recommendation_letter_path)
                  <a href="javascript:void(0);" onclick="showDocument('{{ asset('storage/' . $intern->recommendation_letter_path) }}', 'Surat Pengantar')" class="badge bg-label-success rounded-pill text-decoration-none">Lihat Berkas</a>
                @else
                  <span class="badge bg-label-secondary rounded-pill">Kosong</span>
                @endif
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-12">
    <div class="card shadow-sm intern-location-card">
      <div class="card-header border-bottom intern-location-header">
        <div class="min-w-0">
          <h5 class="intern-location-title mb-1">Lokasi Absensi Intern</h5>
          <div class="intern-location-subtitle">Admin dapat menghubungkan lebih dari satu lokasi agar perpindahan site tetap fleksibel.</div>
        </div>
        <div class="intern-location-badges">
          <span class="badge bg-label-primary">{{ $attendanceLocations->count() }} Lokasi</span>
          <span class="badge bg-label-success">{{ $attendanceLocations->where('is_active', true)->count() }} Aktif</span>
          <a href="{{ route('admin.attendance-locations.index') }}" class="btn btn-outline-primary btn-sm">
            <i class="ri ri-map-pin-2-line me-1"></i> Master Lokasi
          </a>
        </div>
      </div>

      <div class="card-body">
        <form action="{{ route('admin.interns.attendance-locations.update', $intern) }}" method="POST">
          @csrf
          @method('PUT')

          @if($attendanceLocations->isEmpty())
            <div class="alert alert-warning mb-0">
              Belum ada master lokasi absensi. Tambahkan lokasi dulu sebelum menghubungkannya ke intern ini.
            </div>
          @else
            <div class="intern-location-desktop-shell table-responsive ims-card-table-wrap d-none d-md-block">
              <table class="table align-middle ims-card-table">
                <thead class="table-light">
                  <tr>
                    <th>Aktif</th>
                    <th>Lokasi</th>
                    <th>Radius</th>
                    <th>Utama</th>
                    <th>Keterangan</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($attendanceLocations as $location)
                    @php
                      $isChecked = in_array($location->id, $oldLocationIds, true);
                      $selectedPrimary = (int) old('primary_location_id', $primaryLocationId);
                    @endphp
                    <tr>
                      <td data-label="Aktif">
                        <div class="form-check">
                          <input
                            class="form-check-input"
                            type="checkbox"
                            name="location_ids[]"
                            value="{{ $location->id }}"
                            id="location_{{ $location->id }}"
                            @checked($isChecked)>
                        </div>
                      </td>
                      <td data-label="Lokasi" class="ims-card-primary">
                        <label class="fw-medium mb-0" for="location_{{ $location->id }}">{{ $location->name }}</label>
                        <div class="small text-body-secondary">{{ $location->latitude }}, {{ $location->longitude }}</div>
                      </td>
                      <td data-label="Radius">{{ $location->radius_meters }} meter</td>
                      <td data-label="Utama">
                        <div class="form-check">
                          <input
                            class="form-check-input"
                            type="radio"
                            name="primary_location_id"
                            value="{{ $location->id }}"
                            id="primary_location_{{ $location->id }}"
                            @checked($selectedPrimary === $location->id)>
                          <label class="form-check-label" for="primary_location_{{ $location->id }}">Pilih utama</label>
                        </div>
                      </td>
                      <td data-label="Keterangan">{{ $location->notes ?: '-' }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

            <div class="intern-location-mobile-shell d-md-none">
              @foreach($attendanceLocations as $index => $location)
                @php
                  $isChecked = in_array($location->id, $oldLocationIds, true);
                  $selectedPrimary = (int) old('primary_location_id', $primaryLocationId);
                @endphp
                <article class="intern-location-mobile-card">
                  <div class="intern-location-mobile-head">
                    <div class="min-w-0">
                      <div class="intern-location-mobile-eyebrow">
                        <span class="intern-location-mobile-icon">
                          <i class="ri ri-map-pin-line"></i>
                        </span>
                        <span>Lokasi {{ $index + 1 }}</span>
                      </div>
                      <h6 class="intern-location-mobile-name">{{ $location->name }}</h6>
                      <div class="intern-location-mobile-sub">{{ $location->latitude }}, {{ $location->longitude }}</div>
                    </div>
                    <span class="badge bg-label-{{ $isChecked ? 'success' : 'secondary' }} rounded-pill flex-shrink-0">
                      {{ $isChecked ? 'Terhubung' : 'Tidak Aktif' }}
                    </span>
                  </div>

                  <div class="intern-location-mobile-controls">
                    <div class="intern-location-mobile-control-row">
                      <div class="form-check">
                        <input
                          class="form-check-input"
                          type="checkbox"
                          name="location_ids[]"
                          value="{{ $location->id }}"
                          id="mobile_location_{{ $location->id }}"
                          @checked($isChecked)>
                        <label class="form-check-label" for="mobile_location_{{ $location->id }}">Aktifkan lokasi</label>
                      </div>
                      <span class="badge bg-label-info rounded-pill">{{ $location->radius_meters }} m</span>
                    </div>

                    <div class="intern-location-mobile-control-row">
                      <div class="form-check">
                        <input
                          class="form-check-input"
                          type="radio"
                          name="primary_location_id"
                          value="{{ $location->id }}"
                          id="mobile_primary_location_{{ $location->id }}"
                          @checked($selectedPrimary === $location->id)>
                        <label class="form-check-label" for="mobile_primary_location_{{ $location->id }}">Jadikan utama</label>
                      </div>
                      <span class="badge bg-label-primary rounded-pill">Primary</span>
                    </div>
                  </div>

                  <div class="intern-location-mobile-grid">
                    <div class="intern-location-mobile-pill">
                      <span>Radius</span>
                      <strong>{{ $location->radius_meters }} meter</strong>
                    </div>
                    <div class="intern-location-mobile-pill">
                      <span>Status</span>
                      <strong>{{ $location->is_active ? 'Aktif' : 'Nonaktif' }}</strong>
                    </div>
                  </div>

                  <div class="intern-location-mobile-note">
                    <span class="intern-location-mobile-note-label">Keterangan</span>
                    <strong class="intern-location-mobile-note-value">{{ $location->notes ?: 'Belum ada catatan tambahan.' }}</strong>
                  </div>

                  <div class="intern-location-mobile-foot">
                    <span class="badge bg-label-info rounded-pill">{{ $location->active_interns_count }} intern aktif</span>
                    <span class="intern-location-mobile-foot-text">Siap dipakai untuk absensi hari ini</span>
                  </div>
                </article>
              @endforeach
            </div>

            @error('location_ids')
              <div class="text-danger small mt-2">{{ $message }}</div>
            @enderror

            @error('primary_location_id')
              <div class="text-danger small mt-2">{{ $message }}</div>
            @enderror

            <div class="d-flex justify-content-end mt-3">
              <button type="submit" class="btn btn-primary">
                <i class="ri ri-save-line me-1"></i> Simpan Lokasi Absensi
              </button>
            </div>
          @endif
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Document Modal -->
<div class="modal fade" id="documentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="documentModalTitle">Preview Dokumen</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center p-0" style="height: 80vh; background: #f8f9fa;">
        <iframe id="documentIframe" src="" style="width: 100%; height: 100%; border: none;"></iframe>
      </div>
    </div>
  </div>
</div>

@endsection

@section('page-script')
<script>
  function showDocument(url, title) {
    document.getElementById('documentModalTitle').innerText = 'Preview Dokumen: ' + title;
    document.getElementById('documentIframe').src = url;
    var myModal = new bootstrap.Modal(document.getElementById('documentModal'));
    myModal.show();
  }
  
  // Bersihkan iframe saat modal ditutup
  var documentModalEl = document.getElementById('documentModal');
  if(documentModalEl) {
    documentModalEl.addEventListener('hidden.bs.modal', function (event) {
      document.getElementById('documentIframe').src = '';
    });
  }
</script>
@endsection
