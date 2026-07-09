<?php
include 'koneksi.php';

$error_pesan = "";

if (isset($_POST['daftar_mahasiswa'])) {
    $nim        = mysqli_real_escape_string($koneksi, $_POST['nim']);
    $kata_sandi = password_hash($_POST['kata_sandi'], PASSWORD_DEFAULT);
    $nama       = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $jurusan    = mysqli_real_escape_string($koneksi, $_POST['jurusan']);

    // Cek duplikasi akun
    $cek_username = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE nama_pengguna = '$nim'");
    if (mysqli_num_rows($cek_username) > 0) {
        $error_pesan = "NIM sudah terdaftar! Gunakan NIM lain atau silakan login.";
    } else {
        // 1. Simpan data login utama
        $query_pengguna = "INSERT INTO pengguna (nama_pengguna, kata_sandi, peran) VALUES ('$nim', '$kata_sandi', 'mahasiswa')";
        if (mysqli_query($koneksi, $query_pengguna)) {
            $id_pengguna_baru = mysqli_insert_id($koneksi);

            // 2. Simpan data profil mahasiswa
            $query_mahasiswa = "INSERT INTO mahasiswa (nim, id_pengguna, nama, jurusan) VALUES ('$nim', '$id_pengguna_baru', '$nama', '$jurusan')";
            mysqli_query($koneksi, $query_mahasiswa);

            // Lempar ke login.php dengan membawa tanda sukses
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
    <title>Pendaftaran Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">
    <div class="container" style="max-width: 500px;">
        <div class="card shadow-sm p-4">
            <h3 class="text-center mb-4">Daftar Akun Mahasiswa</h3>

            <?php if (!empty($error_pesan)): ?>
                <div class="alert alert-danger py-2 small"><?php echo $error_pesan; ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label">NIM (Nomor Induk Mahasiswa)</label>
                    <input type="text" name="nim" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kata Sandi</label>
                    <input type="password" name="kata_sandi" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jurusan</label>
                    <select name="jurusan" class="form-select" required>
                        <option value="">-- Pilih Jurusan --</option>
                        <option value="Teknik Informatika">Teknik Informatika</option>
                        <option value="Sistem Informasi">Sistem Informasi</option>
                        <option value="Akuntansi">Akuntansi</option>
                    </select>
                </div>
                <button type="submit" name="daftar_mahasiswa" class="btn btn-success w-100">Daftar Sekarang</button>
            </form>
            <div class="text-center mt-3 small">
                Sudah punya akun? <a href="login.php">Login di sini</a>
            </div>
        </div>
    </div>
</body>
</html>
