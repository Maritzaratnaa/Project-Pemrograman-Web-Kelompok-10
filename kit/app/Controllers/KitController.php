<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BarangModel;
use App\Models\KeranjangModel;

class KitController extends BaseController
{   
    protected $barangModel;
    protected $keranjangModel;

    public function __construct()
    {
        $this->barangModel = new BarangModel();
        $this->keranjangModel = new KeranjangModel();
        session(); 
    }

    public function home() {
        if (!session()->has('id_user')) {
            return redirect()->to('/login')->with('error', 'Anda harus login terlebih dahulu.');
        }
        $id_user = session()->get('id_user');
        $cartCount = $this->keranjangModel->countItemsByUser($id_user);

        return view('home', [
            'cartCount' => $cartCount, 
            'id_user' => $id_user]);
    }

    public function katalog($kategori = null)
    {
        $items = [];
        $id_user = session()->get('id_user');
        $cartCount = $this->keranjangModel->countItemsByUser($id_user);

        if ($kategori) {
            $items = $this->barangModel->where('nama_katalog', $kategori)
                ->join('katalog', 'barang.id_katalog = katalog.id_katalog')
                ->select('barang.id_barang, barang.nama_barang, barang.harga, barang.gambar')
                ->findAll();
        }

        switch ($kategori) {
            case 'bag':
                $view = 'katalog/bag';
                break;
            case 'electronic':
                $view = 'katalog/electronic';
                break;
            case 'lightstick':
                $view = 'katalog/lightstick';
                break;
            case 'more':
                $view = 'katalog/more';
                break;
            default:
                $view = 'home';
                break;
        }

        return view($view, [
            'kategori' => $kategori,
            'items' => $items,
            'id_user' => $id_user,
            'cartCount' => $cartCount
        ]);
    }

    public function add($kategori)
    {
        return view('barang/add', ['kategori' => $kategori]);
    }

    public function store($kategori)
    {
        if (session()->get('role') != '1') {
            return redirect()->to('/')->with('error', 'Unauthorized access.');
        }

        $barangModel = new BarangModel();

        $validationRules = [
            'nama_barang' => 'required|min_length[3]',
            'gambar' => 'uploaded[gambar]|is_image[gambar]|max_size[gambar,2048]', // Maksimum 2MB
            'quantity' => 'required|numeric',
            'harga' => 'required|numeric'
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $gambar = $this->request->getFile('gambar');

        $kategoriMap = [
            'lightstick' => ['id_katalog' => 1, 'path' => 'img/lightstick/'],
            'bag' => ['id_katalog' => 2, 'path' => 'img/bag/'],
            'electronic' => ['id_katalog' => 3, 'path' => 'img/electronic/'],
            'more' => ['id_katalog' => 4, 'path' => 'img/more/']
        ];

        if (!array_key_exists($kategori, $kategoriMap)) {
            return redirect()->back()->with('error', 'Kategori tidak valid.');
        }

        $id_katalog = $kategoriMap[$kategori]['id_katalog'];
        $targetPath = $kategoriMap[$kategori]['path'];

        $gambarName = $gambar->getRandomName();
        $fullPath = ROOTPATH . 'public/' . $targetPath;

        if (!is_dir($fullPath)) {
            mkdir($fullPath, 0777, true);
        }

        if (!$gambar->move($fullPath, $gambarName)) {
            return redirect()->back()->with('error', 'Gagal mengunggah gambar.'); 
        }

        $data = [
            'id_katalog' => $id_katalog,
            'nama_barang' => $this->request->getPost('nama_barang'),
            'gambar' => $targetPath . $gambarName,
            'quantity' => $this->request->getPost('quantity'),
            'harga' => $this->request->getPost('harga')
        ];

        $barangModel->save($data);

        return redirect()->to('katalog/' . $kategori)->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit($id_barang)
    {
        $barangModel = new BarangModel();
        $barang = $barangModel->find($id_barang);

        if (!$barang) {
            return redirect()->to('/barang')->with('error', 'Barang tidak ditemukan.');
        }

        return view('barang/edit', ['barang' => $barang]);
    }

    public function update($id_barang)
    {
        $barangModel = new BarangModel();

        $validationRules = [
            'nama_barang' => 'required|min_length[3]',
            'harga' => 'required|numeric',
            'quantity' => 'required|numeric',
            'gambar' => 'is_image[gambar]|max_size[gambar,2048]'
        ];
    
        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $item = $barangModel->find($id_barang);
        if (!$item) {
            return redirect()->to('/katalog')->with('error', 'Barang tidak ditemukan.');
        }

        $gambar = $this->request->getFile('gambar');
        $gambarPath = $item['gambar']; 
    
        if ($gambar && $gambar->isValid()) {
            $kategoriMap = [
                1 => 'lightstick',
                2 => 'bag',
                3 => 'electronic',
                4 => 'more'
            ];
            $kategori = $kategoriMap[$item['id_katalog']] ?? 'more';
    
            $targetPath = 'img/' . $kategori . '/';
            $gambarName = $gambar->getRandomName();
            $fullPath = ROOTPATH . 'public/' . $targetPath;
    
            if (!is_dir($fullPath)) {
                mkdir($fullPath, 0777, true);
            }
    
            if ($gambar->move($fullPath, $gambarName)) {
                $gambarPath = $targetPath . $gambarName;

                if (is_file(ROOTPATH . 'public/' . $item['gambar'])) {
                    unlink(ROOTPATH . 'public/' . $item['gambar']);
                }
            } else {
                return redirect()->back()->with('error', 'Gagal mengunggah gambar baru.');
            }
        }

        $data = [
            'nama_barang' => $this->request->getPost('nama_barang'),
            'harga' => $this->request->getPost('harga'),
            'quantity' => $this->request->getPost('quantity'),
            'gambar' => $gambarPath
        ];
    
        $barangModel->update($id_barang, $data);
    
        return redirect()->to('/katalog')->with('success', 'Barang berhasil diperbarui.');
    }
    
    public function delete($id_barang)
    {
        $this->barangModel->delete($id_barang);

        return redirect()->to('/katalog')->with('success', 'Barang berhasil dihapus.');
    }

    public function ajaxSearch()
    {
        if ($this->request->isAJAX()) {
            $query = $this->request->getGet('query');

            if (empty($query)) {
                return $this->response->setJSON([]);
            }

            $barangModel = new BarangModel();
            $barang = $barangModel->like('nama_barang', $query)->findAll();

            return $this->response->setJSON($barang);
        }
    }

    public function search()
    {
        $query = $this->request->getGet('query');

        if (empty($query)) {
            return redirect()->to('/')->with('error', 'Please enter a search term.');
        }
        $id_user = session()->get('id_user');
        $cartCount = $this->keranjangModel->countItemsByUser($id_user);
        $barang = $this->barangModel->like('nama_barang', $query)->findAll();

        return view('search', [
            'barang' => $barang,
            'query' => $query,
            'id_user' => $id_user,
            'cartCount' => $cartCount
        ]);
    }
}
