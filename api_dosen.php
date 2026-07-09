<?php
header("Content-Type: application/json");
session_start();

// Validasi Keamanan Session
if (!isset($_SESSION['peran']) || $_SESSION['peran'] !== 'admin') {
    echo json_encode(["status" => "error", "message" => "Akses tidak sah!"]);
    exit();
}

include 'koneksi.php';

$aksi = $_GET['aksi'] ?? '';
$response = ["status" => "error", "message" => "Aksi tidak dikenali."];

// ─── 1. AMBIL DATA DOSEN (READ) ───
if ($aksi === 'ambil') {
    $query = "SELECT d.*, p.id AS id_pengguna FROM dosen d INNER JOIN pengguna p ON d.id_pengguna = p.id";
    $result = mysqli_query($koneksi, $query);
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    echo json_encode(["status" => "success", "data" => $data]);
    exit();
}

// Tangkap raw data JSON dari Request Body
$input_raw = file_get_contents("php://input");
$data_input = json_decode($input_raw, true);

// ─── 2. TAMBAH DATA DOSEN (CREATE) ───
if ($aksi === 'tambah' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nidn        = mysqli_real_escape_string($koneksi, $data_input['nidn'] ?? '');
    $kata_sandi  = password_hash($data_input['kata_sandi'] ?? 'dosen123', PASSWORD_DEFAULT);
    $nama        = mysqli_real_escape_string($koneksi, $data_input['nama'] ?? '');
    $jurusan     = mysqli_real_escape_string($koneksi, $data_input['jurusan'] ?? '');
    $mata_kuliah = mysqli_real_escape_string($koneksi, $data_input['mata_kuliah'] ?? '');

    $cek = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE nama_pengguna = '$nidn'");
    if (mysqli_num_rows($cek) > 0) {
        echo json_encode(["status" => "error", "message" => "NIDN sudah terdaftar!"]);
        exit();
    }

    mysqli_query($koneksi, "INSERT INTO pengguna (nama_pengguna, kata_sandi, peran) VALUES ('$nidn', '$kata_sandi', 'dosen')");
    $id_baru = mysqli_insert_id($koneksi);
    mysqli_query($koneksi, "INSERT INTO dosen (nidn, id_pengguna, nama, jurusan, mata_kuliah) VALUES ('$nidn', '$id_baru', '$nama', '$jurusan', '$mata_kuliah')");

    echo json_encode(["status" => "success", "message" => "Dosen baru berhasil ditambahkan!"]);
    exit();
}

// ─── 3. UBAH DATA DOSEN (UPDATE) ───
if ($aksi === 'ubah' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user     = mysqli_real_escape_string($koneksi, $data_input['id_pengguna'] ?? '');
    $nama        = mysqli_real_escape_string($koneksi, $data_input['nama'] ?? '');
    $jurusan     = mysqli_real_escape_string($koneksi, $data_input['jurusan'] ?? '');
    $mata_kuliah = mysqli_real_escape_string($koneksi, $data_input['mata_kuliah'] ?? '');
    
    mysqli_query($koneksi, "UPDATE dosen SET nama='$nama', jurusan='$jurusan', mata_kuliah='$mata_kuliah' WHERE id_pengguna='$id_user'");

    if (!empty($data_input['kata_sandi_baru'])) {
        $pass_baru = password_hash($data_input['kata_sandi_baru'], PASSWORD_DEFAULT);
        mysqli_query($koneksi, "UPDATE pengguna SET kata_sandi='$pass_baru' WHERE id='$id_user'");
    }

    echo json_encode(["status" => "success", "message" => "Profil dosen berhasil diperbarui!"]);
    exit();
}

// ─── 4. HAPUS DATA DOSEN (DELETE) ───
if ($aksi === 'hapus' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_hapus = mysqli_real_escape_string($koneksi, $data_input['id_pengguna'] ?? '');
    mysqli_query($koneksi, "DELETE FROM pengguna WHERE id = '$id_hapus'");

    echo json_encode(["status" => "success", "message" => "Data dosen berhasil dihapus!"]);
    exit();
}

echo json_encode($response);