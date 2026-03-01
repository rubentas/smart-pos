@extends('layouts.admin')

@section('title', 'Tambah Cabang')

@section('content')
  <div class="container-fluid">
    <div class="card shadow-sm">
      <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold">Tambah Cabang Baru</h5>
      </div>

      <div class="card-body">
        <form action="{{ route('admin.branches.store') }}" method="POST">
          @csrf

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Kode Cabang <span class="text-danger">*</span></label>
              <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                value="{{ old('code') }}" required maxlength="20">
              @error('code')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label">Nama Cabang <span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name') }}" required>
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label">Kota</label>
              <input type="text" name="city" class="form-control" value="{{ old('city') }}">
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label">Phone</label>
              <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" value="{{ old('email') }}">
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label">Alamat</label>
              <textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea>
            </div>

            <div class="col-md-12 mb-3">
              <div class="form-check">
                <input type="checkbox" name="is_active" class="form-check-input" value="1" checked>
                <label class="form-check-label">Aktif</label>
              </div>
            </div>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save me-1"></i> Simpan
            </button>
            <a href="{{ route('admin.branches.index') }}" class="btn btn-secondary">
              <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
