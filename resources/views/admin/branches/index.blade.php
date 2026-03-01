@extends('layouts.admin')

@section('title', 'Manajemen Cabang')

@section('content')
  <div class="container-fluid">
    <div class="card shadow-sm">
      <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="mb-0 fw-bold">
            <i class="fas fa-store-alt me-2 text-primary"></i>
            Daftar Cabang
          </h5>
          <a href="{{ route('admin.branches.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Tambah Cabang
          </a>
        </div>
      </div>

      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th>Kode</th>
                <th>Nama Cabang</th>
                <th>Kota</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($branches as $branch)
                <tr>
                  <td><span class="fw-bold">{{ $branch->code }}</span></td>
                  <td>{{ $branch->name }}</td>
                  <td>{{ $branch->city ?? '-' }}</td>
                  <td>{{ $branch->phone ?? '-' }}</td>
                  <td>{{ $branch->email ?? '-' }}</td>
                  <td>
                    @if ($branch->is_active)
                      <span class="badge bg-success">Aktif</span>
                    @else
                      <span class="badge bg-danger">Nonaktif</span>
                    @endif
                  </td>
                  <td>
                    <a href="{{ route('admin.branches.edit', $branch) }}" class="btn btn-sm btn-warning">
                      <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('admin.branches.destroy', $branch) }}" method="POST" class="d-inline">
                      @csrf @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin?')">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div class="d-flex justify-content-end">
          {{ $branches->links() }}
        </div>
      </div>
    </div>
  </div>
@endsection
