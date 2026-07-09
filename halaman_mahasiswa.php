<?php
session_start();

// SATPAM: Jika belum login atau perannya BUKAN mahasiswa, tendang balik ke login.php
if (!isset($_SESSION['peran']) || $_SESSION['peran'] !== 'mahasiswa') {
    header("Location: login.php?error=akses_ditolak");
    exit();
}

include 'koneksi.php';
$id_pengguna = $_SESSION['id_pengguna'];

// Ambil profil detail mahasiswa yang sedang login menggunakan INNER JOIN
$query = "SELECT m.*, p.nama_pengguna FROM mahasiswa m 
          INNER JOIN pengguna p ON m.id_pengguna = p.id 
          WHERE p.id = '$id_pengguna'";
$result = mysqli_query($koneksi, $query);
$data_mhs = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-primary shadow-sm">
        <div class="container">
            <span class="navbar-brand mb-0 h1">Portal Akademik Mahasiswa</span>
            <span class="navbar-text text-white">
                NIM: <strong><?php echo htmlspecialchars($data_mhs['nim']); ?></strong>
            </span>
        </div>
    </nav>

    <div class="container mt-5" style="max-width: 600px;">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 70px; height: 70px; font-size: 30px;">
                        🎓
                    </div>
                    <h4>Selamat Datang, <?php echo htmlspecialchars($data_mhs['nama']); ?>!</h4>
                    <p class="text-muted small">Anda masuk sebagai Mahasiswa Aktif</p>
                </div>
                
                <hr>

                <h5 class="fw-bold mb-3 text-secondary">Informasi Biodata</h5>
                <div class="row mb-2">
                    <div class="col-4 text-muted">NIM</div>
                    <div class="col-8 fw-bold">: <?php echo htmlspecialchars($data_mhs['nim']); ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-4 text-muted">Nama Lengkap</div>
                    <div class="col-8">: <?php echo htmlspecialchars($data_mhs['nama']); ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-4 text-muted">Program Studi</div>
                    <div class="col-8">: <span class="badge bg-light text-dark border"><?php echo htmlspecialchars($data_mhs['jurusan']); ?></span></div>
                </div>

                <hr class="mb-4">

                <div class="text-center">
                    <a href="logout.php" class="btn btn-outline-danger btn-sm px-4" onclick="return confirm('Yakin ingin keluar?')">Keluar / Logout</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
