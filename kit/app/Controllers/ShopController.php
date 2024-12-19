<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\BarangModel;
use App\Models\KeranjangModel;
use App\Models\DetailKeranjangModel;
use App\Models\PembelianModel;
use App\Models\DetailPembelianModel;

class ShopController extends BaseController
{
    protected $userModel;
    protected $barangModel;
    protected $keranjangModel;
    protected $detailKeranjangModel;
    protected $pembelianModel;
    protected $detailPembelianModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->barangModel = new BarangModel();
        $this->keranjangModel = new KeranjangModel();
        $this->detailKeranjangModel = new DetailKeranjangModel();
        $this->pembelianModel = new PembelianModel();
        $this->detailPembelianModel = new DetailPembelianModel();
        helper('form');
        session(); 
    }

    public function showKeranjang($id_user)
    {
        $keranjang = $this->keranjangModel->getKeranjangWithDetails($id_user);

        $cartCount = $this->keranjangModel->countItemsByUser($id_user);
        $totalHarga = $this->keranjangModel->getTotalHargaByUser($id_user);
        $totalHarga = $totalHarga ? $totalHarga['total_harga'] : 0;

        return view('keranjang', [
            'keranjang' => $keranjang,
            'cartCount' => $cartCount, 
            'totalHarga' => $totalHarga, 
            'id_user' => $id_user 
        ]);
    }

    public function addToCart($id_user, $id_barang)
    {
        $barang = $this->barangModel->find($id_barang);

        if (!$barang) {
            return redirect()->back()->with('error', 'Barang tidak ditemukan.');
        }

        $keranjang = $this->keranjangModel->where('id_user', $id_user)->first();

        if (!$keranjang) {
            $this->keranjangModel->save([
                'id_user' => $id_user,
            ]);
            $keranjang = $this->keranjangModel->where('id_user', $id_user)->first();  
        }

        $detailKeranjang = $this->detailKeranjangModel->where('id_keranjang', $keranjang['id_keranjang'])
                                                    ->where('id_barang', $id_barang)
                                                    ->first();

        if ($detailKeranjang) {
            $newQuantity = $detailKeranjang['jumlah'] + 1;
            $this->detailKeranjangModel->update($detailKeranjang['id_detail_keranjang'], ['jumlah' => $newQuantity]);
        } else {
            $this->detailKeranjangModel->save([
                'id_keranjang' => $keranjang['id_keranjang'],
                'id_barang' => $id_barang,
                'jumlah' => 1
            ]);
        }

        return redirect()->to('/keranjang/'.$id_user);
    }

    public function updateQuantity()
    {
        $request = $this->request->getJSON();
        if (!$request) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request payload']);
        }

        $idDetailKeranjang = $request->id_detail_keranjang;
        $action = $request->action;

        $keranjangItem = $this->detailKeranjangModel->getDetailById($idDetailKeranjang);

        if (!$keranjangItem) {
            return $this->response->setJSON(['success' => false, 'message' => 'Item tidak ditemukan.']);
        }

        $newQuantity = $keranjangItem['jumlah'];
        if ($action === 'increase') {
            $newQuantity++;
        } elseif ($action === 'decrease') {
            $newQuantity--;
            if ($newQuantity <= 0) {
                $this->detailKeranjangModel->delete($idDetailKeranjang);
                $totalHarga = $this->keranjangModel->getTotalHargaByUser(session()->get('id_user'));
                return $this->response->setJSON([
                    'success' => true,
                    'newSubtotal' => 0,
                    'newTotal' => number_format($totalHarga['total_harga'] ?? 0, 0, ',', '.')
                ]);
            }
        }

        $this->detailKeranjangModel->update($idDetailKeranjang, ['jumlah' => $newQuantity]);

        $subtotal = $newQuantity * $keranjangItem['harga'];
        $totalHarga = $this->keranjangModel->getTotalHargaByUser(session()->get('id_user'));

        return $this->response->setJSON([
            'success' => true,
            'newSubtotal' => number_format($subtotal, 0, ',', '.'),
            'newTotal' => number_format($totalHarga['total_harga'] ?? 0, 0, ',', '.')
        ]);
    }

    public function removeItem() 
    {
        $request = $this->request->getJSON();
    
        if (!$request) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request payload']);
        }
    
        $idDetailKeranjang = $request->id_detail_keranjang;
        $action = isset($request->action) ? $request->action : null;
    
        if ($action === null) {
            return $this->response->setJSON(['success' => false, 'message' => 'Action not provided']);
        }
    
        $keranjangItem = $this->detailKeranjangModel->getDetailById($idDetailKeranjang);
    
        if (!$keranjangItem) {
            return $this->response->setJSON(['success' => false, 'message' => 'Item tidak ditemukan.']);
        }
    
        if ($action === 'remove') {
            $this->detailKeranjangModel->delete($idDetailKeranjang);
            $totalHarga = $this->keranjangModel->getTotalHargaByUser(session()->get('id_user'));
            return $this->response->setJSON([
                'success' => true,
                'newTotal' => number_format($totalHarga['total_harga'] ?? 0, 0, ',', '.')
            ]);
        }
    
        return $this->response->setJSON(['success' => false, 'message' => 'Action tidak valid']);
    }

    public function showPayment($id_user)
    {
        $user = $this->userModel->find($id_user);
        if (!$user) {
            return redirect()->to('/home')->with('error', 'User tidak ditemukan.');
        }

        $keranjang = $this->keranjangModel
            ->select('barang.nama_barang, barang.harga, detail_keranjang.jumlah')
            ->join('detail_keranjang', 'detail_keranjang.id_keranjang = keranjang.id_keranjang')
            ->join('barang', 'barang.id_barang = detail_keranjang.id_barang')
            ->where('keranjang.id_user', $id_user)
            ->findAll();

        $totalHarga = 0;
        foreach ($keranjang as $item) {
            $totalHarga += $item['harga'] * $item['jumlah'];
        }

        $cartCount = $this->keranjangModel->countItemsByUser($id_user);

        return view('payment', [
            'user' => $user,
            'keranjang' => $keranjang,
            'totalHarga' => $totalHarga,
            'cartCount' => $cartCount,
            'id_user' => $id_user
        ]);
    }

    public function checkout($id_user)
    {
        date_default_timezone_set('Asia/Jakarta');

        $keranjang = $this->keranjangModel->getKeranjangWithDetails($id_user);

        if (empty($keranjang)) {
            return redirect()->to('/keranjang/'.$id_user)->with('error', 'Keranjang Anda kosong.');
        }

        $user = $this->userModel->find($id_user);
        if (!$user) {
            return redirect()->to('/home')->with('error', 'User tidak ditemukan.');
        }

        $totalHarga = 0;
        $totalBarang = 0;
        foreach ($keranjang as $item) {
            $totalHarga += $item['harga'] * $item['jumlah'];
            $totalBarang += $item['jumlah'];
        }

        $metodePembayaran = $this->request->getPost('payment-method');
        if (empty($metodePembayaran)) {
            return redirect()->to('/keranjang/'.$id_user)->with('error', 'Metode Pembayaran harus dipilih.');
        }

        $pembelianData = [
            'id_user' => $id_user,
            'tanggal_pembelian' => date('Y-m-d H:i:s'),
            'total_barang' => $totalBarang,
            'total_harga' => $totalHarga,
            'metode_pembayaran' => $metodePembayaran,
            'status' => 'pending',
        ];

        if (!$this->pembelianModel->insert($pembelianData)) {
            return redirect()->to('/keranjang/'.$id_user)->with('error', 'Gagal menyimpan data pembelian.');
        }

        $id_pembelian = $this->pembelianModel->getInsertID();
        if (!$id_pembelian) {
            return redirect()->to('/keranjang/'.$id_user)->with('error', 'Gagal mendapatkan ID Pembelian.');
        }

        foreach ($keranjang as $item) {
            $detailPembelianData = [
                'id_pembelian' => $id_pembelian,
                'id_barang' => $item['id_barang'],
                'jumlah' => (int) $item['jumlah'], 
            ];

            if (!$this->detailPembelianModel->insert($detailPembelianData)) {
                return redirect()->to('/keranjang/'.$id_user)->with('error', 'Gagal menyimpan detail pembelian.');
            }
        }

        $id_keranjang = $keranjang[0]['id_keranjang']; 
        if (!$this->detailKeranjangModel->deleteByKeranjangId($id_keranjang)) {
            return redirect()->to('/keranjang/'.$id_user)->with('error', 'Gagal menghapus data keranjang.');
        }

        session()->setFlashdata('success', 'Pembelian Anda sedang diproses.');
        return redirect()->to('/home');
    }

    public function index()
    {
        $data['pembelian'] = $this->pembelianModel->getAllPembelian(); 
        return view('keranjang_admin', $data);
    }

    public function updateStatus($id_user)
    {
        $status = $this->request->getPost('status');
        $this->pembelianModel->update($id_user, ['status' => $status]);

        return redirect()->to('/keranjang_admin')->with('message', 'Status berhasil diperbarui!');
    }

    public function delete($id_pembelian)
    {
        $this->detailPembelianModel->where('id_pembelian', $id_pembelian)->delete();
        $this->pembelianModel->delete($id_pembelian);

        return redirect()->to('/keranjang_admin')->with('success', 'Pembelian berhasil dihapus!');
    }

    public function detail_pembelian()
    {
        $id_user = session()->get('id_user');
        $cartCount = $this->keranjangModel->countItemsByUser($id_user);
        
        if (!$id_user) {
            return redirect()->to('/login');
        }

        $data['pembelian'] = $this->pembelianModel->getPembelianByUser($id_user);
        $data['id_user'] = $id_user;
        $data['cartCount'] = $cartCount;

        return view('detail_pembelian', $data);
    }
}
