<?php
// approval.php
require_once 'config/database.php';
require_once 'classes/PengajuanKredit.php';

session_start();
// Tambahkan logika autentikasi manager di sini

$pengajuan = new PengajuanKredit($koneksi);
$error = '';
$success = '';

// Ambil daftar pengajuan yang menunggu approval
$query = "SELECT * FROM pengajuan_kredit WHERE status = 'Diproses'";
$result = $koneksi->query($query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pengajuan = $_POST['id_pengajuan'];
    $status_approval = $_POST['status_approval'];

    if ($pengajuan->approvalManager($id_pengajuan, $status_approval)) {
        $success = "Approval berhasil dilakukan";
        header("Refresh:0"); // Refresh halaman
    } else {
        $error = "Gagal melakukan approval";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Approval Pengajuan Kredit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h2>Approval Pengajuan Kredit</h2>
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
                            <th>Jenis Kendaraan</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['nama'] ?></td>
                            <td><?= $row['email'] ?></td>
                            <td><?= $row['jenis_kendaraan'] ?></td>
                            <td>Rp. <?= number_format($row['harga_kendaraan']) ?></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="id_pengajuan" value="<?= $row['id'] ?>">
                                    <button type="submit" name="status_approval" value="Disetujui" class="btn btn-success btn-sm">Setujui</button>
                                    <button type="submit" name="status_approval" value="Ditolak" class="btn btn-danger btn-sm">Tolak</button>
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