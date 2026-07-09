<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Akademik - Login Modern</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #171614, #432f20, #786b3f);
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: rgba(209, 200, 119, 0.95);
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        }
        .brand-icon {
            width: 65px; height: 65px;
            background: linear-gradient(135deg, #815b32, #b28a6c);
            color: white;
            display: flex; align-items: center; justify-content: center;
            font-size: 28px; border-radius: 15px; margin: 0 auto 20px;
            box-shadow: 0 8px 20px rgb(255, 255, 255);
        }
        .form-control {
            border-radius: 10px; padding: 12px 15px 12px 45px;
            background-color: #f9fbfd;
        }
        .input-group-custom { position: relative; }
        .input-group-custom i {
            position: absolute; left: 18px; top: 50%;
            transform: translateY(-50%); color: #a0a0a0;
        }
        .btn-login {
            background: linear-gradient(135deg, #815b32, #b28a6c);
            border: none; border-radius: 10px; padding: 12px;
            font-weight: 600; color: white;
        }
        .divider { height: 1px; background-color: #e0e0e0; margin: 25px 0; }
        .register-links a { color: #815b32; text-decoration: none; font-weight: 500; }
    </style>
</head>
<body>

    <div class="container" style="max-width: 440px;">
        <div class="card login-card p-4 p-sm-5" data-aos="fade-up" data-aos-duration="1000">
            
            <div class="brand-icon">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            
            <h3 class="text-center fw-bold text-dark mb-1">Selamat Datang</h3>
            <p class="text-center text-muted small mb-4">Sistem Informasi Akademik Universitas</p>

            <div id="alert-container"></div>

            <form id="formLogin">
                <div class="mb-3 input-group-custom">
                    <label class="form-label text-secondary small fw-semibold">NIM / NIDN </label>
                    <input type="text" id="nama_pengguna" class="form-control" placeholder="Masukkan nomor identitas" required>
                    <i class="fa-solid fa-user"></i>
                </div>
                
                <div class="mb-4 input-group-custom">
                    <label class="form-label text-secondary small fw-semibold">Kata Sandi</label>
                    <input type="password" id="kata_sandi" class="form-control" placeholder="••••••••" required>
                    <i class="fa-solid fa-lock"></i>
                </div>
                
                <button type="submit" id="btnSubmit" class="btn btn-login w-100">
                    Masuk Ke Sistem <i class="fa-solid fa-arrow-right-to-bracket ms-1"></i>
                </button>
            </form>

            <div class="divider"></div>

            <div class="text-center register-links small">
                <span class="text-muted">Belum terdaftar sebagai civitas?</span>
                <div class="mt-2">
                    <a href="register_mahasiswa.php"><i class="fa-solid fa-user-graduate me-1"></i>Daftar Mahasiswa</a>
                    <span class="text-muted mx-2">|</span>
                    <a href="register_dosen.php"><i class="fa-solid fa-chalkboard-user me-1"></i>Daftar Dosen</a>
                </div>
            </div>
            
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({ once: true });

        // ─── LOGIKA JAVASCRIPT FETCH API (ALUR UTAMA) ───
        document.getElementById('formLogin').addEventListener('submit', async function(e) {
            e.preventDefault(); // Cegah halaman reload saat form di-submit

            const btnSubmit = document.getElementById('btnSubmit');
            const alertContainer = document.getElementById('alert-container');
            
            // Ambil data dari elemen input HTML
            const nama_pengguna = document.getElementById('nama_pengguna').value;
            const kata_sandi = document.getElementById('kata_sandi').value;

            // Efek Loading pada Tombol
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memeriksa...`;

            //DISINI JSON

            try {
                // 1. JS mengirimkan data (Fetch POST) ke PHP dalam bentuk string JSON
                const response = await fetch('api_login.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        nama_pengguna: nama_pengguna,
                        kata_sandi: kata_sandi
                    })
                });

                // 2. Menerima data balik (Respon) berupa JSON dari PHP
                const data = await response.json();

                // 3. JS memanipulasi HTML berdasarkan status JSON yang diterima
                if (data.status === 'success') {
                    // Render HTML Alert Sukses
                    alertContainer.innerHTML = `
                        <div class="alert alert-success border-0 shadow-sm py-2 px-3 small d-flex align-items-center mb-3">
                            <i class="fa-solid fa-circle-check me-2"></i>
                            <div>${data.message}</div>
                        </div>
                    `;
                    // Redirect halaman setelah 1.5 detik agar user bisa melihat animasinya
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1500);

                } else {
                    // Render HTML Alert Gagal/Error
                    alertContainer.innerHTML = `
                        <div class="alert alert-danger border-0 shadow-sm py-2 px-3 small d-flex align-items-center mb-3">
                            <i class="fa-solid fa-circle-exclamation me-2"></i>
                            <div>${data.message}</div>
                        </div>
                    `;
                    // Kembalikan tombol ke status normal
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = `Masuk Ke Sistem <i class="fa-solid fa-arrow-right-to-bracket ms-1"></i>`;
                }

            } catch (error) {
                // Penanganan jika server mati atau ada error network
                alertContainer.innerHTML = `
                    <div class="alert alert-danger border-0 shadow-sm py-2 px-3 small d-flex align-items-center mb-3">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i>
                        <div>Koneksi ke server terputus!</div>
                    </div>
                `;
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = `Masuk Ke Sistem <i class="fa-solid fa-arrow-right-to-bracket ms-1"></i>`;
            }
        });
    </script>
</body>
</html>