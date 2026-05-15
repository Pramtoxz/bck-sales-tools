<?php

namespace App\Http\Controllers\Lms\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserKurikulumController extends Controller
{
    public function index()
    {
        return view('pages.lms.kurikulum.index');
    }
    public function show()
    {
        $auth =  Auth::user()->unreadNotifications->where('id',request('id'))->first();
        if ($auth) {
            $auth->markAsRead();
        }
        return view('pages.lms.notification.index');
    }
}
