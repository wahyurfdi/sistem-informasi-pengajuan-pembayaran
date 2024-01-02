<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Division;

class DivisionController extends Controller
{
    public function index()
    {
        $divisions = Division::orderBy('updated_at', 'DESC')->orderBy('name', 'ASC')->get();

        return view('master-data.division', compact('divisions'));
    }
}
