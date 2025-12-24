<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }
}
