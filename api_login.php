<?php
header("Content-Type: application/json"); // Wajib memberitahu browser bahwa ini adalah data JSON
session_start();
include 'koneksi.php';

// Menangkap data mentah (raw JSON) yang dikirim oleh JavaScript Fetch
$input_raw = file_get_contents("php://input");
$data_input = json_decode($input_raw, true);

// Wadah respon default
$response = [
    "status"  => "error",
    "message" => "Terjadi kesalahan sistem."
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_pengguna = mysqli_real_escape_string($koneksi, $data_input['nama_pengguna'] ?? '');
    $kata_sandi    = $data_input['kata_sandi'] ?? '';

    if (empty($nama_pengguna) || empty($kata_sandi)) {
        $response['message'] = "Semua kolom wajib diisi!";
        echo json_encode($response);
        exit();
    }

    $query  = "SELECT * FROM pengguna WHERE nama_pengguna = '$nama_pengguna'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) === 1) {
        $data_pengguna = mysqli_fetch_assoc($result);

        // Validasi password (password_hash atau md5)
        if (password_verify($kata_sandi, $data_pengguna['kata_sandi']) || md5($kata_sandi) === $data_pengguna['kata_sandi']) {
            
            // Set Session di sisi server
            $_SESSION['id_pengguna']   = $data_pengguna['id'];
            $_SESSION['nama_pengguna'] = $data_pengguna['nama_pengguna'];
            $_SESSION['peran']         = $data_pengguna['peran'];

            // Tentukan halaman pengalihan berdasarkan peran
            $redirect_url = "dashboard_admin.php";
            if ($data_pengguna['peran'] === 'mahasiswa') {
                $redirect_url = "halaman_mahasiswa.php";
            } elseif ($data_pengguna['peran'] === 'dosen') {
                $redirect_url = "halaman_dosen.php";
            }

            // Kirim respon sukses ke JavaScript
            $response['status'] = "success";
            $response['message'] = "Login berhasil! Mengalihkan...";
            $response['redirect'] = $redirect_url;
            
        } else {
            $response['message'] = "Kata sandi salah!";
        }
    } else {
        $response['message'] = "Nama pengguna tidak terdaftar!";
    }
} else {
    $response['message'] = "Metode request tidak valid!";
}

// Mengubah array PHP menjadi string JSON dan mencetaknya
echo json_encode($response);
exit();