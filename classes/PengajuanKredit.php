<?php

class PengajuanKredit {
    private $koneksi;

    public function __construct($db) {
        $this->koneksi = $db;
    }

    // Method untuk submit pengajuan
    public function submitPengajuan($data, $dokumen) {
        $nama = $this->koneksi->real_escape_string($data['nama']);
        $email = $this->koneksi->real_escape_string($data['email']);
        $telepon = $this->koneksi->real_escape_string($data['telepon']);
        $jenis_kendaraan = $this->koneksi->real_escape_string($data['jenis_kendaraan']);
        $harga_kendaraan = $this->koneksi->real_escape_string($data['harga_kendaraan']);
        $nama_dokumen = $dokumen['name'];

        $query = "INSERT INTO pengajuan_kredit 
                  (nama, email, telepon, jenis_kendaraan, harga_kendaraan, dokumen, status) 
                  VALUES 
                  ('$nama', '$email', '$telepon', '$jenis_kendaraan', '$harga_kendaraan', '$nama_dokumen', 'Diproses')";
        
        return $this->koneksi->query($query);
    }

    // Method untuk approval manager
    public function approvalManager($id_pengajuan, $status) {
        $query = "UPDATE pengajuan_kredit 
                  SET status_approval = '$status' 
                  WHERE id = '$id_pengajuan'";
        
        return $this->koneksi->query($query);
    }

    // Method untuk upload dokumen tanda tangan
    public function uploadDokumenTTD($id_pengajuan, $dokumen_ttd) {
        $nama_dokumen = $dokumen_ttd['name'];

        $query = "UPDATE pengajuan_kredit 
                  SET dokumen_ttd = '$nama_dokumen', 
                  status = 'Selesai' 
                  WHERE id = '$id_pengajuan'";
        
        return $this->koneksi->query($query);
    }
}
?>