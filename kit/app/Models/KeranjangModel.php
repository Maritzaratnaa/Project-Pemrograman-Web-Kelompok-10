<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailKeranjangModel extends Model
{
    protected $table = 'detail_keranjang'; 
    protected $primaryKey = 'id_detail_keranjang';
    protected $allowedFields = ['id_keranjang', 'id_barang', 'jumlah'];

    public function getDetailById($id_detail_keranjang)
    {
        return $this->join('barang', 'barang.id_barang = detail_keranjang.id_barang')
                    ->where('id_detail_keranjang', $id_detail_keranjang)
                    ->first();
    }

    public function deleteByKeranjangId($id_keranjang)
    {
        return $this->db->table('detail_keranjang')
                        ->where('id_keranjang', $id_keranjang)  
                        ->delete();
    }
}

class KeranjangModel extends Model
{
    protected $table = 'keranjang';
    protected $primaryKey = 'id_keranjang';
    protected $allowedFields = ['id_user'];

    public function getKeranjangWithDetails($id_user)
    {
        return $this->select('keranjang.id_keranjang, detail_keranjang.id_detail_keranjang, barang.id_barang, barang.nama_barang, barang.quantity, barang.harga, barang.gambar, detail_keranjang.jumlah')
                    ->join('detail_keranjang', 'detail_keranjang.id_keranjang = keranjang.id_keranjang')
                    ->join('barang', 'barang.id_barang = detail_keranjang.id_barang')
                    ->where('keranjang.id_user', $id_user)
                    ->findAll();
    }

    public function countItemsByUser($id_user)
    {
        return $this->join('detail_keranjang', 'detail_keranjang.id_keranjang = keranjang.id_keranjang')
                    ->where('keranjang.id_user', $id_user)
                    ->countAllResults();
    }

    public function getTotalHargaByUser($id_user)
    {
        $result = $this->join('detail_keranjang', 'detail_keranjang.id_keranjang = keranjang.id_keranjang')
                    ->join('barang', 'barang.id_barang = detail_keranjang.id_barang')
                    ->where('keranjang.id_user', $id_user)
                    ->select('SUM(detail_keranjang.jumlah * barang.harga) AS total_harga')
                    ->first();

        return $result && $result['total_harga'] !== null ? $result : ['total_harga' => 0];
    }

    public function updateQuantity($id_detail_keranjang, $newQuantity)
    {
        if ($newQuantity < 1) {
            return false;
        }
        return $this->db->table('detail_keranjang')->update(['jumlah' => $newQuantity], ['id_detail_keranjang' => $id_detail_keranjang]);
    }

    public function removeItem($id_detail_keranjang)
    {
        return $this->db->table('detail_keranjang')->delete(['id_detail_keranjang' => $id_detail_keranjang]);
    }
}
