<?php
session_start();

// 1. SATPAM: Jika belum login atau perannya BUKAN admin, tendang balik ke login.php
if (!isset($_SESSION['peran']) || $_SESSION['peran'] !== 'admin') {
    header("Location: login.php?error=akses_ditolak");
    exit();
}

// 2. ANTI-CACHE: Memaksa browser meminta data terbaru dari server, bukan dari memori lokal (mengatasi bug tombol back)
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Universitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <span class="navbar-brand mb-0 h1">Sistem Informasi Akademik</span>
            <span class="navbar-text text-white">
                Log masuk sebagai: <strong><?php echo htmlspecialchars($_SESSION['nama_pengguna'] ?? 'Admin'); ?> (Admin)</strong>
            </span>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col">
                <h2>Selamat Datang, Admin!</h2>
                <p class="text-muted">Silakan pilih menu di bawah ini untuk mengelola data master universitas.</p>
                <hr>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title text-primary fw-bold">Kelola Data Mahasiswa</h5>
                            <p class="card-text text-muted">Lihat daftar mahasiswa aktif, tambah mahasiswa baru, perbarui data jurusan, atau hapus akun mahasiswa.</p>
                        </div>
                        <div class="mt-3">
                            <a href="kelola_mahasiswa.php" class="btn btn-primary w-100">Buka Pengelolaan Mahasiswa</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title text-success fw-bold">Kelola Data Dosen</h5>
                            <p class="card-text text-muted">Lihat daftar dosen pengajar, tambah dosen baru beserta mata kuliah yang diampu, edit profil, atau hapus akun dosen.</p>
                        </div>
                        <div class="mt-3">
                            <a href="kelola_dosen.php" class="btn btn-success w-100">Buka Pengelolaan Dosen</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col text-center">
                <a href="logout.php" class="btn btn-outline-danger px-4" onclick="return confirm('Apakah Anda yakin ingin keluar dari sistem?')">Keluar / Logout</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>