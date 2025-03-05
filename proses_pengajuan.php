<?php
// proses_pengajuan.php
require_once 'config/database.php';
require_once 'classes/PengajuanKredit.php';

// Cek apakah request adalah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = [
        'status' => 'error',
        'message' => 'Terjadi kesalahan'
    ];

    // Validasi input
    $required_fields = ['nama', 'email', 'telepon', 'jenis_kendaraan', 'harga_kendaraan'];
    $input_valid = true;

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $input_valid = false;
            $response['message'] = "Field $field wajib diisi";
            break;
        }
    }

    // Validasi dokumen
    if ($input_valid && empty($_FILES['dokumen_pengajuan']['name'])) {
        $input_valid = false;
        $response['message'] = "Dokumen pengajuan wajib diunggah";
    }

    if ($input_valid) {
        // Inisialisasi kelas PengajuanKredit
        $pengajuan = new PengajuanKredit($koneksi);

        // Persiapan data
        $data = [
            'nama' => $_POST['nama'],
            'email' => $_POST['email'],
            'telepon' => $_POST['telepon'],
            'jenis_kendaraan' => $_POST['jenis_kendaraan'],
            'harga_kendaraan' => $_POST['harga_kendaraan']
        ];

        // Proses upload dokumen
        $target_dir = "uploads/pengajuan/";
        $file_extension = pathinfo($_FILES["dokumen_pengajuan"]["name"], PATHINFO_EXTENSION);
        $nama_file_baru = uniqid() . "." . $file_extension;
        $target_file = $target_dir . $nama_file_baru;

        // Validasi tipe file
        $allowed_types = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
        if (in_array(strtolower($file_extension), $allowed_types)) {
            // Pindahkan file yang diunggah
            if (move_uploaded_file($_FILES["dokumen_pengajuan"]["tmp_name"], $target_file)) {
                // Tambahkan nama file ke data
                $_FILES["dokumen_pengajuan"]["name"] = $nama_file_baru;

                // Simpan pengajuan
                if ($pengajuan->submitPengajuan($data, $_FILES['dokumen_pengajuan'])) {
                    $response['status'] = 'success';
                    $response['message'] = 'Pengajuan kredit berhasil disimpan';
                } else {
                    // Hapus file yang sudah diunggah jika penyimpanan gagal
                    unlink($target_file);
                    $response['message'] = 'Gagal menyimpan pengajuan kredit';
                }
            } else {
                $response['message'] = 'Gagal mengunggah dokumen';
            }
        } else {
            $response['message'] = 'Tipe file tidak diizinkan. Gunakan PDF, DOC, DOCX, JPG, JPEG, atau PNG';
        }
    }

    // Kirim respons JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
} else {
    // Jika bukan metode POST
    http_response_code(405);
    echo json_encode([
        'status' => 'error', 
        'message' => 'Metode tidak diizinkan'
    ]);
    exit();
}
?>