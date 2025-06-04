<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Halaman Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="{{ asset('css/adminPage.css') }}">
</head>
<body>
<div class="container py-4 admin-section">
    <h1 class="mb-4 text-center">Dashboard Admin</h1>

    <!-- Section: Aturan Aplikasi -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Aturan Aplikasi</strong>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addRuleModal">
                <i class="fas fa-plus"></i> Tambah Aturan
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px">No</th>
                            <th style="width: 200px">Nama</th>
                            <th>Aturan</th>
                            <th style="width: 150px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data akan diisi oleh JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Section: Request Verifikasi KTP -->
    <div class="card mb-4">
        <div class="card-header">
            <strong>Request Verifikasi KTP</strong>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Nomor KTP</th>
                        <th>Foto KTP</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Contoh data request verifikasi KTP, ganti dengan data dinamis dari backend -->
                    <tr>
                        <td>1</td>
                        <td>Budi</td>
                        <td>1234567890123456</td>
                        <td>
                            <img src="https://via.placeholder.com/120x80?text=KTP+Budi" alt="KTP Budi" style="max-width:120px;max-height:80px;cursor:pointer;" onclick="showKtpImage('https://via.placeholder.com/400x250?text=KTP+Budi')">
                        </td>
                        <td><span class="badge bg-warning text-dark">Menunggu</span></td>
                        <td>
                            <button class="btn btn-success btn-sm" onclick="verifikasiKTPUser(1, 'Budi', '1234567890123456', 'https://via.placeholder.com/400x250?text=KTP+Budi')">Verifikasi</button>
                            <button class="btn btn-danger btn-sm" onclick="tolakKTPUser(1)">Tolak</button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Siti</td>
                        <td>9876543210987654</td>
                        <td>
                            <img src="https://via.placeholder.com/120x80?text=KTP+Siti" alt="KTP Siti" style="max-width:120px;max-height:80px;cursor:pointer;" onclick="showKtpImage('https://via.placeholder.com/400x250?text=KTP+Siti')">
                        </td>
                        <td><span class="badge bg-warning text-dark">Menunggu</span></td>
                        <td>
                            <button class="btn btn-success btn-sm" onclick="verifikasiKTPUser(2, 'Siti', '9876543210987654', 'https://via.placeholder.com/400x250?text=KTP+Siti')">Verifikasi</button>
                            <button class="btn btn-danger btn-sm" onclick="tolakKTPUser(2)">Tolak</button>
                        </td>
                    </tr>
                    <!-- Tambahkan baris request lain di sini -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Section: Daftar Users -->
    <div class="card mb-4">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
            <strong>Daftar Users</strong>
            <form class="d-flex align-items-center gap-2" id="searchUserForm" onsubmit="return false;">
                <input type="text" class="form-control form-control-sm" id="searchUserInput" placeholder="Cari ID/Nama/Email" style="width: 180px;">
                <button class="btn btn-primary btn-sm" onclick="searchUser()">Cari</button>
            </form>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle mb-0" id="userTable">
                    <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="userTableBody">
                    <!-- Data akan diisi oleh JavaScript -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Section: Daftar Transaksi -->
    <div class="card mb-4">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
            <strong>Daftar Transaksi</strong>
            <form class="d-flex align-items-center gap-2" id="searchTransForm" onsubmit="return false;">
                <input type="text" class="form-control form-control-sm" id="searchTransInput" placeholder="Cari ID Transaksi/User" style="width: 180px;">
                <button class="btn btn-primary btn-sm" onclick="searchTransaksi()">Cari</button>
            </form>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-striped align-middle mb-0" id="transTable">
                <thead class="table-light">
                    <tr>
                        <th>ID Transaksi</th>
                        <th>User</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody id="transTableBody">
                    <!-- Contoh data transaksi, ganti dengan data dinamis dari backend -->
                    <tr>
                        <td>TRX001</td>
                        <td>Budi</td>
                        <td>Rp 100.000</td>
                        <td><span class="badge bg-success">Sukses</span></td>
                        <td>2024-06-01</td>
                    </tr>
                    <tr>
                        <td>TRX002</td>
                        <td>Siti</td>
                        <td>Rp 50.000</td>
                        <td><span class="badge bg-warning text-dark">Pending</span></td>
                        <td>2024-06-02</td>
                    </tr>
                    <!-- Tambahkan baris transaksi lain di sini -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Verifikasi KTP User -->
