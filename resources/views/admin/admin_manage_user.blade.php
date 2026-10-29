@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
    <div class="container h-100">

        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="mb-0 text-dark fw-bold">Daftar Akun</h2>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm d-inline-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
                </svg>
                Kembali
            </a>
        </div>

        @include('shared.alert_script')

        <div class="card shadow-sm border-0">
            <div class="card-body p-3">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="col-md-5">
                        <form action="{{ route('admin.manajemen_user') }}" method="GET">
                            <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-end-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        fill="currentColor" class="bi bi-search text-muted" viewBox="0 0 16 16">
                                        <path
                                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                                    </svg>
                                </span>
                                <input type="text" class="form-control border-start-0 ps-0" name="search"
                                    placeholder="Cari data..." value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit">Cari</button>
                            </div>
                        </form>
                    </div>

                    <div>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-plus-lg me-1" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2z" />
                            </svg>
                            Tambah Akun
                        </button>
                    </div>
                </div>

                <div class="table-responsive table-scroll-container">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="text-center fw-bold">
                                <th style="width: 50px;">No</th>
                                <th>NIDN</th>
                                <th>Nama Lengkap</th>
                                <th>Fakultas</th>
                                <th>Prodi</th>
                                <th>Username</th>
                                <th>Hak Akses</th>
                                <th style="width: 80px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @forelse ($users as $user)
                                <tr>
                                    <td>{{ $users->firstItem() + $loop->index }}</td>
                                    <td>{{ $user->nidn ?? '-' }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->fakultas ?? '-' }}</td>
                                    <td>{{ $user->prodi ?? '-' }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>
                                        @if ($user->role === 'Admin')
                                            <span class="badge bg-danger" style="font-size: 0.75rem;">Admin</span>
                                        @elseif(in_array($user->role, ['Dekan', 'Wakil Dekan 3', 'Wakil Rektor 3']))
                                            <span class="badge bg-warning text-dark"
                                                style="font-size: 0.75rem;">{{ $user->role }}</span>
                                        @else
                                            <span class="badge bg-success"
                                                style="font-size: 0.75rem;">{{ $user->role }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-success btn-sm p-1 px-2 rounded-1" title="Edit Akun"
                                            data-bs-toggle="modal" data-bs-target="#modalEditUser"
                                            onclick="fillEditModal({{ $user }})">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                <path
                                                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                <path fill-rule="evenodd"
                                                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <h6 class="fw-bold mb-0">Data tidak ditemukan</h6>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($users->total() > 0)
                    <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                        <div class="text-muted small">
                            Menampilkan <strong>{{ $users->firstItem() }}</strong> -
                            <strong>{{ $users->lastItem() }}</strong> dari <strong>{{ $users->total() }}</strong> data
                        </div>

                        <div class="d-flex align-items-center gap-3">
                            <form action="{{ route('admin.manajemen_user') }}" method="GET"
                                class="d-flex align-items-center">
                                <input type="hidden" name="search" value="{{ request('search') }}">

                                <label for="per_page" class="text-muted small me-2 text-nowrap">Tampilkan:</label>
                                <select name="per_page" id="per_page" class="form-select form-select-sm"
                                    style="width: auto;" onchange="this.form.submit()">
                                    <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10
                                    </option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                </select>
                            </form>

                            <div>
                                {{ $users->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTambahUser" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Akun Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.manajemen_user.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">NIDN</label>
                            <input type="text" class="form-control" name="nidn" placeholder="12345678"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Lengkap</label>
                            <input type="text" class="form-control" name="name" required
                                placeholder="Nama Lengkap">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Fakultas</label>
                            <select class="form-select" id="fakultas_tambah" name="fakultas"
                                onchange="updateProdi('tambah')">
                                <option value="" disabled selected>-- Pilih Fakultas --</option>
                                <option value="Teknologi Informasi">Teknologi Informasi</option>
                                <option value="Kedokteran">Kedokteran</option>
                                <option value="Kedokteran Gigi">Kedokteran Gigi</option>
                                <option value="Ekonomi dan Bisnis">Ekonomi dan Bisnis</option>
                                <option value="Hukum">Hukum</option>
                                <option value="Psikologi">Psikologi</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Prodi</label>
                            <select class="form-select" id="prodi_tambah" name="prodi">
                                <option value="" disabled selected>-- Pilih Fakultas Dulu --</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Username</label>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Password</label>
                            <input type="text" class="form-control" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Hak Akses</label>
                            <select class="form-select" name="role" required>
                                <option value="" disabled selected>-- Pilih Hak Akses --</option>
                                <option value="Dosen">Dosen</option>
                                <option value="Wakil Dekan 3">Wakil Dekan 3</option>
                                <option value="Dekan">Dekan</option>
                                <option value="Kepala Pusat 1">Kepala Pusat YARSI Peduli Penglihatan</option>
                                <option value="Kepala Pusat 2">Kepala Pusat YARSI Peduli TB</option>
                                <option value="Kepala Pusat 3">Kepala Pusat YARSI Pemberdayaan Desa</option>
                                <option value="Kepala Pusat 4">Kepala Pusat YARSI Peduli HIV/AIDS</option>
                                <option value="Kepala Pusat 5">Kepala Pusat Pelayanan Keluarga Sejahtera</option>
                                <option value="Wakil Rektor 3">Wakil Rektor 3</option>
                                <option value="Admin">Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditUser" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Edit Akun</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formEditUser" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">NIDN</label>
                            <input type="text" class="form-control" id="edit_nidn" name="nidn"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Lengkap</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Fakultas</label>
                            <select class="form-select" id="fakultas_edit" name="fakultas"
                                onchange="updateProdi('edit')">
                                <option value="Teknologi Informasi">Teknologi Informasi</option>
                                <option value="Kedokteran">Kedokteran</option>
                                <option value="Kedokteran Gigi">Kedokteran Gigi</option>
                                <option value="Ekonomi dan Bisnis">Ekonomi dan Bisnis</option>
                                <option value="Hukum">Hukum</option>
                                <option value="Psikologi">Psikologi</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Prodi</label>
                            <select class="form-select" id="prodi_edit" name="prodi">
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Username</label>
                            <input type="text" class="form-control" id="edit_username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Password Baru <small class="text-muted fw-normal">(Kosongkan
                                    jika tidak ingin ubah)</small></label>
                            <input type="text" class="form-control" name="password"
                                placeholder="Isi hanya jika ingin ganti password">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Hak Akses</label>
                            <select class="form-select" id="edit_role" name="role" required>
                                <option value="Dosen">Dosen</option>
                                <option value="Wakil Dekan 3">Wakil Dekan 3</option>
                                <option value="Dekan">Dekan</option>
                                <option value="Kepala Pusat 1">Kepala Pusat YARSI Peduli Penglihatan</option>
                                <option value="Kepala Pusat 2">Kepala Pusat YARSI Peduli TB</option>
                                <option value="Kepala Pusat 3">Kepala Pusat YARSI Pemberdayaan Desa</option>
                                <option value="Kepala Pusat 4">Kepala Pusat YARSI Peduli HIV/AIDS</option>
                                <option value="Kepala Pusat 5">Kepala Pusat Pelayanan Keluarga Sejahtera</option>
                                <option value="Wakil Rektor 3">Wakil Rektor 3</option>
                                <option value="Admin">Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const dataProdi = {
            "Teknologi Informasi": ["Teknik Informatika", "Perpustakaan dan Sains Informasi"],
            "Kedokteran": ["Kedokteran"],
            "Kedokteran Gigi": ["Kedokteran Gigi"],
            "Ekonomi dan Bisnis": ["Manajemen", "Akuntansi"],
            "Hukum": ["Ilmu Hukum"],
            "Psikologi": ["Psikologi"]
        };

        function updateProdi(mode, selectedProdi = null) {
            const idFakultas = mode === 'tambah' ? 'fakultas_tambah' : 'fakultas_edit';
            const idProdi = mode === 'tambah' ? 'prodi_tambah' : 'prodi_edit';
            const fakultasVal = document.getElementById(idFakultas).value;
            const prodiSelect = document.getElementById(idProdi);

            prodiSelect.innerHTML = '';

            if (dataProdi[fakultasVal]) {
                dataProdi[fakultasVal].forEach(function(prodi) {
                    const option = document.createElement('option');
                    option.value = prodi;
                    option.text = prodi;
                    if (selectedProdi && prodi === selectedProdi) {
                        option.selected = true;
                    }
                    prodiSelect.add(option);
                });
            } else {
                prodiSelect.innerHTML = '<option value="" disabled selected>-- Pilih Fakultas Dulu --</option>';
            }
        }

        function fillEditModal(user) {
            document.getElementById('edit_nidn').value = user.nidn || '';
            document.getElementById('edit_name').value = user.name;
            document.getElementById('edit_username').value = user.username;
            document.getElementById('edit_role').value = user.role;
            const form = document.getElementById('formEditUser');
            form.action = "{{ route('admin.manajemen_user.update', ':id') }}".replace(':id', user.id);

            if (user.fakultas) {
                document.getElementById('fakultas_edit').value = user.fakultas;
                updateProdi('edit', user.prodi);
            } else {
                if (user.prodi) {
                    document.getElementById('fakultas_edit').value = user.prodi;
                    updateProdi('edit', user.prodi);
                } else {
                    document.getElementById('prodi_edit').innerHTML =
                        '<option value="" disabled selected>-- Pilih Fakultas Dulu --</option>';
                }
            }
        }
    </script>
@endpush

@push('styles')
    <style>
        .table-scroll-container {
            max-height: 65vh;
            overflow-y: auto;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
        }

        .table-scroll-container thead th {
            position: sticky;
            top: 0;
            z-index: 2;
            background-color: #f8f9fa;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .table tbody td {
            font-size: 0.85rem;
            vertical-align: middle;
        }

        .input-group-text {
            border-right: none;
            background: white;
        }

        .input-group .form-control {
            border-left: none;
        }

        .input-group .form-control:focus {
            box-shadow: none;
            border-color: #ced4da;
        }
    </style>
@endpush
