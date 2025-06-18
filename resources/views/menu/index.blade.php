@extends('layouts.app')

@section('content')
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <div class="container">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white text-center border-bottom">
                <h2 class="mb-0 text-dark fw-semibold">Menu</h2>
            </div>

            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="d-flex justify-content-between mb-3">
                    <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createMenuModal">
                        <i class="fas fa-plus me-1"></i> Tambah Menu
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($menus as $menu)
                                <tr>
                                    <td class="text-start">{{ $menu->name }}</td>
                                    <td>{{ $menu->category->name ?? '-' }}</td>
                                    <td>Rp{{ number_format($menu->price, 0, ',', '.') }}</td>
                                    <td>
                                        <span
                                            class="badge {{ $menu->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ ucfirst($menu->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1">
                                            <!-- Tombol Detail -->
                                            <button type="button" class="btn btn-sm btn-info text-white"
                                                data-bs-toggle="modal" data-bs-target="#detailModal{{ $menu->id }}"
                                                title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            <!-- Tombol Edit -->
                                            <button type="button" class="btn btn-sm btn-warning text-white"
                                                data-bs-toggle="modal" data-bs-target="#editModal{{ $menu->id }}"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <!-- Tombol Hapus -->
                                            <form action="{{ route('menu.destroy', $menu->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus menu ini?')"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Detail Modal -->
                                <div class="modal fade" id="detailModal{{ $menu->id }}" tabindex="-1"
                                    aria-labelledby="detailModalLabel{{ $menu->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-light">
                                                <h5 class="modal-title" id="detailModalLabel{{ $menu->id }}">Detail
                                                    Menu: {{ $menu->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Tutup"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="text-center mb-3">
                                                    @if ($menu->image)
                                                        <img src="{{ asset('storage/' . $menu->image) }}"
                                                            class="img-fluid rounded" style="max-height: 200px;">
                                                    @else
                                                        <p class="text-muted"><em>Tidak ada gambar untuk menu ini.</em></p>
                                                    @endif
                                                </div>
                                                <p><strong>Kategori:</strong> {{ $menu->category->name ?? '-' }}</p>
                                                <p><strong>Harga:</strong> Rp{{ number_format($menu->price, 0, ',', '.') }}
                                                </p>
                                                <p><strong>Status:</strong> {{ ucfirst($menu->status) }}</p>
                                                <p><strong>Deskripsi:</strong> {{ $menu->description ?? '-' }}</p>

                                                <h6 class="mt-3">Bahan yang Digunakan:</h6>
                                                @if ($menu->menuIngredients->count() > 0)
                                                    <ul class="list-group">
                                                        @foreach ($menu->menuIngredients as $mi)
                                                            <li class="list-group-item">{{ $mi->ingredient->name }}
                                                                ({{ $mi->quantity }} {{ $mi->ingredient->unit }})
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <p class="text-muted">Tidak ada bahan terdaftar.</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal{{ $menu->id }}" tabindex="-1"
                                    aria-labelledby="editModalLabel{{ $menu->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-warning text-white">
                                                <h5 class="modal-title" id="editModalLabel{{ $menu->id }}">Edit Menu:
                                                    {{ $menu->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Tutup"></button>
                                            </div>
                                            <form action="{{ route('menu.update', $menu->id) }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="name{{ $menu->id }}" class="form-label">Nama
                                                            Menu</label>
                                                        <input type="text" class="form-control"
                                                            id="name{{ $menu->id }}" name="name"
                                                            value="{{ $menu->name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="price{{ $menu->id }}"
                                                            class="form-label">Harga</label>
                                                        <input type="number" class="form-control"
                                                            id="price{{ $menu->id }}" name="price"
                                                            value="{{ $menu->price }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="description{{ $menu->id }}"
                                                            class="form-label">Deskripsi</label>
                                                        <textarea class="form-control" id="description{{ $menu->id }}" name="description">{{ $menu->description }}</textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="status{{ $menu->id }}"
                                                            class="form-label">Status</label>
                                                        <select class="form-select" id="status{{ $menu->id }}"
                                                            name="status">
                                                            <option value="active"
                                                                {{ $menu->status == 'active' ? 'selected' : '' }}>Active
                                                            </option>
                                                            <option value="inactive"
                                                                {{ $menu->status == 'inactive' ? 'selected' : '' }}>
                                                                Inactive</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="image{{ $menu->id }}"
                                                            class="form-label">Gambar</label>
                                                        <input type="file" class="form-control"
                                                            id="image{{ $menu->id }}" name="image">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-warning text-white">Simpan
                                                        Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            @empty
                                <tr>
                                    <td colspan="5">Belum ada menu yang tersedia.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Tambah Menu -->
    <div class="modal fade" id="createMenuModal" tabindex="-1" aria-labelledby="createMenuModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="createMenuModalLabel">Tambah Menu</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('menu.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Menu</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi Menu</label>
                            <input type="text" name="description" id="description" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Kategori</label>
                            <select name="category_id" id="category_id" class="form-select" required>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Harga</label>
                            <input type="number" name="price" id="price" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="active">Aktif</option>
                                <option value="inactive">Tidak Aktif</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Foto Menu</label>
                            <input type="file" name="image" id="image" class="form-control" accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Bahan (Ingredients)</label>
                            <div id="ingredients-wrapper">
                                <div class="ingredient-group d-flex gap-2 mb-2">
                                    <select name="ingredients[0][ingredient_id]" class="form-select">
                                        @foreach ($allIngredients as $ing)
                                            <option value="{{ $ing->id }}">{{ $ing->name }}
                                                ({{ $ing->unit }})</option>
                                        @endforeach
                                    </select>
                                    <input type="number" name="ingredients[0][quantity]" placeholder="Jumlah"
                                        min="1" step="0.01" class="form-control" required>
                                    <button type="button" onclick="removeIngredient(this)"
                                        class="btn btn-danger">🗑</button>
                                </div>
                            </div>
                            <button type="button" onclick="addIngredient()" class="btn btn-secondary btn-sm mt-2">+
                                Tambah Bahan</button>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        let ingredientIndex = 1;

        function addIngredient() {
            const wrapper = document.getElementById('ingredients-wrapper');
            const newGroup = document.createElement('div');
            newGroup.classList.add('ingredient-group', 'd-flex', 'gap-2', 'mb-2');

            newGroup.innerHTML = `
            <select name="ingredients[${ingredientIndex}][ingredient_id]" class="form-select">
                @foreach ($allIngredients as $ing)
                    <option value="{{ $ing->id }}">{{ $ing->name }} ({{ $ing->unit }})</option>
                @endforeach
            </select>
            <input type="number" name="ingredients[${ingredientIndex}][quantity]" placeholder="Jumlah" min="1" step="0.01" class="form-control" required>
            <button type="button" onclick="removeIngredient(this)" class="btn btn-danger">🗑</button>
        `;

            wrapper.appendChild(newGroup);
            ingredientIndex++;
        }

        function removeIngredient(button) {
            button.parentElement.remove();
        }
    </script>

@endsection
