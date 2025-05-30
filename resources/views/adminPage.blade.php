<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Halaman Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <h1 class="mb-4">Dashboard Admin</h1>

    <!-- Section: Aturan Aplikasi -->
    <div class="card mb-4">
        <div class="card-header">
            <strong>Aturan Aplikasi</strong>
        </div>
        <div class="card-body">
            <form id="aturanForm">
                <div class="mb-3">
                    <label for="aturan1" class="form-label">Aturan 1</label>
                    <input type="text" class="form-control" id="aturan1" name="aturan1" value="Contoh aturan 1">
                </div>
                <div class="mb-3">
                    <label for="aturan2" class="form-label">Aturan 2</label>
                    <input type="text" class="form-control" id="aturan2" name="aturan2" value="Contoh aturan 2">
                </div>
                <button type="submit" class="btn btn-success">Simpan Perubahan</button>
            </form>
        </div>
    </div>

    <!-- Section: Daftar Users -->
    <div class="card mb-4">
        <div class="card-header">
            <strong>Daftar Users</strong>
        </div>
        <div class="card-body">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Contoh data user, ganti dengan data dinamis dari backend -->
                    <tr>
                        <td>1</td>
                        <td>Budi</td>
                        <td>budi@email.com</td>
                        <td><span class="badge bg-success">Aktif</span></td>
                        <td>
                            <button class="btn btn-danger btn-sm" onclick="bannedUser(1)">Banned</button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Siti</td>
                        <td>siti@email.com</td>
                        <td><span class="badge bg-danger">Banned</span></td>
                        <td>
                            <button class="btn btn-secondary btn-sm" disabled>Banned</button>
                        </td>
                    </tr>
                    <!-- Tambahkan baris user lain di sini -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Section: Daftar Transaksi -->
    <div class="card">
        <div class="card-header">
            <strong>Daftar Transaksi</strong>
        </div>
        <div class="card-body">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>ID Transaksi</th>
                        <th>User</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
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

<script>
    function bannedUser(userId) {
        if(confirm('Yakin ingin banned user ini?')) {
            // Lakukan request ke backend untuk banned user
            alert('User dengan ID ' + userId + ' telah dibanned (simulasi).');
            // Setelah banned, reload halaman atau update status user di tabel
        }
    }

    document.getElementById('aturanForm').addEventListener('submit', function(e) {
        e.preventDefault();
        // Lakukan request ke backend untuk update aturan aplikasi
        alert('Aturan aplikasi berhasil diubah (simulasi).');
    });
</script>
</body>
</html>
