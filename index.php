<?php
// index.php
require_once 'config/database.php';
require_once 'classes/PengajuanKredit.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pengajuan = new PengajuanKredit($koneksi);
    
    // Validasi input
    if (empty($_POST['nama']) || empty($_POST['email']) || empty($_POST['telepon'])) {
        $error = "Mohon lengkapi semua field";
    } elseif (!empty($_FILES['dokumen_pengajuan']['name'])) {
        // Proses upload dokumen
        $target_dir = "uploads/pengajuan/";
        $target_file = $target_dir . basename($_FILES["dokumen_pengajuan"]["name"]);
        
        if (move_uploaded_file($_FILES["dokumen_pengajuan"]["tmp_name"], $target_file)) {
            $data = [
                'nama' => $_POST['nama'],
                'email' => $_POST['email'],
                'telepon' => $_POST['telepon'],
                'jenis_kendaraan' => $_POST['jenis_kendaraan'],
                'harga_kendaraan' => $_POST['harga_kendaraan']
            ];

            if ($pengajuan->submitPengajuan($data, $_FILES['dokumen_pengajuan'])) {
                $success = "Pengajuan berhasil disimpan";
            } else {
                $error = "Gagal menyimpan pengajuan";
            }
        } else {
            $error = "Gagal upload dokumen";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengajuan Kredit Kendaraan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h2>Formulir Pengajuan Kredit Kendaraan</h2>
            </div>
            <div class="card-body">
                <?php 
                if (!empty($error)) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
                if (!empty($success)) {
                    echo "<div class='alert alert-success'>$success</div>";
                }
                ?>
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Nomor Telepon</label>
                        <input type="tel" name="telepon" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Jenis Kendaraan</label>
                        <select name="jenis_kendaraan" class="form-control" required>
                            <option value="motor">Sepeda Motor</option>
                            <option value="mobil">Mobil</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Harga Kendaraan</label>
                        <input type="number" name="harga_kendaraan" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Dokumen Pengajuan</label>
                        <input type="file" name="dokumen_pengajuan" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>