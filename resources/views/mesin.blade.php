@extends('layouts.app')
@push('styles')
  <link rel="stylesheet" href="{{ asset('css/mesin.css') }}">
@endpush
@section('title', 'Mesin & Peralatan')
@section('nav', 'mesin')

@section('content')

  <div class="judul-halaman">
    <h1>Mesin &amp; Peralatan</h1>
    <p class="sub">Kelola mesin cuci, pengering, dan peralatan Laundry Abah.</p>
  </div>

  {{-- Ringkasan: angkanya dari MesinController@index --}}
  <section class="statistik" aria-label="Ringkasan mesin">
    <div class="kartu">
      <span class="stat-label">Total mesin</span>
      <strong class="stat-angka">{{ $total_mesin }}</strong>
    </div>
    <div class="kartu">
      <span class="stat-label"><i class="titik hijau"></i>Beroperasi</span>
      <strong class="stat-angka">{{ $beroperasi }}</strong>
    </div>
    <div class="kartu">
      <span class="stat-label"><i class="titik kuning"></i>Dalam perawatan</span>
      <strong class="stat-angka">{{ $perawatan }}</strong>
    </div>
    <div class="kartu">
      <span class="stat-label"><i class="titik merah"></i>Rusak</span>
      <strong class="stat-angka">{{ $rusak }}</strong>
    </div>
  </section>

  {{-- Pesan kalau isian form ada yang kurang --}}
  @if ($errors->any())
    <div class="peringatan" role="alert">
      Mesin belum tersimpan. Periksa lagi isian berikut:
      <ul>
        @foreach ($errors->all() as $pesan)
          <li>{{ $pesan }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- Form tambah mesin --}}
  <section class="kartu">
    <h2>Tambah mesin</h2>

    <form action="{{ route('admin.mesin.store') }}" method="post" class="form-grid">
      @csrf
      <div>
        <label for="kode">Kode mesin</label>
        <input type="text" id="kode" name="kode" value="{{ old('kode') }}" placeholder="Contoh: MS-009" required>
      </div>
      <div>
        <label for="nama">Nama mesin</label>
        <input type="text" id="nama" name="nama" value="{{ old('nama') }}" placeholder="Contoh: Mesin Cuci 5" required>
      </div>
      <div>
        <label for="jenis">Jenis</label>
        <select id="jenis" name="jenis" required>
          <option value="">Pilih jenis</option>
          @foreach (['Mesin Cuci', 'Pengering', 'Peralatan Lain'] as $jenis)
            <option value="{{ $jenis }}" {{ old('jenis') === $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label for="merek">Merek</label>
        <input type="text" id="merek" name="merek" value="{{ old('merek') }}" placeholder="Contoh: Electrolux" required>
      </div>
      <div>
        <label for="kapasitas">Kapasitas (kg) <span class="opsional">(boleh dikosongkan)</span></label>
        <input type="number" id="kapasitas" name="kapasitas" value="{{ old('kapasitas') }}" min="0" placeholder="Contoh: 10">
      </div>
      <div>
        <label for="status">Status operasional</label>
        <select id="status" name="status" required>
          @foreach (['Beroperasi', 'Perawatan', 'Rusak'] as $status)
            <option value="{{ $status }}" {{ old('status') === $status ? 'selected' : '' }}>{{ $status }}</option>
          @endforeach
        </select>
      </div>

      <div class="aksi-form">
        <button type="submit" class="tombol tombol-utama">Simpan mesin</button>
        <button type="reset" class="tombol tombol-garis">Kosongkan form</button>
      </div>
    </form>
  </section>

  {{-- Tabel daftar mesin --}}
  <section class="kartu">
    <div class="kepala-kartu">
      <h2>Daftar mesin</h2>
      <input type="search" id="cari" class="cari" placeholder="Cari kode, nama, atau merek" aria-label="Cari mesin">
    </div>

    <div class="tabel-bungkus">
      <table>
        <thead>
          <tr>
            <th>Kode</th>
            <th>Nama mesin</th>
            <th>Jenis</th>
            <th>Merek</th>
            <th>Kapasitas</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody id="isi-tabel">
          @forelse ($daftar_mesin as $row)
            <tr class="baris-mesin">
              <td>{{ $row->kode }}</td>
              <td>{{ $row->nama }}</td>
              <td>{{ $row->jenis }}</td>
              <td>{{ $row->merek }}</td>
              <td>{{ $row->kapasitas ? $row->kapasitas . ' kg' : '-' }}</td>
              <td><span class="lencana {{ strtolower($row->status) }}">{{ $row->status }}</span></td>
              <td class="aksi">
                <a href="{{ route('admin.mesin.edit', $row->kode) }}" class="tombol-edit">Edit</a>

                @if (Auth::guard('admin')->user()->peran === 'admin')
                  <a href="{{ route('admin.mesin.hapus', $row->kode) }}"
                      class="tombol-hapus"
                      onclick="return confirm('Yakin ingin menghapus mesin {{ $row->nama }}?')">
                    Hapus
                  </a>
                @endif
              </td>
            </tr>
          @empty
            <tr><td colspan="7" class="kosong">Belum ada mesin. Tambahkan lewat form di atas.</td></tr>
          @endforelse

          <tr id="tidak-ada" hidden>
            <td colspan="7" class="kosong">Tidak ada mesin yang cocok dengan pencarian.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>

@endsection

@push('scripts')
<script>
  // Pencarian langsung di tabel (tanpa reload): cocokkan kata kunci ke isi tiap baris.
  const kolomCari = document.getElementById('cari');
  const baris = document.querySelectorAll('.baris-mesin');
  const pesanKosong = document.getElementById('tidak-ada');

  kolomCari.addEventListener('input', () => {
    const kata = kolomCari.value.trim().toLowerCase();
    let cocok = 0;
    baris.forEach(tr => {
      const tampil = tr.textContent.toLowerCase().includes(kata);
      tr.hidden = !tampil;
      if (tampil) cocok++;
    });
    pesanKosong.hidden = !(baris.length > 0 && cocok === 0);
  });
</script>
@endpush
