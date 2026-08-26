<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Illuminate\View\View;

class AboutController extends Controller
{
    /**
     * Display the public resort information page.
     */
    public function index(): View
    {
        return view('user.about.index', [
            'settings' => GeneralSetting::instance(),
        ]);
    }
}
