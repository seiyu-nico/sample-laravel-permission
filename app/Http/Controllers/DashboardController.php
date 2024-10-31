<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Auth;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('dashboard', [
            'user' => Auth::user(),
            'company' => Company::find(request()->cookie('company_id')),
        ]);
    }
}
