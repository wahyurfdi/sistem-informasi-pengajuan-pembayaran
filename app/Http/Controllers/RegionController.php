<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Region;

class RegionController extends Controller
{
    public function index()
    {
        $regions = Region::orderBy('updated_at', 'DESC')->orderBy('name', 'ASC')->get();
        
        return view('master-data.region', compact('regions'));
    }
}
