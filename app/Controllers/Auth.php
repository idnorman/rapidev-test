<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Auth extends BaseController
{
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->userModel = new \App\Models\UserModel();
    }

    public function login()
    {
        $data['title'] = 'Daftar Pelanggan';
        return view('auth\login', $data);
    }

    public function loginProcess()
    {
        $request = $this->request->getPost();

        $user = $this->userModel->where('email', $request['email'])->first();
        if($user){
            $authenticated = password_verify($request['password'], $user['password']);
            if($authenticated){
                $userSession = [
                    'user_id'       => $user['id'],
                    'user_email'    => $user['email'],
                    'isLoggedIn'    => TRUE
                ];
                $this->session->set($userSession);
                return redirect()->to('promotion');
            }
        }
        // dd(password_hash('123456',PASSWORD_DEFAULT));
        $this->session->setFlashdata('msg', 'Email atau password salah');
        return redirect()->to('auth/login');
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('auth/login');
    }
}
