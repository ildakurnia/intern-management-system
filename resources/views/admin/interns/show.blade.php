@extends('layouts/contentNavbarLayout')

@section('title', 'Detail Profil Intern')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="py-3 mb-0">
    <span class="text-muted fw-light">Manajemen / Data Intern /</span> Profil
  </h4>
  <div class="d-flex gap-2">
    @if($intern->registration_status !== 'approved')
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
            <tr>
              <td class="text-body-secondary py-2">Mentor Pembimbing</td>
              <td class="py-2">
                <div class="d-flex align-items-center justify-content-between">
                  <span class="fw-medium text-heading">{{ $intern->mentor->name ?? 'Belum ditentukan' }}</span>
                  @if(auth()->user()->hasAnyRole(['superadmin', 'admin']))
                  <a href="{{ route('admin.interns.edit', $intern) }}" class="btn btn-xs btn-outline-primary ms-2">
                    <i class="ri-edit-2-line"></i> Ubah
                  </a>
                  @endif
                </div>
              </td>
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
              <td class="text-end py-3 border-bottom"><span class="badge {{ $intern->registration_status === 'approved' ? 'bg-label-success' : 'bg-label-warning' }} rounded-pill">{{ ucfirst($intern->registration_status) }}</span></td>
            </tr>
            <tr>
              <td class="text-body-secondary py-3 border-bottom">Kelengkapan Profil</td>
              <td class="text-end py-3 border-bottom"><span class="badge {{ $intern->hasCompletedProfile() ? 'bg-label-success' : 'bg-label-warning' }} rounded-pill">{{ $intern->hasCompletedProfile() ? 'Lengkap' : 'Belum Lengkap' }}</span></td>
            </tr>
            <tr>
              <td class="text-body-secondary py-3">Kelengkapan Berkas</td>
              <td class="text-end py-3"><span class="badge {{ $intern->hasCompletedDocuments() ? 'bg-label-success' : 'bg-label-warning' }} rounded-pill">{{ $intern->hasCompletedDocuments() ? 'Lengkap' : 'Belum Lengkap' }}</span></td>
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
                  <td class="text-heading py-2">{{ $intern->institution ?? '-' }}</td>
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