<div class="modal fade" id="modalVerifikasiKTPUser" tabindex="-1" aria-labelledby="modalVerifikasiKTPUserLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalVerifikasiKTPUserLabel">Verifikasi KTP User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="ktp_user_id" name="ktp_user_id">
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="ktp_user_nama" name="ktp_user_nama" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Nomor KTP</label>
                <input type="text" class="form-control" id="ktp_user_nomor" name="ktp_user_nomor" readonly>
            </div>
            <div class="mb-3 text-center">
                <label class="form-label">Foto KTP</label>
                <div>
                    <img id="ktp_user_foto" src="" alt="Foto KTP" style="max-width:100%;max-height:300px;border:1px solid #ddd;">
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="button" class="btn btn-success" id="btnVerifKTP">Verifikasi</button>
          <button type="button" class="btn btn-danger" id="btnTolakKTP">Tolak</button>
        </div>
    </div>
  </div>
</div>

<!-- Modal Lihat Gambar KTP -->
<div class="modal fade" id="modalLihatKTP" tabindex="-1" aria-labelledby="modalLihatKTPLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalLihatKTPLabel">Foto KTP</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body text-center">
            <img id="imgLihatKTP" src="" alt="Foto KTP" style="max-width:100%;max-height:500px;">
        </div>
    </div>
  </div>
</div>

<!-- Modal Tambah Aturan -->
<div class="modal fade" id="addRuleModal" tabindex="-1" aria-labelledby="addRuleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRuleModalLabel">Tambah Aturan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <form id="addRuleForm">
                    <div class="mb-3">
                        <label for="ruleName" class="form-label">Nama Aturan</label>
                        <input type="text" class="form-control" id="ruleName" name="ruleName" required placeholder="Contoh: Aturan Umur">
                    </div>
                    <div class="mb-3">
                        <label for="ruleContent" class="form-label">Isi Aturan</label>
                        <textarea class="form-control" id="ruleContent" name="ruleContent" rows="3" required placeholder="Masukkan isi aturan baru..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="addRule()">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Aturan -->
