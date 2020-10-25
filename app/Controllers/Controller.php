<?php

namespace App\Controllers;

use Nekrida\Core\Controller as CoreController;
use Nekrida\Auth\User;
use Nekrida\Core\Request;

class Controller extends CoreController {
    protected $rights;
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $user = User::getById($this->request->session('user'));
        $user['rights'] = json_decode($user['rights'],true);
        $this->rights = $user['rights'];
        $request->setCache("user",$user);
    }
}
