@extends('layouts.app')

@section('content')
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<div class="container">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white text-center border-bottom">
            <h2 class="mb-0 text-dark fw-semibold">Stok Bahan</h2>
        </div>

        <div class="card-body">
            <!-- Tombol Aksi -->
            <div class="d-flex justify-content-between mb-3">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addStockModal">
                    <i class="fas fa-plus me-1"></i> Tambah Stok Bahan
                </button>
                <a href="{{ route('ingredients.index') }}" class="btn btn-info text-white">
                    <i class="fas fa-list me-1"></i> Lihat Daftar Bahan Baku
                </a>
            </div>

            <!-- Pesan Sukses -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Tabel Riwayat Transaksi -->
            <h5 class="mt-4 mb-3 fw-semibold">Riwayat Transaksi Stok</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Bahan</th>
                            <th>Jumlah</th>
                            <th>Jenis</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stocks as $stock)
                            <tr>
                                <td>{{ $stock->created_at->format('d-m-Y H:i') }}</td>
                                <td>{{ $stock->ingredient->name }}</td>
                                <td>{{ $stock->quantity }} {{ $stock->ingredient->unit }}</td>
                                <td>{{ ucfirst($stock->type) }}</td>
                                <td>{{ $stock->description }}</td>
                                <td>
                                    <form action="{{ route('stocks.destroy', $stock->id) }}" method="POST" onsubmit="return confirm('Hapus histori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada transaksi stok.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Tabel Total Stok -->
            <h5 class="mt-5 mb-3 fw-semibold">Total Stok Saat Ini</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Bahan</th>
                            <th>Stok Tersedia</th>
                            <th>Satuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ingredients as $ingredient)
                            <tr>
                                <td>{{ $ingredient->name }}</td>
                                <td>{{ $ingredient->stock }}</td>
                                <td>{{ $ingredient->unit }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Stok -->
<div class="modal fade" id="addStockModal" tabindex="-1" aria-labelledby="addStockModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="addStockModalLabel">Tambah Stok Bahan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{ route('stocks.store') }}">
            @csrf

            <div class="mb-3">
                <label for="ingredientSelect" class="form-label">Bahan</label>
                <select name="ingredient_id" id="ingredientSelect" class="form-select" onchange="updateStockInfo()">
                    <option value="">-- Pilih Bahan --</option>
                    @foreach ($ingredients as $ing)
                        <option 
                            value="{{ $ing->id }}"
                            data-stock="{{ $ing->stock }}"
                            data-unit="{{ $ing->unit }}"
                        >
                            {{ $ing->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div id="stockInfo" class="mb-3" style="display: none;">
                <strong>Stok Saat Ini:</strong> <span id="stockValue"></span>
            </div>

            <div class="mb-3">
                <label for="quantity" class="form-label">Jumlah Tambahan</label>
                <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
            </div>

            <input type="hidden" name="type" value="in">

            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi (opsional)</label>
                <textarea name="description" id="description" class="form-control" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap 5 JS Script -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function updateStockInfo() {
        const select = document.getElementById('ingredientSelect');
        const selectedOption = select.options[select.selectedIndex];

        const stock = selectedOption.getAttribute('data-stock');
        const unit = selectedOption.getAttribute('data-unit');

        if (stock && unit) {
            document.getElementById('stockValue').innerText = `${stock} ${unit}`;
            document.getElementById('stockInfo').style.display = 'block';
        } else {
            document.getElementById('stockInfo').style.display = 'none';
        }
    }
</script>
@endsection
