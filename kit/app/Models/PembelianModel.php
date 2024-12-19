<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPembelianModel extends Model
{
    protected $table = 'detail_pembelian';
    protected $primaryKey = 'id_detail_pembelian';
    protected $allowedFields = ['id_pembelian', 'id_barang', 'jumlah'];
}

class PembelianModel extends Model
{
    protected $table = 'pembelian';
    protected $primaryKey = 'id_pembelian';
    protected $allowedFields = ['id_user', 'tanggal_pembelian', 'total_barang', 'total_harga', 'metode_pembayaran', 'status'];

    public function getAllPembelian()
    {
        return $this->select('pembelian.*, user.nama_user, 
                            GROUP_CONCAT(CONCAT(barang.nama_barang, " (", detail_pembelian.jumlah, ")") SEPARATOR ", ") as detail_barang')
                    ->join('user', 'user.id_user = pembelian.id_user')
                    ->join('detail_pembelian', 'detail_pembelian.id_pembelian = pembelian.id_pembelian')
                    ->join('barang', 'barang.id_barang = detail_pembelian.id_barang')
                    ->groupBy('pembelian.id_pembelian')
                    ->orderBy('pembelian.tanggal_pembelian', 'DESC')
                    ->findAll();
    }

    public function getPembelianByUser($id_user)
    {
        return $this->select('pembelian.tanggal_pembelian, pembelian.total_barang, pembelian.total_harga, pembelian.metode_pembayaran, pembelian.status,
                            GROUP_CONCAT(CONCAT(barang.nama_barang, " (", detail_pembelian.jumlah, ")") SEPARATOR ", ") as detail_barang')
                            ->join('detail_pembelian', 'detail_pembelian.id_pembelian = pembelian.id_pembelian')
                            ->join('barang', 'barang.id_barang = detail_pembelian.id_barang')
                            ->where('pembelian.id_user', $id_user)
                            ->findAll();
    }
}