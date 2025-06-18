@extends('layouts.app')

@section('content')
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<div class="container">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white text-center border-bottom">
            <h2 class="mb-0 text-dark fw-semibold">Bahan Baku</h2>
        </div>

        <div class="card-body">
            <!-- Pesan sukses -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Tombol aksi -->
            <div class="d-flex justify-content-between mb-3">
                <div class="d-flex gap-2">
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addIngredientModal">
                        <i class="fas fa-plus me-1"></i> Tambah Bahan Baku
                    </button>
                    <a href="{{ route('stocks.index') }}" class="btn btn-secondary">
                        <i class="fas fa-boxes-stacked me-1"></i> Lihat Riwayat Stok
                    </a>
                </div>
            </div>

            <!-- Tabel bahan baku -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th>Stok</th>
                            <th>Satuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ingredients as $ingredient)
                        <tr>
                            <td class="text-start">{{ $ingredient->name }}</td>
                            <td>{{ $ingredient->stock }}</td>
                            <td>{{ $ingredient->unit }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">Belum ada bahan baku.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Bahan Baku -->
<div class="modal fade" id="addIngredientModal" tabindex="-1" aria-labelledby="addIngredientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="addIngredientModalLabel">Tambah Bahan Baku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form action="{{ route('ingredients.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Bahan</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="unit" class="form-label">Satuan</label>
                        <input type="text" name="unit" id="unit" class="form-control" placeholder="misal: gr, butir, bungkus" required>
                    </div>

                    <div class="mb-3">
                        <label for="stock" class="form-label">Stok Awal</label>
                        <input type="number" name="stock" id="stock" class="form-control" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
