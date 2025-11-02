<?php

namespace App\Http\Controllers\Master\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserService;

class UserManagerController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
}
