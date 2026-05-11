@extends('layouts/contentNavbarLayout')

@section('title', 'Detail Profil Intern')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
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
        <i class="ri-check-line me-1"></i> Terima / Approve
      </button>
    </form>
    @endif
    <a href="{{ route('admin.interns.index') }}" class="btn btn-outline-secondary">
      <i class="ri-arrow-left-line me-1"></i> Kembali
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
              <td class="py-2"><span class="badge bg-label-info rounded-pill">{{ ucfirst($intern->status ?? 'Active') }}</span></td>
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
    <div class="card shadow-sm">
      <div class="card-header border-bottom d-flex flex-column flex-lg-row justify-content-between gap-3">
        <div>
          <h5 class="mb-1">Lokasi Absensi Intern</h5>
          <small class="text-body-secondary">Admin dapat mengaktifkan lebih dari satu lokasi agar perpindahan site tetap mudah dikelola.</small>
        </div>
        <a href="{{ route('admin.attendance-locations.index') }}" class="btn btn-outline-primary btn-sm align-self-lg-center">
          <i class="ri ri-map-pin-2-line me-1"></i> Buka Master Lokasi
        </a>
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
            <div class="table-responsive">
              <table class="table align-middle">
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
                      <td>
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
                      <td>
                        <label class="fw-medium mb-0" for="location_{{ $location->id }}">{{ $location->name }}</label>
                        <div class="small text-body-secondary">{{ $location->latitude }}, {{ $location->longitude }}</div>
                      </td>
                      <td>{{ $location->radius_meters }} meter</td>
                      <td>
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
                      <td>{{ $location->notes ?: '-' }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
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
