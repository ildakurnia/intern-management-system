@extends('layouts.app')

@section('title', 'Logbook Saya')

@section('content')
    <section class="page-intro card-surface">
        <div>
            <p class="eyebrow">Aktivitas Magang</p>
            <h2>Logbook Saya</h2>
            <p>Catatan aktivitas, pembelajaran, dan kendala selama magang.</p>
        </div>
        @can('intern.logbooks.create')
            <a href="{{ route('intern.logbooks.create') }}" class="button">+ Tambah Logbook</a>
        @endcan
    </section>

    <section class="card-surface table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Uraian Aktivitas</th>
                    <th>Pembelajaran</th>
                    <th>Kendala</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logbooks as $logbook)
                    <tr>
                        <td>{{ $logbook->tanggal->format('d M Y') }}</td>
                        <td>{{ str($logbook->uraian_aktivitas)->limit(90) }}</td>
                        <td>{{ str($logbook->pembelajaran_diperoleh)->limit(90) }}</td>
                        <td>{{ $logbook->kendala_dialami ? str($logbook->kendala_dialami)->limit(70) : '-' }}</td>
                        <td>
                            <div class="table-actions">
                            @can('intern.logbooks.show')
                                <a href="{{ route('intern.logbooks.show', $logbook) }}" class="pill pill-primary">Detail</a>
                            @endcan
                            @can('intern.logbooks.edit')
                                <a href="{{ route('intern.logbooks.edit', $logbook) }}" class="pill">Edit</a>
                            @endcan
                            @can('intern.logbooks.destroy')
                                <form action="{{ route('intern.logbooks.destroy', $logbook) }}" method="POST" onsubmit="return confirm('Hapus logbook ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="pill danger-action">Hapus</button>
                                </form>
                            @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach

                @if ($logbooks->isEmpty())
                    <tr>
                        <td colspan="5">Belum ada logbook. Tambahkan catatan aktivitas pertamamu.</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="pagination-wrap">
            {{ $logbooks->links() }}
        </div>
    </section>
@endsection
