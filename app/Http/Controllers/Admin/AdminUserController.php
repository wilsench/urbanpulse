<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::withCount('activities')->withSum('activities', 'co2_avoided_kg')->latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }
}
