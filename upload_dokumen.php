<?php
// upload_dokumen.php
require_once 'config/database.php';
require_once 'classes/PengajuanKredit.php';

$error = '';
$success = '';

// Ambil daftar pengajuan yang sudah disetujui
$query = "SELECT * FROM pengajuan_kredit WHERE status_approval = 'Disetujui'";
$result = $koneksi->query($query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pengajuan = new PengajuanKredit($koneksi);
    
    if (!empty($_FILES['dokumen_ttd']['name'])) {
        // Buat direktori upload jika belum ada
        $target_dir = "uploads/ttd/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Generate nama file unik
        $file_extension = pathinfo($_FILES["dokumen_ttd"]["name"], PATHINFO_EXTENSION);
        $nama_file_baru = uniqid() . "." . $file_extension;
        $target_file = $target_dir . $nama_file_baru;

        // Daftar tipe file yang diizinkan
        $allowed_types = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
        $file_type = strtolower($file_extension);

        // Validasi tipe file
        if (in_array($file_type, $allowed_types)) {
            // Cek ukuran file (misalnya maks 5MB)
            if ($_FILES["dokumen_ttd"]["size"] <= 5 * 1024 * 1024) {
                // Pindahkan file yang diunggah
                if (move_uploaded_file($_FILES["dokumen_ttd"]["tmp_name"], $target_file)) {
                    $id_pengajuan = $_POST['id_pengajuan'];

                    // Persiapan data file untuk disimpan
                    $_FILES["dokumen_ttd"]["name"] = $nama_file_baru;

                    // Simpan dokumen
                    if ($pengajuan->uploadDokumenTTD($id_pengajuan, $_FILES['dokumen_ttd'])) {
                        $success = "Dokumen berhasil diunggah";
                        header("Refresh:0");
                        exit();
                    } else {
                        // Hapus file jika penyimpanan gagal
                        unlink($target_file);
                        $error = "Gagal menyimpan dokumen ke database";
                    }
                } else {
                    $error = "Gagal mengunggah dokumen";
                }
            } else {
                $error = "Ukuran file terlalu besar. Maks 5MB";
            }
        } else {
            $error = "Tipe file tidak diizinkan. Gunakan PDF, DOC, DOCX, JPG, JPEG, atau PNG";
        }
    } else {
        $error = "Pilih dokumen terlebih dahulu";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Upload Dokumen Tanda Tangan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h2>Upload Dokumen Tanda Tangan</h2>
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
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Status Approval</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['nama'] ?></td>
                            <td><?= $row['email'] ?></td>
                            <td><?= $row['status_approval'] ?></td>
                            <td>
                                <form method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="id_pengajuan" value="<?= $row['id'] ?>">
                                    <input type="file" name="dokumen_ttd" class="form-control mb-2" required>
                                    <button type="submit" class="btn btn-primary btn-sm">Upload Dokumen</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>