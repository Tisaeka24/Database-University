<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Mahasiswa - Modern API</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <span class="navbar-brand mb-0 h1">Panel Admin (API Mode)</span>
            <a href="dashboard_admin.php" class="btn btn-outline-light btn-sm">◀ Kembali</a>
        </div>
    </nav>

    <div class="container">
        <div class="card shadow-sm border-0 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold text-secondary m-0">Daftar Mahasiswa</h4>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">＋ Tambah Mahasiswa</button>
            </div>

            <div id="alert-container"></div>

            <div class="table-responsive">
                <table class="table table-hover align-middle border">
                    <thead class="table-light text-uppercase fs-7">
                        <tr>
                            <th class="ps-3">NIM</th>
                            <th>Nama Mahasiswa</th>
                            <th>Jurusan</th>
                            <th class="text-center" style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tempat-data-tabel">
                        </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="formTambah" class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold">Tambah Mahasiswa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">NIM</label><input type="text" id="add-nim" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Kata Sandi Default</label><input type="password" id="add-sandi" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Nama Lengkap</label><input type="text" id="add-nama" class="form-control" required></div>
                    <div class="mb-3">
                        <label class="form-label">Jurusan</label>
                        <select id="add-jurusan" class="form-select" required>
                            <option value="Teknik Informatika">Teknik Informatika</option>
                            <option value="Sistem Informasi">Sistem Informasi</option>
                            <option value="Akuntansi">Akuntansi</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="formEdit" class="modal-content border-0 shadow">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-bold">Ubah Data Mahasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit-id">
                    <div class="mb-3"><label class="form-label text-muted">NIM (Permanen)</label><input type="text" id="edit-nim" class="form-control bg-light" disabled></div>
                    <div class="mb-3"><label class="form-label">Nama Lengkap</label><input type="text" id="edit-nama" class="form-control" required></div>
                    <div class="mb-3">
                        <label class="form-label">Jurusan</label>
                        <select id="edit-jurusan" class="form-select" required>
                            <option value="Teknik Informatika">Teknik Informatika</option>
                            <option value="Sistem Informasi">Sistem Informasi</option>
                            <option value="Akuntansi">Akuntansi</option>
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label">Atur Ulang Kata Sandi (Opsional)</label><input type="password" id="edit-sandi-baru" class="form-control" placeholder="Isi jika ingin diubah"></div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Perbarui</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalHapus" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <form id="formHapus" class="modal-content border-0 shadow">
                <div class="modal-body text-center pt-4">
                    <div class="text-danger mb-3" style="font-size: 50px;">⚠️</div>
                    <h5 class="fw-bold mb-2">Konfirmasi Hapus</h5>
                    <p class="text-muted small">Yakin ingin menghapus <strong id="hapus-nama-txt"></strong>?</p>
                    <input type="hidden" id="hapus-id">
                </div>
                <div class="modal-footer border-0 d-flex justify-content-center pb-4">
                    <button type="button" class="btn btn-light px-4 border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger px-4">Ya, Hapus</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Instance Modal Bootstrap biar bisa ditutup via JS
        const btsTambahModal = new bootstrap.Modal(document.getElementById('modalTambah'));
        const btsEditModal = new bootstrap.Modal(document.getElementById('modalEdit'));
        const btsHapusModal = new bootstrap.Modal(document.getElementById('modalHapus'));

        // Fungsi pembantu untuk memunculkan alert sukses/gagal
        function tampilkanAlert(pesan, jenis = 'success') {
            const container = document.getElementById('alert-container');
            container.innerHTML = `
                <div class="alert alert-${jenis} alert-dismissible fade show" role="alert">
                    ${jenis === 'success' ? '✨' : '❌'} ${pesan}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
        }

        // ─── FUNGSI UTAMA: LOAD DATA DARI API ───
        async function muatDataMahasiswa() {
            const tbody = document.getElementById('tempat-data-tabel');
            tbody.innerHTML = `<tr><td colspan="4" class="text-center py-4 text-muted"><span class="spinner-border spinner-border-sm me-2"></span>Memuat data...</td></tr>`;
            
            try {
                const response = await fetch('api_mahasiswa.php?aksi=ambil');
                const hasil = await response.json();
                
                if (hasil.status === 'success') {
                    tbody.innerHTML = '';
                    if (hasil.data.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="4" class="text-center py-4 text-muted">Belum ada data mahasiswa.</td></tr>`;
                        return;
                    }

                    hasil.data.forEach(mhs => {
                        tbody.innerHTML += `
                            <tr>
                                <td class="ps-3 fw-bold">${mhs.nim}</td>
                                <td>${mhs.nama}</td>
                                <td><span class="badge bg-light text-dark border">${mhs.jurusan}</span></td>
                                <td class="text-center">
                                    <button class="btn btn-warning btn-sm" onclick="bukaModalEdit('${mhs.id_pengguna}', '${mhs.nim}', '${mhs.nama}', '${mhs.jurusan}')">Edit</button>
                                    <button class="btn btn-danger btn-sm" onclick="bukaModalHapus('${mhs.id_pengguna}', '${mhs.nama}')">Hapus</button>
                                </td>
                            </tr>
                        `;
                    });
                }
            } catch (err) {
                tbody.innerHTML = `<tr><td colspan="4" class="text-center py-4 text-danger">Gagal memuat data dari server.</td></tr>`;
            }
        }

        // Jalankan fungsi load data saat halaman pertama kali dibuka
        document.addEventListener('DOMContentLoaded', muatDataMahasiswa);

        // ─── PROSES TAMBAH DATA ───
        document.getElementById('formTambah').addEventListener('submit', async function(e) {
            e.preventDefault();
            const data = {
                nim: document.getElementById('add-nim').value,
                kata_sandi: document.getElementById('add-sandi').value,
                nama: document.getElementById('add-nama').value,
                jurusan: document.getElementById('add-jurusan').value
            };

            const response = await fetch('api_mahasiswa.php?aksi=tambah', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const hasil = await response.json();
            
            if(hasil.status === 'success') {
                btsTambahModal.hide();
                document.getElementById('formTambah').reset();
                tampilkanAlert(hasil.message);
                muatDataMahasiswa(); // Update tabel instan tanpa reload halaman
            } else {
                alert(hasil.message);
            }
        });

        // ─── PROSES EDIT DATA ───
        function bukaModalEdit(id, nim, nama, jurusan) {
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-nim').value = nim;
            document.getElementById('edit-nama').value = nama;
            document.getElementById('edit-jurusan').value = jurusan;
            document.getElementById('edit-sandi-baru').value = '';
            btsEditModal.show();
        }

        document.getElementById('formEdit').addEventListener('submit', async function(e) {
            e.preventDefault();
            const data = {
                id_pengguna: document.getElementById('edit-id').value,
                nama: document.getElementById('edit-nama').value,
                jurusan: document.getElementById('edit-jurusan').value,
                kata_sandi_baru: document.getElementById('edit-sandi-baru').value
            };

            const response = await fetch('api_mahasiswa.php?aksi=ubah', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const hasil = await response.json();
            
            if(hasil.status === 'success') {
                btsEditModal.hide();
                tampilkanAlert(hasil.message);
                muatDataMahasiswa();
            }
        });

        // ─── PROSES HAPUS DATA ───
        function bukaModalHapus(id, nama) {
            document.getElementById('hapus-id').value = id;
            document.getElementById('hapus-nama-txt').innerText = nama;
            btsHapusModal.show();
        }

        document.getElementById('formHapus').addEventListener('submit', async function(e) {
            e.preventDefault();
            const data = { id_pengguna: document.getElementById('hapus-id').value };

            const response = await fetch('api_mahasiswa.php?aksi=hapus', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const hasil = await response.json();
            
            if(hasil.status === 'success') {
                btsHapusModal.hide();
                tampilkanAlert(hasil.message, 'danger');
                muatDataMahasiswa();
            }
        });
    </script>
</body>
</html>