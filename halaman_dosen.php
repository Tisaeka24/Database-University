<?php
session_start();

// SATPAM: Jika belum login atau perannya BUKAN dosen, tendang balik ke login.php
if (!isset($_SESSION['peran']) || $_SESSION['peran'] !== 'dosen') {
    header("Location: login.php?error=akses_ditolak");
    exit();
}

include 'koneksi.php';
$id_pengguna = $_SESSION['id_pengguna'];

// Ambil profil detail dosen yang sedang login menggunakan INNER JOIN
$query = "SELECT d.*, p.nama_pengguna FROM dosen d 
          INNER JOIN pengguna p ON d.id_pengguna = p.id 
          WHERE p.id = '$id_pengguna'";
$result = mysqli_query($koneksi, $query);
$data_dosen = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-success shadow-sm">
        <div class="container">
            <span class="navbar-brand mb-0 h1">Portal Akademik Dosen</span>
            <span class="navbar-text text-white">
                NIDN: <strong><?php echo htmlspecialchars($data_dosen['nidn']); ?></strong>
            </span>
        </div>
    </nav>

    <div class="container mt-5" style="max-width: 600px;">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 70px; height: 70px; font-size: 30px;">
                        👨‍🏫
                    </div>
                    <h4>Selamat Datang, <?php echo htmlspecialchars($data_dosen['nama']); ?>!</h4>
                    <p class="text-muted small">Anda masuk sebagai Dosen / Tenaga Pengajar</p>
                </div>
                
                <hr>

                <h5 class="fw-bold mb-3 text-secondary">Informasi Kepegawaian</h5>
                <div class="row mb-2">
                    <div class="col-4 text-muted">NIDN</div>
                    <div class="col-8 fw-bold">: <?php echo htmlspecialchars($data_dosen['nidn']); ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-4 text-muted">Nama & Gelar</div>
                    <div class="col-8">: <?php echo htmlspecialchars($data_dosen['nama']); ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-4 text-muted">Homebase</div>
                    <div class="col-8">: <?php echo htmlspecialchars($data_dosen['jurusan']); ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-4 text-muted">Mata Kuliah</div>
                    <div class="col-8">: <span class="badge bg-success-subtle text-success border border-success-subtle"><?php echo htmlspecialchars($data_dosen['mata_kuliah']); ?></span></div>
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
