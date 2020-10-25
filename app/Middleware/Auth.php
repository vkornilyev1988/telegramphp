<?php
namespace App\Middleware;

use Nekrida\Core\Controller as Controller;

class Auth extends Controller
{

    public function unauthorizedOnly() {
        return !$this->request->session('role');
    }

    public function correspondentOnly() {
        return $this->request->session('role') == 1;
    }

    public function telegraphistOnly() {
        return $this->request->session('role') == 2;
    }

    public function adminOnly() {
        return $this->request->session('role') == 3;
    }

    public function authorizedOnly() {
        return !!$this->request->session('role');
    }
}