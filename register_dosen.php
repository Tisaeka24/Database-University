<?php
include 'koneksi.php';

$error_pesan = "";

if (isset($_POST['daftar_dosen'])) {
    $nidn        = mysqli_real_escape_string($koneksi, $_POST['nidn']);
    $kata_sandi  = password_hash($_POST['kata_sandi'], PASSWORD_DEFAULT);
    $nama        = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $jurusan     = mysqli_real_escape_string($koneksi, $_POST['jurusan']);
    $mata_kuliah = mysqli_real_escape_string($koneksi, $_POST['mata_kuliah']);

    // Cek duplikasi akun
    $cek_username = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE nama_pengguna = '$nidn'");
    if (mysqli_num_rows($cek_username) > 0) {
        $error_pesan = "NIDN sudah terdaftar! Silakan login.";
    } else {
        // 1. Simpan data login utama
        $query_pengguna = "INSERT INTO pengguna (nama_pengguna, kata_sandi, peran) VALUES ('$nidn', '$kata_sandi', 'dosen')";
        if (mysqli_query($koneksi, $query_pengguna)) {
            $id_pengguna_baru = mysqli_insert_id($koneksi);

            // 2. Simpan data profil dosen
            $query_dosen = "INSERT INTO dosen (nidn, id_pengguna, nama, jurusan, mata_kuliah) VALUES ('$nidn', '$id_pengguna_baru', '$nama', '$jurusan', '$mata_kuliah')";
            mysqli_query($koneksi, $query_dosen);

            header("Location: login.php?pesan=registrasi_berhasil");
            exit();
        } else {
            $error_pesan = "Terjadi kesalahan sistem, pendaftaran gagal.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pendaftaran Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">
    <div class="container" style="max-width: 500px;">
        <div class="card shadow-sm p-4">
            <h3 class="text-center mb-4">Daftar Akun Dosen</h3>

            <?php if (!empty($error_pesan)): ?>
                <div class="alert alert-danger py-2 small"><?php echo $error_pesan; ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label">NIDN (Nomor Induk Dosen Nasional)</label>
                    <input type="text" name="nidn" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kata Sandi</label>
                    <input type="password" name="kata_sandi" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap & Gelar</label>
                    <input type="text" name="nama" class="form-control" placeholder="Contoh: Dr. Eko, M.T." required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Homebase Jurusan</label>
                    <input type="text" name="jurusan" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mata Kuliah Utama</label>
                    <input type="text" name="mata_kuliah" class="form-control" required>
                </div>
                <button type="submit" name="daftar_dosen" class="btn btn-success w-100">Daftar Sekarang</button>
            </form>
            <div class="text-center mt-3 small">
                Sudah punya akun? <a href="login.php">Login di sini</a>
            </div>
        </div>
    </div>
</body>
</html>
