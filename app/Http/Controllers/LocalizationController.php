<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocalizationController extends Controller
{
    public function changeLanguage($locale)
    {
        session(['locale' => $locale]);
        return redirect()->back();
    }
}
