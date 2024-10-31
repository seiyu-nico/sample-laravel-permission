<?php

namespace App\Http\Controllers;

use Auth;
use Cookie;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SelectCompanyController extends Controller
{
    public function show(): View|RedirectResponse
    {
        $companies = Auth::user()->companies;

        if ($companies->count() === 1) {
            Cookie::queue('company_id', $companies->first()->id);

            return redirect()->route('dashboard');
        }

        return view('select-company', [
            'companies' => $companies,
            'current_company_id' => (int) Cookie::get('company_id'),
        ]);
    }

    public function select(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
        ]);

        Cookie::queue('company_id', $request->company_id);

        return redirect()->route('dashboard');
    }
}
