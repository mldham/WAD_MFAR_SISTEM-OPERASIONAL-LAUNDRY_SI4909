@extends('layouts.app')
@push('styles')
  <link rel="stylesheet" href="{{ asset('css/mesin.css') }}">
@endpush
@section('title', 'Edit mesin')
@section('nav', 'mesin')

@section('content')
  <div class="halaman-sempit">

    <div class="judul-halaman">
      <h1>Edit mesin</h1>
      <p class="sub">Mengubah data {{ $mesin->kode }}. Kode mesin tidak bisa diubah.</p>
    </div>

    @if ($errors->any())
      <div class="peringatan" role="alert">
        Perubahan belum tersimpan. Periksa lagi isian berikut:
        <ul>
          @foreach ($errors->all() as $pesan)
            <li>{{ $pesan }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <section class="kartu">
      <form action="{{ route('admin.mesin.update', $mesin->kode) }}" method="post" class="form-grid">
        @csrf
        @method('PUT')
        <div>
          <label for="kode">Kode mesin</label>
          <input type="text" id="kode" name="kode" value="{{ $mesin->kode }}" readonly>
        </div>
        <div>
          <label for="nama">Nama mesin</label>
          <input type="text" id="nama" name="nama" value="{{ old('nama', $mesin->nama) }}" required>
        </div>
        <div>
          <label for="jenis">Jenis</label>
          <select id="jenis" name="jenis" required>
            @foreach (['Mesin Cuci', 'Pengering', 'Peralatan Lain'] as $jenis)
              <option value="{{ $jenis }}" {{ old('jenis', $mesin->jenis) === $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label for="merek">Merek</label>
          <input type="text" id="merek" name="merek" value="{{ old('merek', $mesin->merek) }}" required>
        </div>
        <div>
          <label for="kapasitas">Kapasitas (kg) <span class="opsional">(boleh dikosongkan)</span></label>
          <input type="number" id="kapasitas" name="kapasitas" value="{{ old('kapasitas', $mesin->kapasitas) }}" min="0">
        </div>
        <div>
          <label for="status">Status operasional</label>
          <select id="status" name="status" required>
            @foreach (['Beroperasi', 'Perawatan', 'Rusak'] as $status)
              <option value="{{ $status }}" {{ old('status', $mesin->status) === $status ? 'selected' : '' }}>{{ $status }}</option>
            @endforeach
          </select>
        </div>

        <div class="aksi-form">
          <button type="submit" class="tombol tombol-utama">Simpan perubahan</button>
          <a href="{{ url('/') }}" class="tombol tombol-garis">Batal</a>
        </div>
      </form>
    </section>

  </div>
@endsection
