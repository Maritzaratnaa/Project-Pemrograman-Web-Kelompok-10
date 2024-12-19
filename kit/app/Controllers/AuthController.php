<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\KeranjangModel;

class AuthController extends BaseController
{
    protected $userModel;
    protected $keranjangModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->keranjangModel = new KeranjangModel();
    }
    
    public function login()
    {
        return view('login');
    }
    
    public function submit()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->userModel->validateUser($email, $password);
        log_message('debug', 'User data: ' . json_encode($user));

        if ($user) {
            if (password_verify($password, $user['password'])) {
                session()->set([
                    'email' => $user['email'],
                    'id_user' => $user['id_user'],
                    'role' => $user['role'],
                    'isLoggedIn' => true
                ]);

                if ($user['role'] === 'admin') {
                    return redirect()->to('/admin/dashboard');
                } else {
                    return redirect()->to('/home');
                }
            } else {
                session()->setFlashdata('error', 'Email atau password salah.');
                return redirect()->to('/');
            }
        } else {
            session()->setFlashdata('error', 'Email atau password salah.');
            return redirect()->to('/');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }

    public function register()
    {
        return view('register');
    }

    public function submitRegister()
    {
        $validation = \Config\Services::validation();

        $validation->setRules([
            'nama_user' => 'required',
            'email' => 'required|valid_email',
            'telepon' => 'required|min_length[10]|numeric',
            'alamat' => 'required',
            'password' => 'required|min_length[6]'
        ]);

        if (!$this->validate($validation->getRules())) {
            return redirect()->to('/register')
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $username = $this->request->getPost('nama_user');
        $email = $this->request->getPost('email');
        $telepon = $this->request->getPost('telepon');
        $alamat = $this->request->getPost('alamat');
        $password = $this->request->getPost('password');
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        if ($this->userModel->where('nama_user', $username)->first()) {
            return redirect()->to('/register')
                ->withInput()
                ->with('errors', ['nama_user' => 'Username already exists!']);
        }

        if ($this->userModel->where('email', $email)->first()) {
            return redirect()->to('/register')
                ->withInput()
                ->with('errors', ['email' => 'Email already exists!']);
        }

        $this->userModel->save([
            'nama_user' => $username,
            'email' => $email,
            'telepon' => $telepon,
            'alamat' => $alamat,
            'password' => $hashedPassword,
        ]);

        session()->setFlashdata('success', 'Registration successful!');
        return redirect()->to('/');
    }


    public function profile()
    {
        $userId = session()->get('id_user');

        if (!$userId) {
            return redirect()->to('/');
        }

        $user = $this->userModel->find($userId);
        $cartCount = $this->keranjangModel->countItemsByUser($userId);
        if (!$user) {
            session()->setFlashdata('error', 'User not found!');
            return redirect()->to('/home');
        }

        $users = [];
        if ($user['role'] == '1') {
            $users = $this->userModel->findAll();
        }

        return view('profile', [
            'user' => $user,
            'users' => $users,
            'id_user' => $userId,
            'cartCount' => $cartCount
        ]);
    }

    public function edit()
    {
        $userId = session()->get('id_user');

        $user = $this->userModel->find($userId);
        $cartCount = $this->keranjangModel->countItemsByUser($userId);

        return view('edit_profile', [
            'user' => $user,
            'id_user' => $userId,
            'cartCount' => $cartCount]);
    }

    public function update()
    {
        $userId = session()->get('id_user');
        $data = [
            'nama_user' => $this->request->getPost('nama_user'),
            'email' => $this->request->getPost('email'),
            'telepon' => $this->request->getPost('telepon'),
            'alamat' => $this->request->getPost('alamat')
        ];

        $this->userModel->update($userId, $data);

        return redirect()->to('/profile');
    }

    public function delete($id = null)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please log in first.');
        }

        if ($id === null) {
            session()->setFlashdata('error', 'Invalid user ID!');
            return redirect()->back();
        }

        if (session()->get('role') !== '1') {
            if ($id == session()->get('id_user')) {
                if ($this->userModel->delete($id)) {
                    session()->destroy(); 
                    return redirect()->to('/')->with('success', 'Your account has been deleted successfully.');
                } else {
                    return redirect()->back()->with('error', 'Failed to delete your account.');
                }
            } else {
                return redirect()->to('/')->with('error', 'You do not have permission to delete this account.');
            }
        }

        if (session()->get('role') === '1') {
            if ($id == session()->get('id_user')) {
                session()->setFlashdata('error', 'You cannot delete your own admin account!');
                return redirect()->to('/admin/users');
            }

            if ($this->userModel->delete($id)) {
                session()->setFlashdata('success', 'User deleted successfully!');
            } else {
                session()->setFlashdata('error', 'Failed to delete user!');
            }

            return redirect()->to('/admin/users');
        }
    }
}
