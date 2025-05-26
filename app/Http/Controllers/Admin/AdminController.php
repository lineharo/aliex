<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductClick;
use App\Modules\Sber;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('areas.admin.index');
    }

    public function vk_auth()
    {
        return view('areas.admin.vk_auth');
    }

    public function sber_auth()
    {
        $sber = new Sber();
        $isAuth = $sber->checkAuth();

        return view('areas.admin.sber_auth',[
            'isAuth' => $isAuth,
        ]);
    }

    public function clicks()
    {
        $clicks = ProductClick::orderBy('id', 'desc')->paginate(40);

        return view('areas.admin.clicks', ['clicks' => $clicks]);
    }

}
