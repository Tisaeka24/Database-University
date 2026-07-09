<?php
header("Content-Type: application/json");
session_start();

// Validasi Satpam Session di level API
if (!isset($_SESSION['peran']) || $_SESSION['peran'] !== 'admin') {
    echo json_encode(["status" => "error", "message" => "Akses tidak sah!"]);
    exit();
}

include 'koneksi.php';

$aksi = $_GET['aksi'] ?? '';
$response = ["status" => "error", "message" => "Aksi tidak dikenali."];

// ─── 1. AMBIL DATA (READ) ───
if ($aksi === 'ambil') {
    $query = "SELECT m.*, p.id AS id_pengguna FROM mahasiswa m INNER JOIN pengguna p ON m.id_pengguna = p.id";
    $result = mysqli_query($koneksi, $query);
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    echo json_encode(["status" => "success", "data" => $data]);
    exit();
}

// Menangkap data JSON dari Request Body (untuk Tambah, Edit, Hapus)
$input_raw = file_get_contents("php://input");
$data_input = json_decode($input_raw, true);

// ─── 2. TAMBAH DATA (CREATE) ───
if ($aksi === 'tambah' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim        = mysqli_real_escape_string($koneksi, $data_input['nim'] ?? '');
    $kata_sandi = password_hash($data_input['kata_sandi'] ?? 'mhs123', PASSWORD_DEFAULT);
    $nama       = mysqli_real_escape_string($koneksi, $data_input['nama'] ?? '');
    $jurusan    = mysqli_real_escape_string($koneksi, $data_input['jurusan'] ?? '');

    $cek = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE nama_pengguna = '$nim'");
    if (mysqli_num_rows($cek) > 0) {
        echo json_encode(["status" => "error", "message" => "NIM sudah terpakai!"]);
        exit();
    }

    mysqli_query($koneksi, "INSERT INTO pengguna (nama_pengguna, kata_sandi, peran) VALUES ('$nim', '$kata_sandi', 'mahasiswa')");
    $id_baru = mysqli_insert_id($koneksi);
    mysqli_query($koneksi, "INSERT INTO mahasiswa (nim, id_pengguna, nama, jurusan) VALUES ('$nim', '$id_baru', '$nama', '$jurusan')");
    
    echo json_encode(["status" => "success", "message" => "Mahasiswa berhasil ditambahkan!"]);
    exit();
}

// ─── 3. UBAH DATA (UPDATE) ───
if ($aksi === 'ubah' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = mysqli_real_escape_string($koneksi, $data_input['id_pengguna'] ?? '');
    $nama    = mysqli_real_escape_string($koneksi, $data_input['nama'] ?? '');
    $jurusan = mysqli_real_escape_string($koneksi, $data_input['jurusan'] ?? '');
    
    mysqli_query($koneksi, "UPDATE mahasiswa SET nama='$nama', jurusan='$jurusan' WHERE id_pengguna='$id_user'");

    if (!empty($data_input['kata_sandi_baru'])) {
        $pass_baru = password_hash($data_input['kata_sandi_baru'], PASSWORD_DEFAULT);
        mysqli_query($koneksi, "UPDATE pengguna SET kata_sandi='$pass_baru' WHERE id='$id_user'");
    }

    echo json_encode(["status" => "success", "message" => "Data mahasiswa berhasil diperbarui!"]);
    exit();
}

// ─── 4. HAPUS DATA (DELETE) ───
if ($aksi === 'hapus' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_hapus = mysqli_real_escape_string($koneksi, $data_input['id_pengguna'] ?? '');
    mysqli_query($koneksi, "DELETE FROM pengguna WHERE id = '$id_hapus'");
    
    echo json_encode(["status" => "success", "message" => "Mahasiswa berhasil dihapus!"]);
    exit();
}

echo json_encode($response);