<div class="modal fade" id="editRuleModal" tabindex="-1" aria-labelledby="editRuleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRuleModalLabel">Edit Aturan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <form id="editRuleForm">
                    <input type="hidden" id="editRuleId">
                    <div class="mb-3">
                        <label for="editRuleName" class="form-label">Nama Aturan</label>
                        <input type="text" class="form-control" id="editRuleName" name="editRuleName" required>
                    </div>
                    <div class="mb-3">
                        <label for="editRuleContent" class="form-label">Isi Aturan</label>
                        <textarea class="form-control" id="editRuleContent" name="editRuleContent" rows="3" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="updateRule()">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Fungsi untuk fetch data users
    async function fetchUsers() {
        try {
            const response = await fetch('{{ route("users") }}');
            const result = await response.json();
            
            if (result.success) {
                const userTableBody = document.getElementById('userTableBody');
                userTableBody.innerHTML = ''; // Clear existing rows
                
                result.data.forEach(user => {
                    const isBanned = user.status && user.status.is_banned;
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${user.id}</td>
                        <td>${user.username}</td>
                        <td>${user.email}</td>
                        <td><span class="badge ${isBanned ? 'bg-danger' : 'bg-success'}">${isBanned ? 'Banned' : 'Aktif'}</span></td>
                        <td class="d-flex gap-1 flex-wrap">
                            ${isBanned ? 
                                `<button class="btn btn-secondary btn-sm" disabled>Banned</button>` :
                                `<button class="btn btn-danger btn-sm" onclick="bannedUser(${user.id})">Banned</button>`
                            }
                        </td>
                    `;
                    userTableBody.appendChild(row);
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Gagal mengambil data users'
                });
            }
        } catch (error) {
            console.error('Error fetching users:', error);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Terjadi kesalahan saat mengambil data users'
            });
        }
    }

    async function searchUser() {
        const searchInput = document.getElementById('searchUserInput');
        const searchQuery = searchInput.value.trim();
        
        try {
            const response = await fetch(`{{ route('users.search') }}?search=${encodeURIComponent(searchQuery)}`);
            const result = await response.json();
            
            if (result.success) {
                const userTableBody = document.getElementById('userTableBody');
                userTableBody.innerHTML = ''; // Clear existing rows
                
                if (result.data.length === 0) {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td colspan="5" class="text-center">Tidak ada data yang ditemukan</td>
                    `;
                    userTableBody.appendChild(row);
                    return;
                }
                
                result.data.forEach(user => {
                    const isBanned = user.status && user.status.is_banned;
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${user.id}</td>
                        <td>${user.username}</td>
                        <td>${user.email}</td>
                        <td><span class="badge ${isBanned ? 'bg-danger' : 'bg-success'}">${isBanned ? 'Banned' : 'Aktif'}</span></td>
                        <td class="d-flex gap-1 flex-wrap">
                            ${isBanned ? 
                                `<button class="btn btn-secondary btn-sm" disabled>Banned</button>` :
                                `<button class="btn btn-danger btn-sm" onclick="bannedUser(${user.id})">Banned</button>`
                            }
                        </td>
                    `;
                    userTableBody.appendChild(row);
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Gagal mencari users'
                });
            }
        } catch (error) {
            console.error('Error searching users:', error);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Terjadi kesalahan saat mencari users'
            });
        }
    }

    // Tambahkan event listener untuk tombol Enter pada input pencarian
    document.getElementById('searchUserInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchUser();
        }
    });

    // Panggil fetchUsers saat halaman dimuat
    document.addEventListener('DOMContentLoaded', fetchUsers);

    function bannedUser(userId) {
        Swal.fire({
            title: 'Konfirmasi',
            text: "Yakin ingin banned user ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, banned user!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`{{ url('users/ban') }}/${userId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: result.message
                        });
                        fetchUsers(); // Refresh data users
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: result.message
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan saat membanned user'
                    });
                });
            }
        });
    }

    // Fungsi untuk mengambil data aturan
    async function fetchRules() {
        try {
            const response = await fetch('{{ route("terms") }}');
            const result = await response.json();
            
            if (result.success) {
                const rulesTableBody = document.querySelector('table tbody');
                rulesTableBody.innerHTML = '';
                
                result.data.forEach((rule, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${rule.name}</td>
                        <td>${rule.message}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editRuleModal" onclick="showEditRuleModal(${rule.id}, '${rule.name}', '${rule.message}')">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="confirmDeleteRule(${rule.id})">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </td>
                    `;
                    rulesTableBody.appendChild(row);
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Gagal mengambil data aturan'
                });
            }
        } catch (error) {
            console.error('Error fetching rules:', error);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Terjadi kesalahan saat mengambil data aturan'
            });
        }
    }

    // Fungsi untuk menampilkan modal edit aturan
    function showEditRuleModal(id, name, content) {
        document.getElementById('editRuleId').value = id;
        document.getElementById('editRuleName').value = name;
        document.getElementById('editRuleContent').value = content;
    }

    // Panggil fetchRules saat halaman dimuat
    document.addEventListener('DOMContentLoaded', fetchRules);

    // Fungsi untuk menambah aturan baru
    async function addRule() {
        const name = document.getElementById('ruleName').value;
        const message = document.getElementById('ruleContent').value;

        try {
            const response = await fetch('{{ route("terms.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    name: name,
                    message: message
                })
            });

            const result = await response.json();

            if (result.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Aturan berhasil ditambahkan'
                });
                // Tutup modal dan refresh data
                const modal = bootstrap.Modal.getInstance(document.getElementById('addRuleModal'));
                modal.hide();
                document.getElementById('addRuleForm').reset();
                fetchRules();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: result.message || 'Gagal menambahkan aturan'
                });
            }
        } catch (error) {
            console.error('Error adding rule:', error);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Terjadi kesalahan saat menambahkan aturan'
            });
        }
    }

    // Fungsi untuk mengupdate aturan
    async function updateRule() {
        const id = document.getElementById('editRuleId').value;
        const name = document.getElementById('editRuleName').value;
        const message = document.getElementById('editRuleContent').value;

        try {
            const response = await fetch(`{{ url('terms') }}/${id}/edit`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    name: name,
                    message: message
                })
            });

            const result = await response.json();

            if (result.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Aturan berhasil diperbarui'
                });
                // Tutup modal dan refresh data
                const modal = bootstrap.Modal.getInstance(document.getElementById('editRuleModal'));
                modal.hide();
                fetchRules();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: result.message || 'Gagal memperbarui aturan'
                });
            }
        } catch (error) {
            console.error('Error updating rule:', error);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Terjadi kesalahan saat memperbarui aturan'
            });
        }
    }

    // Fungsi untuk menghapus aturan
    async function deleteRule(id) {
        try {
            const response = await fetch(`{{ url('terms') }}/${id}/remove`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const result = await response.json();

            if (result.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Aturan berhasil dihapus'
                });
                fetchRules();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: result.message || 'Gagal menghapus aturan'
                });
            }
        } catch (error) {
            console.error('Error deleting rule:', error);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Terjadi kesalahan saat menghapus aturan'
            });
        }
    }

    // Update fungsi confirmDeleteRule untuk memanggil deleteRule
    function confirmDeleteRule(id) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: "Apakah Anda yakin ingin menghapus aturan ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteRule(id);
            }
        });
    }
</script>
</body>
</html>
