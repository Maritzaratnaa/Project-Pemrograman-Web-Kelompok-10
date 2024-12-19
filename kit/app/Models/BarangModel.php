<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangModel extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id_barang';
    protected $allowedFields = ['id_katalog', 'nama_barang', 'gambar', 'quantity', 'harga'];

    public function getBarangByKategori($kategori)
    {
        return $this->join('katalog', 'barang.id_katalog = katalog.id_katalog')
                    ->where('katalog.nama_katalog', $kategori)
                    ->select('barang.nama_barang, barang.harga, barang.gambar')
                    ->findAll();
    }

    public function updateBarang($id_barang, $data)
    {
        return $this->update($id_barang, $data);
    }
}