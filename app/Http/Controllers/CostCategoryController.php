<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CostCategory;

class CostCategoryController extends Controller
{
    public function index()
    {
        $costCategories = CostCategory::orderBy('updated_at', 'DESC')->orderBy('name', 'ASC')->get();

        return view('master-data.cost-category', compact('costCategories'));
    }
}
