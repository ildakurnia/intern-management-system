@extends('layouts/contentNavbarLayout')

@section('title', 'Manajemen Divisi')

@section('vendor-style')
  @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
         'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss'])
@endsection

@section('page-style')
<style>
  /* Bulletproof Search Icon using Background Image with high specificity */
  html body .dataTables_filter input.form-control,
  html body .dataTables_filter input {
    width: 100% !important;
    margin-left: 0 !important;
    padding-left: 40px !important;
    border-radius: 8px !important;
    height: 40px !important;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23666cff'%3E%3Cpath d='M18.031 16.617l4.283 4.282-1.415 1.415-4.282-4.283A8.96 8.96 0 0 1 11 20c-4.968 0-9-4.032-9-9s4.032-9 9-9 9 4.032 9 9a8.96 8.96 0 0 1-1.969 5.617zm-2.006-.738A7.003 7.003 0 0 0 18 11c0-3.86-3.14-7-7-7s-7 3.14-7 7 3.14 7 7 7a6.967 6.967 0 0 0 4.025-1.282l.008-.007-.008-.032z'/%3E%3C/svg%3E") !important;
    background-repeat: no-repeat !important;
    background-position: 12px center !important;
    background-size: 20px !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    background-color: rgba(255, 255, 255, 0.05) !important;
    color: #fff !important;
  }

  .f-wrapper { width: 100%; max-width: 320px; }
  .dataTables_filter { width: 100%; }
  .dataTables_filter label { width: 100%; margin-bottom: 0; display: block !important; }

  /* Pagination Styling - Circular Buttons */
  .dataTables_paginate {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-top: 20px;
  }
  .dataTables_paginate .paginate_button {
    padding: 0 !important;
    margin: 0 !important;
    border: none !important;
    background: none !important;
  }
  .dataTables_paginate .paginate_button a {
    width: 38px !important;
    height: 38px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    border-radius: 50% !important;
    color: #cbd5e1 !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    text-decoration: none !important;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    font-size: 1.3rem !important;
    background-color: rgba(255, 255, 255, 0.05) !important;
    padding: 0 !important;
  }
  .dataTables_paginate .paginate_button.active a {
    background-color: #666cff !important;
    color: #fff !important;
    border-color: #666cff !important;
    box-shadow: 0 0 15px rgba(102, 108, 255, 0.4);
  }
  .dataTables_paginate .paginate_button:not(.disabled):not(.active):hover a {
    background-color: rgba(102, 108, 255, 0.15);
    color: #666cff !important;
    border-color: #666cff !important;
    transform: translateY(-2px);
  }
  .dataTables_paginate .paginate_button.disabled a {
    opacity: 0.2;
    cursor: not-allowed;
  }
  .dataTables_info {
    font-size: 0.85rem;
    color: #94a3b8;
    margin-top: 20px;
  }
</style>
@endsection

@section('vendor-script')
  @vite(['resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js'])
@endsection

@section('content')
<div class="row g-6">
  <div class="col-md-12">
    <div class="card shadow-sm border-0">
      <div class="card-header d-flex align-items-center justify-content-between border-bottom py-4">
        <div class="card-title mb-0">
          <h5 class="m-0 me-2 text-primary fw-bold">Daftar Divisi / Departemen</h5>
          <small class="text-body-secondary">Kelola struktur organisasi Persero Batam</small>
        </div>
        <div class="d-flex gap-2">
          <select id="filterStatus" class="form-select form-select-sm" style="width: 150px;">
            <option value="">Semua Status</option>
            <option value="Aktif">Aktif</option>
            <option value="Non-aktif">Non-aktif</option>
          </select>
          <a href="{{ route('admin.divisions.create') }}" class="btn btn-primary shadow">
            <i class="ri ri-add-line me-1"></i> Tambah Divisi
          </a>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive text-nowrap p-4">
          <table class="table table-hover mb-0" id="tableDivisions">
            <thead class="table-light">
              <tr>
                <th class="py-3">Kode</th>
                <th class="py-3">Nama Divisi</th>
                <th class="py-3">Total Intern</th>
                <th class="py-3">Total Mentor</th>
                <th class="py-3">Status</th>
                <th class="py-3 text-center">Aksi</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              @foreach($divisions as $div)
              <tr>
                <td><span class="fw-bold text-primary">{{ $div->code }}</span></td>
                <td>
                  <div class="d-flex flex-column">
                    <h6 class="mb-0 small fw-bold">{{ $div->name }}</h6>
                    <small class="text-body-secondary text-truncate" style="max-width: 250px;">{{ $div->description ?? '-' }}</small>
                  </div>
                </td>
                <td>
                  <span class="badge bg-label-info rounded-pill">{{ $div->interns_count }} Intern</span>
                </td>
                <td>
                  <span class="badge bg-label-primary rounded-pill">{{ $div->mentors_count }} Mentor</span>
                </td>
                <td>
                  <span class="badge bg-label-{{ $div->is_active ? 'success' : 'danger' }} rounded-pill">
                    {{ $div->is_active ? 'Aktif' : 'Non-aktif' }}
                  </span>
                </td>
                <td class="text-center">
                  <div class="d-flex align-items-center justify-content-center gap-2">
                    <a href="{{ route('admin.divisions.edit', $div->id) }}"
                      class="btn btn-sm btn-icon btn-text-secondary rounded-pill shadow-none"
                      title="Edit">
                      <i class="ri ri-pencil-line text-warning icon-22px"></i>
                    </a>
                    <form action="{{ route('admin.divisions.destroy', $div->id) }}" method="POST"
                      class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus divisi ini?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-icon btn-text-secondary rounded-pill shadow-none"
                        title="Hapus">
                        <i class="ri ri-delete-bin-line text-danger icon-22px"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const table = $('#tableDivisions').DataTable({
    dom: '<"card-header d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between pt-0 pb-4 gap-3"<"l-wrapper"l><"f-wrapper"f>>t<"card-footer d-flex align-items-center justify-content-between"ip>',
    language: {
      search: '',
      searchPlaceholder: 'Cari divisi...',
      lengthMenu: '_MENU_',
      info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
      infoEmpty: 'Menampilkan 0 sampai 0 dari 0 data',
      infoFiltered: '(disaring dari _MAX_ total data)',
      paginate: {
        next: '&rsaquo;',
        previous: '&lsaquo;',
        first: '&laquo;',
        last: '&raquo;'
      },
      emptyTable: 'Belum ada data divisi'
    },
    columnDefs: [
      { orderable: false, targets: [5] }
    ],
    order: [[1, 'asc']],
    drawCallback: function() {
      // Remove "Search:" text from label
      $('.dataTables_filter label').contents().filter(function() {
        return this.nodeType === 3;
      }).remove();
    }
  });

  // Filter Status
  $('#filterStatus').on('change', function () {
    const val = $(this).val();
    table.column(4).search(val ? '^' + val + '$' : '', true, false).draw();
  });
});
</script>
@endsection
