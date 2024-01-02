<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\PaymentRequest;

class DashboardController extends Controller
{
    public function index()
    {
        $payreqStatusCount = PaymentRequest::select([
                DB::raw('SUM(IF(status = "PENDING", 1, 0)) AS pending'),
                DB::raw('SUM(IF(status = "REVIEW", 1, 0)) AS review'),
                DB::raw('SUM(IF(status = "RETURNED", 1, 0)) AS returned'),
                DB::raw('SUM(IF(status = "REJECTED", 1, 0)) AS rejected'),
                DB::raw('SUM(IF(status = "APPROVED", 1, 0)) AS approved'),
            ])
            ->first();

        return view('dashboard', compact('payreqStatusCount'));
    }
}
